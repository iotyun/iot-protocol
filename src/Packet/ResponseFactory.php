<?php
declare(strict_types=1);

namespace ModbusTcpClient\Packet;


use iot\iotprotocol\Exception\ModbusException;
use iot\iotprotocol\Exception\ParseException;
use iot\iotprotocol\Packet\ModbusFunction\ReadCoilsResponse;
use iot\iotprotocol\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use iot\iotprotocol\Packet\ModbusFunction\ReadInputDiscretesResponse;
use iot\iotprotocol\Packet\ModbusFunction\ReadInputRegistersResponse;
use iot\iotprotocol\Packet\ModbusFunction\ReadWriteMultipleRegistersResponse;
use iot\iotprotocol\Packet\ModbusFunction\WriteMultipleCoilsResponse;
use iot\iotprotocol\Packet\ModbusFunction\WriteMultipleRegistersResponse;
use iot\iotprotocol\Packet\ModbusFunction\WriteSingleCoilResponse;
use iot\iotprotocol\Packet\ModbusFunction\WriteSingleRegisterResponse;
use iot\iotprotocol\Utils\Types;

class ResponseFactory
{
    /**
     * @param string|null $binaryString
     * @return ModbusResponse
     * @throws \ModbusTcpClient\Exception\ModbusException
     */
    public static function parseResponse($binaryString): ModbusResponse
    {
        if ($binaryString === null || strlen($binaryString) < 9) { // 7 bytes for MBAP header and at least 2 bytes for PDU
            throw new ModbusException('Response null or data length too short to be valid packet!');
        }

        $functionCode = ord($binaryString[7]);

        if (($functionCode & ErrorResponse::EXCEPTION_BITMASK) > 0) {
            $functionCode -= ErrorResponse::EXCEPTION_BITMASK; //function code is in low bits of exception
            $exceptionCode = Types::parseByte($binaryString[8]);

            return new ErrorResponse(ModbusApplicationHeader::parse($binaryString), $functionCode, $exceptionCode);
        }

        $transactionId = Types::parseUInt16($binaryString[0] . $binaryString[1]);
        $unitId = Types::parseByte($binaryString[6]);

        $rawData = substr($binaryString, 8);

        switch ($functionCode) {
            case ModbusPacket::READ_HOLDING_REGISTERS:
                return new ReadHoldingRegistersResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::READ_INPUT_REGISTERS:
                return new ReadInputRegistersResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::READ_COILS:
                return new ReadCoilsResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::READ_INPUT_DISCRETES:
                return new ReadInputDiscretesResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::WRITE_SINGLE_COIL:
                return new WriteSingleCoilResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::WRITE_SINGLE_REGISTER:
                return new WriteSingleRegisterResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::WRITE_MULTIPLE_COILS:
                return new WriteMultipleCoilsResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::WRITE_MULTIPLE_REGISTERS:
                return new WriteMultipleRegistersResponse($rawData, $unitId, $transactionId);
                break;
            case ModbusPacket::READ_WRITE_MULTIPLE_REGISTERS:
                return new ReadWriteMultipleRegistersResponse($rawData, $unitId, $transactionId);
                break;
            default:
                throw new ParseException("Unknown function code '{$functionCode}' read from response packet");

        }
    }

    public static function parseResponseOrThrow($binaryString): ModbusResponse
    {
        $response = static::parseResponse($binaryString);
        if ($response instanceof ErrorResponse) {
            throw new ModbusException($response->getErrorMessage(), $response->getErrorCode());
        }
        return $response;
    }

}

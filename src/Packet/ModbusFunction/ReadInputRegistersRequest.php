<?php
declare(strict_types=1);

namespace iotyun\iotprotocol\Packet\ModbusFunction;

use iotyun\iotprotocol\Packet\ErrorResponse;
use iotyun\iotprotocol\Packet\ModbusPacket;
use iotyun\iotprotocol\Utils\Types;

/**
 * Request for Read Input Registers (FC=04)
 *
 * Example packet: \x00\x01\x00\x00\x00\x06\x01\x04\x00\x6B\x00\x01
 * \x00\x01 - transaction id
 * \x00\x00 - protocol id
 * \x00\x06 - number of bytes in the message (PDU = ProtocolDataUnit) to follow
 * \x01 - unit id
 * \x04 - function code
 * \x00\x6B - start address
 * \x00\x01 - input registers quantity to return
 *
 */
class ReadInputRegistersRequest extends ReadHoldingRegistersRequest
{
    public function getFunctionCode(): int
    {
        return ModbusPacket::READ_INPUT_REGISTERS;
    }

    /**
     * Parses binary string to ReadInputRegistersRequest or return ErrorResponse on failure
     *
     * @param $binaryString
     * @return ReadInputRegistersRequest|ErrorResponse
     */
    public static function parse($binaryString)
    {
        return self::parseStartAddressPacket(
            $binaryString,
            12,
            ModbusPacket::READ_INPUT_REGISTERS,
            function (int $transactionId, int $unitId, int $startAddress) use ($binaryString) {
                $quantity = Types::parseUInt16($binaryString[10] . $binaryString[11]);
                return new self($startAddress, $quantity, $unitId, $transactionId);
            }
        );
    }
}

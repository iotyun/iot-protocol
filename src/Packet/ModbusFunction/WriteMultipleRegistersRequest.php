<?php
declare(strict_types=1);

namespace iotyun\iotprotocol\Packet\ModbusFunction;


use iotyun\iotprotocol\Exception\InvalidArgumentException;
use iotyun\iotprotocol\Packet\ErrorResponse;
use iotyun\iotprotocol\Packet\ModbusApplicationHeader;
use iotyun\iotprotocol\Packet\ModbusPacket;
use iotyun\iotprotocol\Packet\ModbusRequest;
use iotyun\iotprotocol\Packet\ProtocolDataUnitRequest;
use iotyun\iotprotocol\Utils\Registers;
use iotyun\iotprotocol\Utils\Types;

/**
 * Request for Write Multiple Registers (FC=16)
 *
 * Example packet: \x01\x38\x00\x00\x00\x0d\x11\x10\x04\x10\x00\x03\x06\x00\xC8\x00\x82\x87\x01
 * \x01\x38 - transaction id
 * \x00\x00 - protocol id
 * \x00\x0d - number of bytes in the message (PDU = ProtocolDataUnit) to follow
 * \x11 - unit id
 * \x10 - function code
 * \x04\x10 - start address
 * \x00\x03 - count of register to write
 * \x06 - registers byte count
 * \x00\xC8\x00\x82\x87\x01 - registers data
 *
 */
class WriteMultipleRegistersRequest extends ProtocolDataUnitRequest implements ModbusRequest
{
    /**
     * @var array registers (array of bytes)
     */
    private $registers;
    private $registersCount;
    private $registersBytesSize;

    public function __construct(int $startAddress, array $registers, int $unitId = 0, int $transactionId = null)
    {
        $this->registers = $registers;
        $this->registersBytesSize = Registers::getRegisterArrayByteSize($this->registers);
        $this->registersCount = $this->registersBytesSize / 2;

        parent::__construct($startAddress, $unitId, $transactionId);

        $this->validate();
    }

    public function validate()
    {
        parent::validate();

        if ($this->registersCount === 0 || $this->registersCount > 124) {
            // as request contain 1 byte field 'registersBytesSize' to indicate number of bytes to follow
            // there is no way more than 124 words (124*2 bytes) can be written as this field would overflow
            throw new InvalidArgumentException("registers count out of range (1-124): {$this->registersCount}", 3);
        }
    }

    public function getFunctionCode(): int
    {
        return ModbusPacket::WRITE_MULTIPLE_REGISTERS;
    }

    public function __toString(): string
    {
        return parent::__toString()
            . Types::toRegister($this->registersCount)
            . Types::toByte($this->registersBytesSize)
            . Registers::getRegisterArrayAsByteString($this->registers);
    }

    /**
     * @return array
     */
    public function getRegisters(): array
    {
        return $this->registers;
    }

    protected function getLengthInternal(): int
    {
        // (function code size (1) + startAddress size (2)) + registers count size (2) + register byte size (1) + number of bytes registers need for data
        return parent::getLengthInternal() + (3 + $this->registersBytesSize);
    }

    /**
     * Parses binary string to WriteMultipleRegistersRequest or return ErrorResponse on failure
     *
     * @param $binaryString
     * @return WriteMultipleRegistersRequest|ErrorResponse
     */
    public static function parse($binaryString)
    {
        return self::parseStartAddressPacket(
            $binaryString,
            15,
            ModbusPacket::WRITE_MULTIPLE_REGISTERS,
            function (int $transactionId, int $unitId, int $startAddress) use ($binaryString) {
                $quantity = Types::parseUInt16($binaryString[10] . $binaryString[11]);
                $byteCount = Types::parseByte($binaryString[12]);

                $registers = str_split(substr($binaryString, 13, $byteCount), 2);
                if ($quantity !== count($registers)) {
                    return new ErrorResponse(
                        new ModbusApplicationHeader(2, $unitId, $transactionId),
                        ModbusPacket::WRITE_MULTIPLE_REGISTERS,
                        3 // Illegal data value
                    );
                }
                return new self($startAddress, $registers, $unitId, $transactionId);
            }
        );
    }
}

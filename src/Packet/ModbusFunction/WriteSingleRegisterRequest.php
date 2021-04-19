<?php
declare(strict_types=1);

namespace iotyun\iotprotocol\Packet\ModbusFunction;

use iotyun\iotprotocol\Exception\InvalidArgumentException;
use iotyun\iotprotocol\Packet\ErrorResponse;
use iotyun\iotprotocol\Packet\ModbusPacket;
use iotyun\iotprotocol\Packet\ModbusRequest;
use iotyun\iotprotocol\Packet\ProtocolDataUnitRequest;
use iotyun\iotprotocol\Packet\Word;
use iotyun\iotprotocol\Utils\Types;


/**
 * Request for Write Single Register (FC=06)
 *
 * Example packet: \x00\x01\x00\x00\x00\x06\x11\x06\x00\x6B\x01\x01
 * \x00\x01 - transaction id
 * \x00\x00 - protocol id
 * \x00\x06 - number of bytes in the message (PDU = ProtocolDataUnit) to follow
 * \x11 - unit id
 * \x06 - function code
 * \x00\x6B - start address
 * \x01\x01 - register data
 *
 */
class WriteSingleRegisterRequest extends ProtocolDataUnitRequest implements ModbusRequest
{

    /**
     * @var int value to be sent to modbus
     */
    private $value;

    public function __construct(int $startAddress, int $value, int $unitId = 0, int $transactionId = null)
    {
        parent::__construct($startAddress, $unitId, $transactionId);
        $this->value = $value;

        $this->validate();
    }

    public function validate()
    {
        parent::validate();
        // value is 2 bytes in packet so it must be set and in range of uint16 (0 - 65535) or int16 (-32768 - +32767)
        if ((null !== $this->value) && (($this->value >= Types::MIN_VALUE_INT16) && ($this->value <= Types::MAX_VALUE_UINT16))) {
            return;
        }
        throw new InvalidArgumentException("value is not set or out of range (u)int16: {$this->value}", 3);
    }

    public function getFunctionCode(): int
    {
        return ModbusPacket::WRITE_SINGLE_REGISTER;
    }

    public function __toString(): string
    {
        return parent::__toString()
            . Types::toRegister($this->getValue());
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return Word
     */
    public function getValueAsWord(): Word
    {
        return new Word(Types::toInt16($this->value));
    }

    protected function getLengthInternal(): int
    {
        return parent::getLengthInternal() + 2; // value size (2 bytes)
    }

    /**
     * Parses binary string to WriteSingleRegisterRequest or return ErrorResponse on failure
     *
     * @param $binaryString
     * @return WriteSingleRegisterRequest|ErrorResponse
     */
    public static function parse($binaryString)
    {
        return self::parseStartAddressPacket(
            $binaryString,
            12,
            ModbusPacket::WRITE_SINGLE_REGISTER,
            function (int $transactionId, int $unitId, int $startAddress) use ($binaryString) {
                $value = Types::parseInt16($binaryString[10] . $binaryString[11]);
                return new self($startAddress, $value, $unitId, $transactionId);
            }
        );
    }
}

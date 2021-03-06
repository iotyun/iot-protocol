<?php
declare(strict_types=1);

namespace iotyun\iotprotocol\Packet\ModbusFunction;

use iotyun\iotprotocol\Exception\InvalidArgumentException;
use iotyun\iotprotocol\Packet\ErrorResponse;
use iotyun\iotprotocol\Packet\ModbusApplicationHeader;
use iotyun\iotprotocol\Packet\ModbusPacket;
use iotyun\iotprotocol\Packet\ModbusRequest;
use iotyun\iotprotocol\Packet\ProtocolDataUnitRequest;
use iotyun\iotprotocol\Utils\Types;

/**
 * Request for Write Multiple Coils (FC=15)
 *
 * Example packet: \x01\x38\x00\x00\x00\x08\x11\x0F\x04\x10\x00\x03\x01\x05
 * \x01\x38 - transaction id
 * \x00\x00 - protocol id
 * \x00\x08 - number of bytes in the message (PDU = ProtocolDataUnit) to follow
 * \x11 - unit id
 * \x0F - function code
 * \x04\x10 - start address
 * \x00\x03 - count of coils to write
 * \x01 - coils byte count
 * \x05 - coils data
 *
 */
class WriteMultipleCoilsRequest extends ProtocolDataUnitRequest implements ModbusRequest
{
    /**
     * @var array coils (array of booleans)
     */
    private $coils;
    private $coilCount;
    private $coilBytesSize;

    public function __construct(int $startAddress, array $coils, int $unitId = 0, int $transactionId = null)
    {
        $this->coils = $coils;
        $this->coilCount = count($this->coils);
        $this->coilBytesSize = (int)(($this->coilCount + 7) / 8);

        parent::__construct($startAddress, $unitId, $transactionId);

        $this->validate();
    }

    public function validate()
    {
        parent::validate();

        if ($this->coilCount === 0 || $this->coilCount > 2048) {
            throw new InvalidArgumentException("coils count out of range (1-2048): {$this->coilCount}", 3);
        }
    }

    public function getFunctionCode(): int
    {
        return ModbusPacket::WRITE_MULTIPLE_COILS;
    }

    public function __toString(): string
    {
        return parent::__toString()
            . Types::toRegister($this->coilCount)
            . Types::toByte($this->coilBytesSize)
            . Types::byteArrayToByte(Types::booleanArrayToByteArray($this->coils));
    }

    /**
     * @return array
     */
    public function getCoils(): array
    {
        return $this->coils;
    }

    protected function getLengthInternal(): int
    {
        return parent::getLengthInternal() + (3 + $this->coilBytesSize); // coilCount + coilBytesSize + number of bytes coils need for data
    }

    /**
     * Parses binary string to WriteMultipleCoilsRequest or return ErrorResponse on failure
     *
     * @param $binaryString
     * @return WriteMultipleCoilsRequest|ErrorResponse
     */
    public static function parse($binaryString)
    {
        return self::parseStartAddressPacket(
            $binaryString,
            14,
            ModbusPacket::WRITE_MULTIPLE_COILS,
            function (int $transactionId, int $unitId, int $startAddress) use ($binaryString) {
                $quantity = Types::parseUInt16($binaryString[10] . $binaryString[11]);
                $byteCount = Types::parseByte($binaryString[12]);
                $coils = Types::binaryStringToBooleanArray(substr($binaryString, 13, $byteCount));
                if ($quantity > count($coils)) {
                    return new ErrorResponse(
                        new ModbusApplicationHeader(2, $unitId, $transactionId),
                        ModbusPacket::WRITE_MULTIPLE_COILS,
                        3 // Illegal data value
                    );
                }
                $coils = array_slice($coils, 0, $quantity);
                return new self($startAddress, $coils, $unitId, $transactionId);
            }
        );
    }
}

<?php

namespace iotyun\iotprotocol\Composer\Read\Register;

use iotyun\iotprotocol\Composer\Address;
use iotyun\iotprotocol\Packet\ModbusResponse;

class BitReadRegisterAddress extends ReadRegisterAddress
{
    /** @var int */
    private $bit;

    public function __construct(int $address, int $bit, string $name = null, callable $callback = null, callable $errorCallback = null)
    {
        $type = Address::TYPE_BIT;
        parent::__construct($address, $type, $name ?: "{$type}_{$address}_{$bit}", $callback, $errorCallback);
        $this->bit = $bit;
    }

    protected function extractInternal(ModbusResponse $response)
    {
        return $response->getWordAt($this->address)->isBitSet($this->bit);
    }

    public function getBit(): int
    {
        return $this->bit;
    }

    protected function getAllowedTypes(): array
    {
        return [Address::TYPE_BIT];
    }
}

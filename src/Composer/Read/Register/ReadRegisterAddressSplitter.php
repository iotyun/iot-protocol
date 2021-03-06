<?php

namespace iotyun\iotprotocol\Composer\Read\Register;


use iotyun\iotprotocol\Composer\AddressSplitter;

class ReadRegisterAddressSplitter extends AddressSplitter
{
    /** @var string */
    private $requestClass;

    public function __construct(string $requestClass)
    {
        $this->requestClass = $requestClass;
    }

    protected function createRequest(string $uri, array $addressesChunk, int $startAddress, int $quantity, int $unitId = 0)
    {
        return new ReadRegisterRequest($uri, $addressesChunk, new $this->requestClass($startAddress, $quantity, $unitId));
    }
}

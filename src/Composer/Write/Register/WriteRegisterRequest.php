<?php

namespace iot\iotprotocol\Composer\Write\Register;


use iot\iotprotocol\Composer\Request;
use iot\iotprotocol\Exception\ModbusException;
use iot\iotprotocol\Packet\ModbusRequest;
use iot\iotprotocol\Packet\ModbusResponse;
use iot\iotprotocol\Packet\ResponseFactory;

class WriteRegisterRequest implements Request
{
    /**
     * @var string uri to modbus server. Example: 'tcp://192.168.100.1:502'
     */
    private $uri;

    /** @var ModbusRequest */
    private $request;

    /** @var array */
    private $addresses;


    public function __construct(string $uri, array $addresses, ModbusRequest $request)
    {
        $this->request = $request;
        $this->uri = $uri;
        $this->addresses = $addresses;
    }

    /**
     * @return ModbusRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return WriteRegisterAddress[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function __toString()
    {
        return $this->request->__toString();
    }

    /**
     * @param string $binaryData
     * @return ModbusResponse
     * @throws ModbusException
     */
    public function parse(string $binaryData): ModbusResponse
    {
        return ResponseFactory::parseResponse($binaryData);
    }
}

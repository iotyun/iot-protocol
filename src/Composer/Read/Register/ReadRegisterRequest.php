<?php

namespace iot\iotprotocol\Composer\Read\Register;


use iot\iotprotocol\Composer\Request;
use iot\iotprotocol\Exception\ModbusException;
use iot\iotprotocol\Packet\ErrorResponse;
use iot\iotprotocol\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use iot\iotprotocol\Packet\ResponseFactory;

class ReadRegisterRequest implements Request
{
    /**
     * @var string uri to modbus server. Example: 'tcp://192.168.100.1:502'
     */
    private $uri;

    /** @var ReadRegisterAddress[] */
    private $addresses;

    /** @var ReadHoldingRegistersRequest */
    private $request;


    public function __construct(string $uri, array $addresses, $request)
    {
        $this->addresses = $addresses;
        $this->request = $request;
        $this->uri = $uri;
    }

    /**
     * @return ReadHoldingRegistersRequest
     */
    public function getRequest(): ReadHoldingRegistersRequest
    {
        return $this->request;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ReadRegisterAddress[]
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
     * @return array|ErrorResponse
     * @throws ModbusException
     * @throws \Exception
     */
    public function parse(string $binaryData)
    {
        $response = ResponseFactory::parseResponse($binaryData)->withStartAddress($this->request->getStartAddress());
        if ($response instanceof ErrorResponse) {
            return $response;
        }

        $result = [];
        foreach ($this->addresses as $address) {
            $result[$address->getName()] = $address->extract($response);
        }
        return $result;
    }
}

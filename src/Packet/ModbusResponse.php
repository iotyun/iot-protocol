<?php

namespace iot\iotprotocol\Packet;


interface ModbusResponse extends ModbusPacket
{
    public function withStartAddress(int $startAddress);
}
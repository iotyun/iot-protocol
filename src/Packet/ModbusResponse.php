<?php

namespace iotyun\iotprotocol\Packet;


interface ModbusResponse extends ModbusPacket
{
    public function withStartAddress(int $startAddress);
}
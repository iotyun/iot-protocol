<?php

namespace Tests\unit\Composer\Write\Coil;


use iot\iotprotocol\Composer\AddressSplitter;
use iot\iotprotocol\Composer\Write\Coil\WriteCoilAddress;
use iot\iotprotocol\Composer\Write\Coil\WriteCoilAddressSplitter;
use iot\iotprotocol\Exception\InvalidArgumentException;
use iot\iotprotocol\Packet\ModbusFunction\WriteMultipleCoilsRequest;
use PHPUnit\Framework\TestCase;

class WriteCoilAddressSplitterTest extends TestCase
{
    public function testSplitSameAddress()
    {
        $this->expectExceptionMessage("Trying to write addresses that seem share their memory range! 256 with 256");
        $this->expectException(InvalidArgumentException::class);

        $splitter = new WriteCoilAddressSplitter(WriteMultipleCoilsRequest::class);

        $requests = $splitter->split([
            'tcp://127.0.0.1' . AddressSplitter::UNIT_ID_PREFIX . '1' => [
                new WriteCoilAddress(256, true),
                new WriteCoilAddress(256, false),
            ]
        ]);

        $this->assertEquals(1, $requests);
    }
}

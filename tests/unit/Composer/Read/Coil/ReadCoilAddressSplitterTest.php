<?php

namespace Tests\unit\Composer\Write\Coil;


use iot\iotprotocol\Composer\AddressSplitter;
use iot\iotprotocol\Composer\Read\Coil\ReadCoilAddress;
use iot\iotprotocol\Composer\Read\Coil\ReadCoilAddressSplitter;
use iot\iotprotocol\Packet\ModbusFunction\ReadCoilsRequest;
use PHPUnit\Framework\TestCase;

class ReadCoilAddressSplitterTest extends TestCase
{
    public function testSplitSameAddress()
    {
        $splitter = new ReadCoilAddressSplitter(ReadCoilsRequest::class);

        $requests = $splitter->split([
            'tcp://127.0.0.1' . AddressSplitter::UNIT_ID_PREFIX . '1' => [
                new ReadCoilAddress(256),
                new ReadCoilAddress(256),
            ]
        ]);

        $this->assertCount(1, $requests);
        $this->assertCount(2, $requests[0]->getAddresses());
    }
}

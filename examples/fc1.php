<?php

use iot\iotprotocol\Network\BinaryStreamConnection;
use iot\iotprotocol\Packet\ModbusFunction\ReadCoilsRequest;
use iot\iotprotocol\Packet\ModbusFunction\ReadCoilsResponse;
use iot\iotprotocol\Packet\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';

$connection = BinaryStreamConnection::getBuilder()
    ->setPort(5020)
    ->setHost('127.0.0.1')
    ->build();

$startAddress = 12288;
$quantity = 16;
$packet = new ReadCoilsRequest($startAddress, $quantity);
echo 'Packet to be sent (in hex): ' . $packet->toHex() . PHP_EOL;

try {
    $binaryData = $connection->connect()
        ->sendAndReceive($packet);
    echo 'Binary received (in hex):   ' . unpack('H*', $binaryData)[1] . PHP_EOL;

    /* @var $response ReadCoilsResponse */
    $response = ResponseFactory::parseResponseOrThrow($binaryData);
    echo 'Parsed packet (in hex):     ' . $response->toHex() . PHP_EOL;
    echo 'Data parsed from packet (bytes):' . PHP_EOL;
    print_r($response->getCoils());

    // set internal index to match start address to simplify array access
    $responseWithStartAddress = $response->withStartAddress($startAddress);
    print_r($responseWithStartAddress[12288]); // coil value at 12288

} catch (Exception $exception) {
    echo 'An exception occurred' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
} finally {
    $connection->close();
}

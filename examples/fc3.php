<?php

use iot\iotprotocol\Network\BinaryStreamConnection;
use iot\iotprotocol\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use iot\iotprotocol\Packet\ModbusFunction\ReadHoldingRegistersResponse;
use iot\iotprotocol\Packet\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/logger.php';

$connection = BinaryStreamConnection::getBuilder()
    ->setPort(5020)
    ->setHost('127.0.0.1')
    ->setConnectTimeoutSec(1.5) // timeout when establishing connection to the server
    ->setWriteTimeoutSec(0.5) // timeout when writing/sending packet to the server
    ->setReadTimeoutSec(0.3) // timeout when waiting response from server
    ->setLogger(new EchoLogger())
    ->build();

$startAddress = 256;
$quantity = 6;
$unitID = 0;
$packet = new ReadHoldingRegistersRequest($startAddress, $quantity, $unitID);

try {
    $binaryData = $connection->connect()->sendAndReceive($packet);

    /**
     * @var $response ReadHoldingRegistersResponse
     */
    $response = ResponseFactory::parseResponseOrThrow($binaryData);

    print_r($response->getData());
    foreach ($response as $word) {
        print_r($word->getBytes());
    }
    foreach ($response->asDoubleWords() as $doubleWord) {
        print_r($doubleWord->getBytes());
    }

    // set internal index to match start address to simplify array access
    $responseWithStartAddress = $response->withStartAddress($startAddress);
    print_r($responseWithStartAddress[$startAddress]->getBytes()); // use array access to get word
    print_r($responseWithStartAddress->getDoubleWordAt($startAddress)->getFloat());

} catch (Exception $exception) {
    echo 'An exception occurred' . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
} finally {
    $connection->close();
}

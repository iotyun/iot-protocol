<?php

namespace iot\iotprotocol\Composer;


interface Request
{
    public function parse(string $binaryData);

    public function getRequest();

    public function getUri(): string;
}
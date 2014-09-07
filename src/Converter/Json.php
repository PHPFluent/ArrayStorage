<?php

namespace PHPFluent\ArrayStorage\Converter;

use Traversable;

class Json implements Converter
{
    protected $arrayConverter;
    protected $jsonEncodeFlags;

    public function __construct($jsonEncodeFlags = JSON_PRETTY_PRINT, Arr $arrayConverter = null)
    {
        $this->jsonEncodeFlags = $jsonEncodeFlags;
        $this->arrayConverter = $arrayConverter ?: new Arr(true);
    }

    public function convert(Traversable $traversable)
    {
        $data = $this->arrayConverter->convert($traversable);
        $result = json_encode($data, $this->jsonEncodeFlags);

        return $result;
    }
}

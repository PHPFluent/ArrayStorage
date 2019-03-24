<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Converter;

use Traversable;
use function json_encode;
use const JSON_PRETTY_PRINT;

class Json implements Converter
{
    /**
     * @var int
     */
    protected $jsonEncodeFlags;

    public function __construct(int $jsonEncodeFlags = JSON_PRETTY_PRINT)
    {
        $this->jsonEncodeFlags = $jsonEncodeFlags;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Traversable $traversable)
    {
        return json_encode((new Arr(true))->convert($traversable), $this->jsonEncodeFlags);
    }
}

<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Converter;

use DOMDocument;
use DOMElement;
use ReflectionObject;
use stdClass;
use Traversable;
use function is_array;
use function is_int;
use function is_object;

class Xml implements Converter
{
    /**
     * @var DOMDocument
     */
    protected $document;

    public function __construct(?DOMDocument $document = null)
    {
        if ($document === null) {
            $document = new DOMDocument('1.0', 'UTF-8');
            $document->formatOutput = true;
        }

        $this->document = $document;
    }

    /**
     * @param mixed $value
     */
    protected function getNodeName($value): string
    {
        if (is_object($value)) {
            return (new ReflectionObject($value))->getShortName();
        }

        return 'value';
    }

    /**
     * @param Traversable|mixed[] $traversable
     */
    protected function parseNode($traversable, DOMElement $parentNode): void
    {
        foreach ($traversable as $key => $value) {
            $nodeName = is_int($key) ? $this->getNodeName($value) : $key;
            $childNode = $this->document->createElement($nodeName);
            $parentNode->appendChild($childNode);
            if ($value instanceof Traversable
                || $value instanceof stdClass
                || is_array($value)) {
                $this->parseNode($value, $childNode);
                continue;
            }
            $childNode->nodeValue = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Traversable $traversable)
    {
        $name = $this->getNodeName($traversable);
        $childNode = $this->document->createElement($name);
        $this->document->appendChild($childNode);
        $this->parseNode($traversable, $childNode);

        return $this->document->saveXml();
    }
}

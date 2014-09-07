<?php

namespace PHPFluent\ArrayStorage\Converter;

use DOMDocument;
use DOMElement;
use ReflectionObject;
use stdClass;
use Traversable;

class Xml implements Converter
{
    protected $document;

    public function __construct(DOMDocument $document = null)
    {
        if (null === $document) {
            $document = new DOMDocument('1.0', 'UTF-8');
            $document->formatOutput = true;
        }

        $this->document = $document;
    }

    protected function getNodeName($value)
    {
        if (is_object($value)) {
            return (new ReflectionObject($value))->getShortName();
        }

        return 'value';
    }

    protected function parseNode($traversable, DOMElement $parentNode)
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

    public function convert(Traversable $traversable)
    {
        $name = $this->getNodeName($traversable);
        $childNode = $this->document->createElement($name);
        $this->document->appendChild($childNode);
        $this->parseNode($traversable, $childNode);

        return $this->document->saveXml();
    }
}

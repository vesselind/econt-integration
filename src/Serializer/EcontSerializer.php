<?php

declare(strict_types=1);

namespace Econt\EcontApi\Serializer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EcontSerializer
{
    private Serializer $serializer;

    public function __construct()
    {
        $normalizer = new ObjectNormalizer(
            null,
            null,
            null,
            null,
            null,
            ['disable_type_enforcement' => true]
        );

        $this->serializer = new Serializer([$normalizer], [new XmlEncoder()]);
    }

    public function serialize(mixed $data, string $format = 'xml'): string
    {
        return $this->serializer->serialize($data, $format);
    }

    public function deserialize(string $data, string $type, string $format = 'xml'): mixed
    {
        return $this->serializer->deserialize($data, $type, $format);
    }

    public function arrayToXml(array $data, string $rootElement = 'request'): string
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootElement}/>");

        $this->buildXmlFromArray($xml, $data);

        return $xml->asXML();
    }

    private function buildXmlFromArray(\SimpleXMLElement $xml, array $data): void
    {
        foreach ($data as $key => $value) {
            $key = $this->camelToSnake($key);

            if (is_array($value)) {
                $child = $xml->addChild($key);
                $this->buildXmlFromArray($child, $value);
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
            }
        }
    }

    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
    }

    public function xmlToArray(string $xmlString): array
    {
        $xml = @simplexml_load_string($xmlString);

        if ($xml === false) {
            return [];
        }

        return $this->xmlToArrayRecursive($xml);
    }

    private function xmlToArrayRecursive(\SimpleXMLElement $element): array
    {
        $result = [];

        foreach ($element->children() as $child) {
            $name = $this->snakeToCamel($child->getName());
            $value = count($child->children()) > 0 ? $this->xmlToArrayRecursive($child) : (string) $child;

            $result[$name] = $value;
        }

        return $result;
    }

    private function snakeToCamel(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}
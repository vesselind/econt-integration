<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model;

use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;

abstract class AbstractModel
{
    public function toArray(): array
    {
        $result = [];
        $reflector = new \ReflectionClass($this);

        foreach ($reflector->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            if ($value === null) {
                continue;
            }

            if (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $result[$property->getName()] = $value->toArray();
                } elseif ($value instanceof \DateTimeInterface) {
                    $result[$property->getName()] = $value->format('Y-m-d H:i:s');
                }
            } elseif (is_array($value)) {
                $result[$property->getName()] = array_map(
                    function ($item) {
                        if ($item instanceof AbstractModel) {
                            return $item->toArray();
                        }
                        if ($item instanceof \DateTimeInterface) {
                            return $item->format('Y-m-d H:i:s');
                        }
                        return $item;
                    },
                    $value
                );
            } else {
                $result[$property->getName()] = $value;
            }
        }

        return $result;
    }

    public static function fromArray(array $data): static
    {
        $instance = new static();
        $reflector = new \ReflectionClass($instance);

        foreach ($reflector->getProperties() as $property) {
            $propertyName = $property->getName();

            if (!array_key_exists($propertyName, $data)) {
                continue;
            }

            $property->setAccessible(true);
            $value = $data[$propertyName];

            if ($value === null) {
                continue;
            }

            $propertyType = $property->getType();
            if ($propertyType && !$propertyType->isBuiltin()) {
                $typeClass = $propertyType->getName();
                if (is_a($typeClass, AbstractModel::class, true)) {
                    $value = is_array($value) ? $typeClass::fromArray($value) : null;
                }
            }

            $property->setValue($instance, $value);
        }

        return $instance;
    }
}
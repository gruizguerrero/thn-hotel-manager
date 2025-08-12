<?php

namespace App\Shared\Domain\Service;

use ReflectionClass;
use ReflectionProperty;

final class ReflectionManager
{
    public static function create(): self
    {
        return new self();
    }

    public function buildObject(string $className, array $params = []): object
    {
        $reflectionClass = new ReflectionClass($className);
        $object = $reflectionClass->newInstanceWithoutConstructor();
        $this->setPropertyValues($params, $object);

        return $object;
    }

    /**
     * @return ReflectionProperty[]
     */
    public function getProperties(string $classname): array
    {
        $reflectionClass = new ReflectionClass($classname);

        return $reflectionClass->getProperties();
    }

    private function setPropertyValues(array $params, object $object): void
    {
        foreach ($params as $paramName => $paramValue) {
            $this->setPropertyValue($object, $paramName, $paramValue);
        }
    }

    public function getPropertyValue(object $object, string $propertyName)
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, $object, $propertyName);
        $reflectionProperty->setAccessible(true);

        $value = $reflectionProperty->getValue($object);

        $reflectionProperty->setAccessible(false);

        return $value;
    }

    public function setPropertyValue(object $object, string $propertyName, $value): void
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, $object, $propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
        $reflectionProperty->setAccessible(false);
    }

    private function getPropertyConsideringParentClasses(ReflectionClass $reflectionClass, $object, string $propertyName): ReflectionProperty
    {
        try {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
        } catch (\ReflectionException $e) {
            if (!get_parent_class($object)) {
                throw $e;
            }
            $reflectionClass = new ReflectionClass(get_parent_class($object));
            $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, get_parent_class($object), $propertyName);
        }

        return $reflectionProperty;
    }
}
<?php

namespace App\Traits;

use App\CMS\BaseComponent;

trait ComponentInitializer
{
    protected function initializeComponents(): void
    {
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            if (!$property->isInitialized($this) && is_subclass_of($property->getType()->getName(), BaseComponent::class)) {
                $this->initializeBaseComponentProperty($property);
            }
        }
    }

    protected function initializeBaseComponentProperty(\ReflectionProperty $property): void
    {
        $propertyType = $property->getType()->getName();
        $instance = new $propertyType($this->getPropertyAttributes($property));
        $property->setValue($this, $instance);
        $this->children[$property->getName()] = $instance;
    }

    protected function getPropertyAttributes(\ReflectionProperty $property): array
    {
        $propertyAttrs = [];
        foreach ($property->getAttributes() as $attr) {
            $attrInstance = $attr->newInstance();
            $propertyAttrs[get_class($attrInstance)] = $attrInstance;
        }

        return $propertyAttrs;
    }
}
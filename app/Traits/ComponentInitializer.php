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
                $this->initializeProperty($property);
            }
        }
    }

    protected function initializeProperty(\ReflectionProperty $property): void
    {
        $component = $this->createComponent($property);
        $component->setParent($this, $property->getName());
        
        $property->setValue($this, $component);

        $this->children[$property->getName()] = $component;
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

    protected function createComponent(\ReflectionProperty $property): BaseComponent
    {
        $propertyType = $property->getType()->getName();

        return new $propertyType($this->getPropertyAttributes($property));
    }
}

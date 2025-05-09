<?php

namespace App\CMS;

use App\Interfaces\Renderable;
use App\Traits\ComponentInitializer;
use App\Attrs\AdminAttr;
use App\Attrs\DBAttr;

abstract class BaseComponent implements Renderable
{
    use ComponentInitializer;
    
    protected array $attributes = [];
    protected array $children = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->initializeComponents();
    }

    abstract public function render(): string;

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getAttribute(string $code, string $key): mixed
    {
        if (!isset($this->attributes[$code])) {
            throw new \Exception("Attribute class $code dosn't add for this instance of BaseComponent!");
        }

        if (!isset($this->attributes[$key])) {
            throw new \Exception("Attribute $key not found!");
        }

        return $this->attributes[$code][$key];
    }

    public function getAdminAttribute(string $key): mixed 
    {
        return $this->getAttribute(AdminAttr::class, $key);
    }

    public function getDBAttribute(string $key): mixed
    {
        return $this->getAttribute(DBAttr::class, $key);
    }

    // protected function initializeComponents(): void
    // {
    //     $reflection = new \ReflectionClass($this);

    //     foreach ($reflection->getProperties() as $property) {
    //         $propertyType = $property->getType()->getName();
    //         if (!is_subclass_of($propertyType, BaseComponent::class)) {
    //             continue;
    //         }
            
    //         $propertyAttrs = [];
    //         foreach ($property->getAttributes() as $attr) {
    //             $attrInstance = $attr->newInstance();
    //             $propertyAttrs[get_class($attrInstance)] = $attrInstance;
    //         }

    //         $component = new $propertyType($propertyAttrs);
    //         $property->setValue($this, $component);
    //         $this->children[$property->getName()] = $component;
    //     }
    // }
}

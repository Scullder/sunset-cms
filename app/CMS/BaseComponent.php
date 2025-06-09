<?php

namespace App\CMS;

use App\Interfaces\Renderable;
use App\Traits\ComponentInitializer;
use App\Attrs\AdminAttr;
use App\Attrs\DBAttr;
use App\CMS\Collection;
use Illuminate\Support\Str;

abstract class BaseComponent implements Renderable
{
    use ComponentInitializer;

    protected array $attributes = [];
    protected array $children = [];
    protected string $path = '';
    protected ?BaseComponent $parent = null;
    protected string $parentPropertyName = '';

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

    public function getAttribute(string $code, ?string $key = null): mixed
    {
        if (!isset($this->attributes[$code])) {
            throw new \Exception("Attribute class $code dosn't add for this instance of BaseComponent!");
        }

        if ($key && !isset($this->attributes[$key])) {
            throw new \Exception("Attribute $key not found!");
        }

        if (!$key) {
            return $this->attributes[$code];
        }
        
        return $this->attributes[$code][$key];
    }

    public function getAdminAttribute(?string $key = null): mixed
    {
        return $this->getAttribute(AdminAttr::class, $key);
    }

    public function getDBAttribute(?string $key = null): mixed
    {
        return $this->getAttribute(DBAttr::class, $key);
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setParent(BaseComponent $parent, string $parentPropertyName): void
    {
        $this->parent = $parent;
        $this->parentPropertyName = $parentPropertyName;

        $this->updatePath();
        $this->updateChildPaths();
    }

    public function getParent(): ?BaseComponent
    {
        return $this->parent;
    }

    public function setParentPropertyName(string $name): void
    {
        $this->parentPropertyName = $name;
    }

    public function getParentPropertyName(): string
    {
        return $this->parentPropertyName;
    }

    protected function isCollection(): bool
    {
        return $this instanceof Collection;
    }

    public function updateChildPaths(): void
    {
        foreach ($this->children as $child) {
            $child->updatePath();
            $child->updateChildPaths();
        }

        // Only handle items if this is a collection
        if ($this->isCollection()) {
            /** @var Collection $this */
            foreach ($this->items as $index => $item) {
                $item->updatePath();
                $item->updateChildPaths();
            }
        }
    }

    protected function updatePath(): void
    {
        $this->path = '';

        if ($this->parent) {
            if (!$parentPath = $this->parent->getPath()) {
                $parentPath = Str::snake(class_basename($this->parent));
            }

            $this->path = ($parentPath != '') 
                ? "$parentPath.{$this->parentPropertyName}" 
                : $this->parentPropertyName;
        }
    }
}

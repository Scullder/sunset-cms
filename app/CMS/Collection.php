<?php

namespace App\CMS;

use App\CMS\BaseComponent;

abstract class Collection extends BaseComponent implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected array $items = [];
    protected string $itemType;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!is_subclass_of($this->itemType, BaseComponent::class)) {
            throw new \InvalidArgumentException("Collection item type must extend BaseComponent");
        }
    }

    // ArrayAccess methods
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    protected function validateItem(mixed $value): void
    {
        if (!$value instanceof $this->itemType) {
            throw new \InvalidArgumentException(sprintf(
                'Collection only accepts items of type %s, %s given',
                $this->itemType,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateItem($value);

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }

        // $this->children[] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    // Countable method
    public function count(): int
    {
        return count($this->items);
    }

    // IteratorAggregate method
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function add(BaseComponent $item): void
    {
        $this[] = $item;
        $item->setParent($this, 'items[' . (count($this->items) - 1) . ']');
        // $this->updateChildPaths();
    }

    public function remove(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index
        // $this->updateChildPaths();
    }

    public function render(): string
    {
        $output = '<div class="component-collection" style="border:1px solid #ccc;padding:1rem;margin:1rem 0">';
        $output .= '<h3>' . $this->getAdminAttribute('label') . '</h3>';

        foreach ($this->items as $item) {
            $output .= $item->render();
        }

        return $output . '</div>';
    }

    public function getItemType(): string
    {
        return $this->itemType;

        // return is_subclass_of($this->itemType, Collection::class)
        //     ? $this->itemType::getItemType() // Drill down to final type
        //     : $this->itemType;
    }

    public function isNestedCollection(): bool
    {
        return is_subclass_of($this->itemType, Collection::class);
    }
}

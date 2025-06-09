<?php

namespace App\Services;

use App\CMS\BaseComponent;
use App\CMS\Field;
use App\CMS\Collection;
use App\CMS\Component;
use App\Attrs\DBAttr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;

class SchemaGenerator
{
    public function sync(BaseComponent $entity): void
    {
        $this->processComponent($entity);
    }

    protected function processComponent(BaseComponent $component, ?BaseComponent $parent = null): void
    {
        if ($component instanceof Collection) {
            $this->handleCollection($component, $parent);
            return;
        }

        if ($this->hasDatabaseFields($component)) {
            $tableName = $this->getTableName($component, $parent);

            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, fn($table) => $this->buildTable($table, $component, $parent));
            } else {
                Schema::table($tableName, fn($table) => $this->updateTable($table, $component));
            }
        }

        foreach ($component->getChildren() as $child) {
            if ($child instanceof Collection || $child instanceof Component) {
                // $this->processComponent(new ($child->getItemType()), $component);
                $this->processComponent($child, $component);
            }
        }
    }

    protected function hasDatabaseFields(BaseComponent $component): bool
    {
        foreach ($component->getChildren() as $child) {
            if ($child instanceof Field && $child->getDBAttribute()) {
                return true;
            }
        }
        return false;
    }

    protected function buildTable($table, BaseComponent $component, ?BaseComponent $parent): void
    {
        $table->id();

        if ($parent) {
            $table->foreignId($this->getForeignKeyName($parent))
                ->constrained($this->getTableName($parent))
                ->cascadeOnDelete();
        }

        $this->addColumns($table, $component);
        $table->timestamps();
    }

    protected function updateTable(Blueprint $table, BaseComponent $component): void
    {
        $this->addColumns($table, $component, true);
    }

    protected function addColumns(Blueprint $table, BaseComponent $component, bool $onlyNew = false): void
    {
        foreach ($component->getChildren() as $name => $child) {
            if ($child instanceof Field && $dbAttr = $child->getDBAttribute()) {
                if (!$onlyNew || !Schema::hasColumn($table->getTable(), $name)) {
                    $this->addColumn($table, $name, $dbAttr);
                }
            }
        }
    }

    protected function addColumn(Blueprint $table, string $name, DBAttr $attr): void
    {
        $column = $table->{$attr->type}($name, $attr->length);

        if ($attr->nullable) {
            $column->nullable();
        }

        if ($attr->default !== null) {
            $column->default($attr->default);
        }

        if ($attr->index) {
            $column->index();
        }

        // if ($attr->foreign) {
        //     $column->references('id')->on($attr->on ?? Str::plural($attr->foreign));
        // }
    }

    protected function getTableName(BaseComponent $component, ?BaseComponent $parent = null): string
    {
        if (!$parent) {
            return Str::snake(Str::pluralStudly(class_basename($component)));
        }

        return Str::replace('.', '_', $component->getPath());
    }

    protected function getForeignKeyName(BaseComponent $parent): string
    {
        return Str::snake(class_basename($parent)) . '_id';
    }

    protected function handleCollection(Collection $collection, ?BaseComponent $parent): void
    {
        if ($collection->isNestedCollection()) {
            // $itemType = new ($collection->getItemType());
            // $itemType->setParent($collection, 'items');

            $itemType = new ($collection->getItemType());
            $this->processComponent($itemType, $parent);
            // $this->createPivotTable($parent, $collection);
        } else {
            $itemType = new ($collection->getItemType());
            $itemType->setParent($collection, 'items');
            $this->processComponent($itemType, $parent);
        }
    }

    protected function createPivotTable(?BaseComponent $parent, Collection $collection)
    {

    }

    // protected function createPivotTable(?BaseComponent $parent, Collection $collection): void
    // {
    //     $tableName = $this->getPivotTableName($parent, $collection);

    //     if (!Schema::hasTable($tableName)) {
    //         Schema::create($tableName, function ($table) use ($parent, $collection) {
    //             $table->id();

    //             // Parent reference
    //             if ($parent) {
    //                 $table->foreignId($this->getForeignKeyName($parent))
    //                     ->constrained($this->getTableName($parent))
    //                     ->cascadeOnDelete();
    //             }

    //             // Item reference
    //             $itemType = $collection->getItemType();
    //             $table->foreignId($this->getItemForeignKeyName($itemType))
    //                 ->constrained($this->getTableName(new $itemType))
    //                 ->cascadeOnDelete();

    //             $table->timestamps();

    //             // Composite unique index
    //             // $columns = array_filter([
    //             //     $parent ? $this->getForeignKeyName($parent) : null,
    //             //     $this->getItemForeignKeyName($itemType)
    //             // ]);
    //             // $table->unique($columns);
    //         });
    //     }
    // }

    // protected function getItemForeignKeyName(string|object $itemType): string
    // {
    //     $className = is_object($itemType) ? get_class($itemType) : $itemType;
    //     return Str::singular(Str::snake(class_basename($className))) . '_id';
    // }

    // protected function getPivotTableName(?BaseComponent $parent, Collection $collection): string
    // {
    //     $parentName = $parent ? Str::singular(Str::snake(class_basename($parent))) : '';
    //     $itemName = Str::singular(Str::snake(class_basename($collection->getItemType())));

    //     return $parentName ? "{$parentName}_{$itemName}_pivot" : "{$itemName}_pivot";
    // }
}

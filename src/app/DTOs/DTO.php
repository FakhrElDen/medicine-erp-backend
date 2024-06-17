<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

abstract class DTO implements Arrayable
{
    /**
     * checks if a property has been set or not
     */
    public function has($property)
    {
        return !is_null($this->{$property});
    }

    /**
     * return a copy of the instance without one or more properties
     */
    public function except(string|array $property): static
    {
        $clone = clone $this;

        foreach (Arr::wrap($property) as $property_name) {
            $clone->{$property_name} = null;
        }

        return $clone;
    }

    /**
     * return a copy of the instance with only the specified properties
     */
    public function only(array $property_names): static
    {
        $clone = clone $this;

        $this->getPublicProperties()
            ->filter(fn (\ReflectionProperty $property) => !in_array($property->name, $property_names) && !$property->isStatic())
            ->each(function (\ReflectionProperty $property) use (&$clone) {
                $clone->{$property->name} = null;
            });

        return $clone;
    }

    public function toArray()
    {
        $array = [];

        $properties = $this->getPublicProperties();

        foreach ($properties as $property) {
            $array[$property->name] = $this->{$property->name};
        }

        return $array;
    }

    protected function getPublicProperties()
    {
        $reflection_class = new \ReflectionClass($this);
        $public_properties = $reflection_class->getProperties(\ReflectionProperty::IS_PUBLIC);

        return collect($public_properties)
            ->filter(fn (\ReflectionProperty $p) => !$p->isStatic())
            ->values();
    }

    public function whereNotNull(): array
    {
        $properties = get_object_vars($this);
        return array_filter($properties, function ($value) {
            return $value !== null;
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

trait Mapper
{
    private function hasFillableAttributes()
    {
        return property_exists($this, 'fillable')
            && is_array($this->fillable)
            && count($this->fillable) > 0;
    }

    private function getFillableAttributes(array $array): array
    {
        return array_filter($array, function ($value, $attribute) {
            return in_array($attribute, $this->fillable);
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function getSetterMethodFromAttribute($attribute)
    {
        return 'with'.ucfirst($attribute);
    }

    private function getGetterMethodFromAttribute($attribute)
    {
        return 'get'.ucfirst($attribute);
    }

    private function fill(array $fillable)
    {
        array_map(function ($attribute, $value) {
            $setter = $this->getSetterMethodFromAttribute($attribute);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }, array_keys($fillable), $fillable) ;
    }

    public function fromArray(array $array)
    {
        if (!$this->hasFillableAttributes()) {
            return false;
        }

        $fillable = $this->getFillableAttributes($array);
        $this->fill($fillable);
    }

    public function toArray(): array
    {
        $response = [];

        if (!$this->hasFillableAttributes()) {
            return $response;
        }

        foreach ($this->fillable as $fillable) {
            $getter = $this->getGetterMethodFromAttribute($fillable);
            $response[$fillable] = $this->$getter();
        }

        return $response;
    }
}

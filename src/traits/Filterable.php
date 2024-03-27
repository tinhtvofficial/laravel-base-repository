<?php

namespace Luuka\Traits;

use Illuminate\Support\Str;

trait Filterable
{
    public function scopeFilter($query, $params = [])
    {
        foreach ($params as $column => $value) {
            if (!isset($value)) continue;

            $method = 'filter' . Str::studly($column);

            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }

            if (isset($this->filterable) && is_array($this->filterable)) {
                if (in_array($column, $this->filterable)) {
                    $query->where($column, $value);
                }

                if (key_exists($column, $this->filterable)) {
                    $query->where($this->filterable[$column], $value);
                }
            }
        }

        return $query;
    }
}

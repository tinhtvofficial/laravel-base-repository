<?php

namespace Luuka\Traits;

use Illuminate\Support\Str;

trait Sortable 
{
    public function scopeSort($query, $orders)
    {
        foreach ($orders as $column => $value) {
            $method = 'sort' . Str::studly($column);

            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }

            if (isset($this->sortable) && is_array($this->sortable) && count($this->sortable))
            {
                if (in_array($column, $this->sortable)) {
                    $query->orderBy($column, $value);
                }

                if (key_exists($column, $this->sortable)) {
                    $query->orderBy($this->sortable[$column], $value);
                }
            }
        }

        return $query;
    }
}
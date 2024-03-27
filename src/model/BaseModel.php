<?php

namespace Luuka\Model;

use Luuka\Traits\Filterable;
use Luuka\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;
    use Filterable, Sortable;
}

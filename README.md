<!-- [![Latest Stable Version](https://poser.pugx.org/jason-guru/laravel-make-repository/version)](https://packagist.org/packages/jason-guru/laravel-make-repository)
[![Total Downloads](https://poser.pugx.org/jason-guru/laravel-make-repository/downloads)](https://packagist.org/packages/jason-guru/laravel-make-repository)
[![Latest Unstable Version](https://poser.pugx.org/jason-guru/laravel-make-repository/v/unstable)](//packagist.org/packages/jason-guru/laravel-make-repository)
[![License](https://poser.pugx.org/jason-guru/laravel-make-repository/license)](https://packagist.org/packages/jason-guru/laravel-make-repository) -->
# Laravel PHP Artisan Make:Repository
A simple package for addding `php artisan make:repository` command to Laravel 5 and above

## Installation
Require the package with composer using the following command:

`composer require luuka/laravel-base-repository --dev`

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require-dev": {
          "luuka/laravel-base-repository": "*"
}
```
## Usage
`php artisan make:repository your-repository-name`

Example:
```
php artisan make:repository UserRepository
```
or
```
php artisan make:repository Backend/UserRepository
```

The above will create a repositories directory inside the app directory.

Once the repository is generated add your model class and return it in the model function,

Example:

```
<?php

namespace Luuka;

use Luuka\LaravelBaseRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        //return YourModel::class
    }
}

```

After create new repository class extends BaseRepository, you need extends BaseModel in your model class have been binded on model() function. BaseModel look like:

```
<?php

namespace Luuka\LaravelBaseModel\Model;

use Luuka\Traits\Filterable;
use Luuka\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;
    use Filterable, Sortable;
}
```

BaseModel use two scope function from traits: scopeFilter & scopeSort. May be you need to use it in the next time.
On every new model created. You extend BaseModel:

```

<?php

namespace App\Models;

use Luuka\LaravelBaseModel\Model\BaseModel;

class YourModel extends BaseModel
{
   // code
}


```
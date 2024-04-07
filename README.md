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
	// Filter with variable
	protected $filterable = ['name'];
	
	// Sort with variable;
	protected $sortable = ['name' => 'desc'];

    // Custom filter
    public function filterSearch($query, $value)
    {
        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
	
	// Custom sorts
    public function sortName($query)
    {
        return $query->orderBy('name', 'desc');
    }
}


```

```

<form action="your_url" method="POST/GET">
	
	// Filter by name with name prefix
	<input type="text" name="search" placeholder="search by name">
	
	<button>Submit</button>
	
</form>

```

## BASE USAGE

```
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->getAll($request->all());

        return view('backend.pages.settings.index', compact('users'));
    }
}

```
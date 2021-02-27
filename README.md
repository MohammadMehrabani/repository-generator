# Laravel - Repository Generator

![version](https://img.shields.io/badge/version-1.0.0-blue.svg) ![license](https://img.shields.io/badge/license-MIT-green.svg)
## About
Repository Generator is a Laravel package that aims to generate repository and interface files and binding interfaces to implementations automatically for repository pattern. It gives you developing speed by automated operations. You can use this package for both ongoing and new projects.

- Highly customizable with simple config
- Overriding option
- Easy to improve

## Installation
You can install the package via Composer:
``` bash
composer require mohammadmehrabani/repository-generator:dev-master
```

Next, you must install the service provider to `config/app.php`:
```php
'providers' => [
    // for laravel 5.4 and below
    MohammadMehrabani\RepositoryGenerator\RepositoryGeneratorServiceProvider::class,
];
```

Then, if you want to customize folder names, namespaces, etc... You need to publish config with command:
``` bash
php artisan vendor:publish --provider="MohammadMehrabani\RepositoryGenerator\RepositoryGeneratorServiceProvider" --tag="config"
```

Now you can edit `config/repository-generator.php`

## Usage
Before using `generate` commdand you should customize `config/repository-generator.php` for your own use.
You can simply use `repository:generate` command by terminal:
``` bash
php artisan repository:generate
```
Next, you must install the service provider to `config/app.php`:
```php
'providers' => [
    // You can change service_provider_class from config/repository-generator.php
    App\Providers\RepositoryServiceProvider::class,

],
```


### Repository file provided by RepositoryGenerator (optional use)

This package contains Repository.php which has similar functions to Eloquent. You can basically do something like below when you extend class from `\MohammadMehrabani\RepositoryGenerator\Repository`

This is completely personal and optional. I just created/copied some functions from Eloquent to add similar functionalities directly to repository file. So I can use same methods in my controller If I extend or implement this repository/interface for other database source like mongodb.

``` php
<?php

// You can change Directories and Namespaces from config/repository-generator.php
use App\Repositories\Interfaces\UserRepositoryInterface;

$repository = resolve(UserRepositoryInterface::class);
$user      = $repository->select('id', 'name')
                         ->where('name', 'LIKE', '%Mohammad%')
                         ->first(); // or ->get();
```

Built-in active() scope
``` php
<?php

use App\Repositories\Interfaces\UserRepositoryInterface;

$repository = resolve(UserRepositoryInterface::class);
$users      = $repository->active()
                         ->get();
            
// You can change active column name from config/repository-generator.php
```

**Available Methods** <br>
All listed methods have same usage as Eloquent

| Method        | Usage                                                     
| ------------- | ----------------------------------------------------------
| **select**    | $repo->select('column1,'column2')                         
|               | $repo->select(['column1, 'column2'])                      
| **active**    | $repo->active()->get();                                   
|               | active() is equal to $repo->where('active_column', 1);   
| **where**     | $repo->where('column', 'value')->first();
|               | $repo->where('column', '>',  10)->first();
| **whereIn**   | $repo->whereIn('column', ['value1', 'value2'])->get();
| **orWhere**   | $repo->orWhere('column', 'value')->first();
| **with**      | $repo->with('relation')->get();
| **count**     | $repo->where('column', 'value')->count();
| **find**      | $repo->find($id);
| **first**     | $repo->first();
|               | $repo->first(['column1', 'column2']);
| **value**     | $repo->where('id', $id)->value('name');
| **orderBy**   | $repo->orderBy('column')->get(); // default 'asc'
|               | $repo->orderBy('column', 'desc')->get();
| **get**       | $repo->get();
| **paginate**  | $repo->paginate(20);
| **create**    | $repo->create(['column1' => 'value', 'column2' => 'value']);
| **update**    | $repo->update($id, ['column1' => 'value', 'column2' => 'value']);
| **delete**    | $repo->where('column', 'value')->delete();
|               | $repo->delete($id);
| **destroy**   | $repo->destroy($id);


## Contributing
 
Thank you for considering contributing to the Repository Generator! The contribution guide can be found in the CONTRIBUTING.md

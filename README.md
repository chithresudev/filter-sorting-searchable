# laravel Devchithu-Filter-Sorting-Searchable

[![Latest Stable Version](http://poser.pugx.org/devchithu/filter-sorting-searchable/v)](https://packagist.org/packages/devchithu/filter-sorting-searchable) [![Total Downloads](http://poser.pugx.org/devchithu/filter-sorting-searchable/downloads)](https://packagist.org/packages/devchithu/filter-sorting-searchable) [![Latest Unstable Version](http://poser.pugx.org/devchithu/filter-sorting-searchable/v/unstable)](https://packagist.org/packages/devchithu/filter-sorting-searchable) [![License](http://poser.pugx.org/devchithu/filter-sorting-searchable/license)](https://packagist.org/packages/devchithu/filter-sorting-searchable) [![PHP Version Require](http://poser.pugx.org/devchithu/filter-sorting-searchable/require/php)](https://packagist.org/packages/devchithu/filter-sorting-searchable)


This Package bootstrap popover for handling dynamic column sorting, filter and searchable in Laravel.


![Screenshot](public/filter_sort_searchable.gif)

## Installation & Usages

### Basic Setup

Install via composer; in console: 
```
composer require devchithu/filter-sorting-searchable 
``` 
or require in *composer.json*:
```json
{
    "require": {
        "devchithu/filter-sorting-searchable": "^1.1.2"
    }
}
```
then run `composer update` in your terminal to pull it in.

Once this has finished, you will need to add the service provider to the providers array in your app.php config as follows:

path : project/config/app.php

Find ' providers => [] ' add inside below code (Custom Service Providers...)

```php
Devchithu\FilterSortingSearchable\Providers\FilterSortingSearchableProvider::class,
```
Example like code :  project/config/app.php

```php
'providers' => [

    App\Providers\RouteServiceProvider::class,

    /*
     * Third Party Service Providers...
     */
    Devchithu\FilterSortingSearchable\Providers\FilterSortingSearchableProvider::class,
],
```
### Usages

Use **FilterSortingSearchable** trait inside your *Eloquent* model(s).

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...
}
```

### Bootstrap 5 version

CSS File
```code
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
```
JS File

```code
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

```

## Publish Js file

then run publish cmd you must to publish only js file and where-ever your want 'filter, sort, searchable' using the below script in blade.php file

```
php artisan vendor:publish --tag=filter-sorting-searchable
```
See public/filter-sorting-searchable.js (if you want any change update this code inside)

# Method of design filter sorting extension
#### 1. Bootstrap Popover filter sorting Blade Extension

##  1. Bootstrap Popover filter sorting Blade Extension
Must push that js file in blade, where-ever your want like (better than push that js file into main index blade.php):

```
    <script src="{{ asset('filter-sorting-searchable.js') }}"></script>
```
OR,

```
@push('scripts')
    <script src="{{ asset('filter-sorting-searchable.js') }}"></script>
@endpush
```

## * Sorting

There is a blade extension for you to use **@filterSort()**

```blade
@filterSort(['sorting' => true, 'field_name' => 'name'])
```

**Custom field name sorting**
```blade
@filterSort(['sorting' => true,'field_name' => 'name', 'label_name' => 'Name'])
```

**Custom label(Ascending and descending) name change**
```blade
@filterSort(['sorting' => true, 'field_name' => 'name', 'label_name' => 'Name', 'sorting_custom_label' => ['low to high', 'high to low']])
```


**Here**,
1. `sorting` parameter default is false. `true` is sorting enabled, if don't need sorting just put `false` or just remove sorting field.
2. `field_name` parameter is column in database table field, **name**.
3. `label_name` parameter is displayed inside anchor tags and print valueable field name. incase of  `label_name` doe'st use automatically get column in database table field.
4. `sorting_custom_label` parameter change default name like : 'Sort Ascending' into 'low to high' , 'Sort Descending' into 'high to low'

**what are field sorting declare the using your *Eloquent* model(s) inside function like below code**,

Use **FilterSortSearchable** trait inside your *Eloquent* model(s).

### *Eloquent* model 

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...

    /**
     * The table sorting order asc and desc.
     *
     * @var string
     */

    public $sorting = [
        'id',
        'name',
        'email',
        'created_at',
    ];


}
```

### Controller's `index()` method

```php
public function index(Request $request)
{
    $users = User::sorting()->get();

    return view('user.index', ['users' => $users]);
}
```

## * Inline Filter

***Blade table config***

There is a sorting similar same blade extension for you to use **@filterSort()**

```blade
 @filterSort(['filter' => true, 'type' => 'text', 'field_name' => 'instance_type'])
```

**Custom field name change filter**

```blade
@filterSort(['filter' => true, 'type' => 'text',  'field_name' => 'name', 'label_name' => 'Name'])
```


**Custom design filter**
Here, Custom design when anything you want design put the code like below,

```blade
@filterSort(['filter' => true, 'type' => 'text',  'field_name' => 'name', 'label_name' => 'Name', 'custom_design' => '

<div class="text-center"><input type="text"/></div>

'])
```

**Custom design only filter**
Here, Type is not mentioned, if you want new design apply Custom design when anything you want design put the code like below,

```blade
@filterSort(['filter' => true, 'field_name' => 'name', 'label_name' => 'Name', 'custom_design' => '

<div class="text-center"><input type="text"/></div>

'])
```

**what are field filterable declare the using your *Eloquent* model(s) inside function like below code**,

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...

    /**
     * The table filter order asc and desc.
     *
     * @var string
     */

     public $filterable = [
        'id',
        'name',
        'email'
    ];

}
```

### Controller's `index()` method

```php
public function index(Request $request)
{
    $users = User::filterable()->get();

    return view('user.index', ['users' => $users]);
}
```

## Sorting & Filter

Incase, If you want sort and filter sametime  using below Code, 

```blade
 @filterSort(['sotring' => true, 'filter' => true, 'type' => 'text', 'field_name' => 'status_type', 'label_name' => 'Status Type '])
```

**Here**,
1. `filter` parameter default is false. `true` is filter enabled, if don't need filter just put `false` or just remove filter parames.
2. `field_name` parameter is column in database table field, **name**.
3. `label_name` parameter is displayed inside anchor tags and print valueable field name. incase of  `label_name` doe'st use automatically get column in database table field.

**UI - filter input field automatically generate**

1. filter is `true` create input box type = 'text', if you want different input type like (selelect, radio, range) below code put the array params
   `'type' => 'text' // 'type' => 'select' or  radio, range`

Here, if you `select ` option using  multiple option value data like `'multiple_option' => ['All', 'active', 'in_active']

```blade
 @filterSort(['sorting' => true, 'filter' => true, 'type' => 'select', 'field_name' => 'status', 'label_name' => 'Status', 'multiple_option' => ['All', 'active', 'in_active']])
                               
```

**what are field sorting and filter declare the using your *Eloquent* model(s) inside function like below code**,

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...

    /**
     * The table sorting order asc and desc.
     *
     * @var string
     */

    public $sorting = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    /**
     * The table filter.
     *
     * @var string
     */

     public $filterable = [
        'id',
        'name',
        'email'
    ];

}
```

### Controller's `index()` method

```php
public function index(Request $request)
{
    $users = User::sorting()->filterable()->get();

    return view('user.index', ['users' => $users]);
}
```

### Controller's `index()` method with paginate()

```php
public function index(Request $request)
{
    $users = User::sorting()->filterable()->paginate(20);

    return view('user.index', ['users' => $users]);
}
```

## *Searchable
This searchable global area find the table data

**what are field searchable declare the using your *Eloquent* model(s) inside function like below code**,

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...

    /**
     * The table searchable.
     *
     * @var string
     */

     public $searchable = [
        'id',
        'name',
        'email'
    ];

}
```

There is a blade extension for you to use **@searchable()**

```blade
@searchable()
```
### Controller's `index()` method

```php
public function index(Request $request)
{
    $users = User::searchable()->get();

    return view('user.index', ['users' => $users]);
}
```

###  Controller's `index()` method
If you want filter, sorting, searchable declare the scope function

```php
public function index(Request $request)
{
    $users = User::sorting()->filterable()->searchable()->get();

    return view('user.index', ['users' => $users]);
}

```


# *Manual customized filter 
If you want filter some field customazed used here file

```
php artisan vendor:publish --tag=customFilterTrait
```
See app\CustomFilter\CustomFilterTrait.php (if you want any change update this code inside)

**what are the customized filter field don't declare  (filterable) array. declare custom filterable the using your *Eloquent* model(s) inside function**,

```php
use Devchithu\FilterSortingSearchable\Traits\FilterSortingSearchable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FilterSortingSearchable;
    ...
    ...


    /**
     * The table filter.
     *
     * @var string
     */

     public $customfilterable = [
            'name',
            'status',
    ];

}
```


## * Binding Params
What are field sorting, searching and filterting below code
Which place to you want binding parameters declare the **@bindingParams()**

```blade
@bindingParams()
```

## * Binding Params apply custom style class
What are field sorting, filterting below code
Which desgin you want apply the class name like : 

```blade
@bindingParams(['sorting_style_class' => 'custom-sorting', 'filter_style_class' => 'custom-filter'])
```

Run finally,
```
php artisan op:cl
```

Thank you .




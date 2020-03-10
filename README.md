Blog for T2 CMS
=======
Nested category, post for multilanguage, multidomain site

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist startpl/t2cms-blog "*"
```

or add

```
"startpl/t2cms-blog": "*"
```

to the require section of your `composer.json` file.

and add the module to backend config:
```php
'modules' => [
    //...
    'blog' => [
        'class' => 'startpl\t2cmsblog\backend\Module',
    ],
    //...
],
```
Also you need add the module to frontend config:
```php
'modules' => [
    //...
    'blog' => [
        'class' => 'startpl\t2cmsblog\frontend\Module',
    ],
    //...
],
'urlManager' => [
    'rules' => [
        [
            'class' => 'startpl\t2cmsblog\components\CategoryUrlRule',
            //'prefix' => 'blog'
        ],
        [
            'class' => 'startpl\t2cmsblog\components\PageUrlRule',
            //'prefix' => 'blog'
        ],
    ],
],
```
Then you should start the migration (console):
```php
php yii migrate --migrationPath=@vendor/startpl/t2cms-blog/migrations
```

Usage
-----

Go to backend /blog/categories or /blog/pages

Also you can clone this repository for create your extension (for example, a store, etc.)
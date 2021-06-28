# Pollen View Component

[![Latest Version](https://img.shields.io/badge/release-1.0.0-blue?style=for-the-badge)](https://www.presstify.com/pollen-solutions/view/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen **View** Component is a template engine system.

This is an expandable display template engine that natively integrates **Plates** and **Twig** library.

## Installation

```bash
composer require pollen-solutions/view
```

## Fundamentals

### About Plates

**Plates** is a native PHP template system that’s fast, easy to use.
It’s inspired by the [Twig](#about-twig) template engine.
Plates is designed for developers who prefer to use native PHP templates over compiled template languages.

Plates is use as default engine in the Pollen Solutions Components Suite.

More informations :
- [Plates official documentation](https://platesphp.com/)

### About Twig

**Twig** is the templating engine that included with [Symfony Framework](https://symfony.com/).
**Twig** is a modern template engine for PHP.
**Twig** compiles templates down to plain optimized PHP code.

**Twig** is natively included with Pollen View component.

More informations :
- [Twig official documentation](https://twig.symfony.com/doc/)

### Third-party Engine

#### Blade

**Blade** is the templating engine that included with [Laravel Framework](https://laravel.com/).

**Blade** is not natively included with the Pollen View component, but can easily be added :

```bash
composer require pollen-solutions/view-blade
```

More informations :
- [Blade official documentation](https://laravel.com/docs/8.x/blade)

#### Mustache

Mustache PHP engine is currently in project and coming soon.

More informations :
- [Mustache official documentation](https://github.com/bobthecow/mustache.php/wiki)

## Fundamentals

### An unified interface

To respond to the particularity of each of the template display engines, Pollen View benefits from a unified interface.

### Directory and override Directory

Pollen **View** purposes a different logic from the libraries it inherits.

Each template file included in the template directory can be replaced by a template file with the same name in the 
override directory.

### Extending the template engine

Pollen **View** also makes it possible to extend the functionalities of the template display engines through an 
easy interface.

### Caching 

To facilitate the work of application development, Pollen View allows you to disable the cache of the display template 
engines that it implements.

It is strongly recommended that you enable the cache when deploying to production.

## Using global View

```php
use Pollen\View\ViewManager;

$viewManager = new ViewManager();

#

## Set view directory
$directory = "/var/www/html/views";

```

## Creating a new View

### Standard Method

```php
use Pollen\View\ViewManager;

$viewManager = new ViewManager();

## For Plates
$view = $viewManager->createView('plates');

## For Twig
$view = $viewManager->createView('twig');

```

### Callback Engine Configuration Method

```php
use Pollen\View\ViewManager;
use Pollen\View\Engines\Plates\PlatesViewEngine;

$viewManager = new ViewManager();

$directory = "/var/www/html/views";
$overrideDir = "/var/www/html/public/";

$view = $viewManager->createView(
    'plates',
    function (PlatesViewEngine $platesViewEngine) use ($directory, $overrideDir) {
        $platesViewEngine
            ->setDirectory($directory)
            ->setOverrideDir($overrideDir);

        return $platesViewEngine;
    }
);
```

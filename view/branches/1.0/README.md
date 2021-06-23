# Pollen View Component

[![Latest Version](https://img.shields.io/badge/release-1.0.0-blue?style=for-the-badge)](https://www.presstify.com/pollen-solutions/view/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.3-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen **View** Component is a Template engine system.

## Installation

```bash
composer require pollen-solutions/view
```

## Creating View

### Standard Method

```php

```

### Callback Method

```php
use Pollen\View\ViewManager;
use Pollen\View\Engines\Plates\PlatesViewEngine;

$viewManager = new ViewManager();

$view = $viewManager->createView(
    'plates',
    [],
    function (PlatesViewEngine $platesViewEngine) use ($directory, $overrideDir) {
        $platesViewEngine
            ->setDirectory($directory);

        if ($overrideDir !== null) {
            $platesViewEngine->setOverrideDir($overrideDir);
        }

        $mixins = [
            'after',
            'attrs',
            'before',
            'content',
            'getAlias',
            'getId',
            'getIndex',
        ];

        foreach ($mixins as $mixin) {
            $platesViewEngine->setDelegateMixin($mixin);
        }

        return $platesViewEngine;
    }
);
```
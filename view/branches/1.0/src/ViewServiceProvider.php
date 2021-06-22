<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Container\BootableServiceProvider;

class ViewServiceProvider extends BootableServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $provides = [
        ViewInterface::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->add(ViewInterface::class, function () {
            $viewEngine = new View();
            $viewEngine->setContainer($this->getContainer());

            return $viewEngine;
        });
    }
}

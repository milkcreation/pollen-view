<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Container\BaseServiceProvider;

class ViewServiceProvider extends BaseServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $provides = [
        ViewEngineInterface::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->add(ViewEngineInterface::class, function () {
            $viewEngine = new ViewEngine();
            $viewEngine->setContainer($this->getContainer());

            return $viewEngine;
        });
    }
}

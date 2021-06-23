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
        ViewInterface::class,
        ViewManagerInterface::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(ViewManagerInterface::class, function () {
            return new ViewManager([], $this->getContainer());
        });

        $this->getContainer()->share(ViewInterface::class, function () {
            /** @var ViewManagerInterface $viewManager */
            $viewManager = $this->getContainer()->get(ViewManagerInterface::class);

            return $viewManager->getDefaultView();
        });
    }
}

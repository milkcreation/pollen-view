<?php

declare(strict_types=1);

namespace Pollen\View;

use Closure;
use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;

interface ViewManagerInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ContainerProxyInterface,
    ViewInterface
{
    /**
     * Booting.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Create a new view.
     *
     * @param string|ViewEngineInterface|null $viewEngineDef
     * @param array $args
     * @param Closure|null $engineCallback
     *
     * @return ViewInterface
     */
    public function createView($viewEngineDef = null, array $args = [], ?Closure $engineCallback = null): ViewInterface;

    /**
     * Get default view.
     *
     * @return ViewInterface
     */
    public function getDefaultView(): ViewInterface;

    /**
     * View Engine registration.
     *
     * @param string $name
     * @param string $classname
     * @param bool $asDefault
     *
     * @return static
     */
    public function registerViewEngine(string $name, string $classname, bool $asDefault = false): ViewManagerInterface;
}

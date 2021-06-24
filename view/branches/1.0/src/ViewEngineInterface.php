<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Support\Proxy\ContainerProxyInterface;

interface ViewEngineInterface extends ContainerProxyInterface
{
    /**
     * Add a template function.
     *
     * @param string $name
     * @param callable $function
     *
     * @return static
     */
    public function addFunction(string $name, callable $function): ViewEngineInterface;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * Return a view template.
     *
     * @param string $name
     * @param array $datas
     *
     * @return string
     */
    public function render(string $name, array $datas = []): string;

    /**
     * Set template cache directory.
     *
     * @param string|null $cacheDir Absolute path of templates cache directory.
     *
     * @return static
     */
    public function setCacheDir(?string $cacheDir = null): ViewEngineInterface;

    /**
     * Set template directory.
     *
     * @param string $directory Absolute path of templates directory.
     *
     * @return static
     */
    public function setDirectory(string $directory): ViewEngineInterface;

    /**
     * Set template override directory.
     *
     * @param string $overrideDir Absolute path of override templates directory.
     *
     * @return static
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface;

    /**
     * Add a piece of shared data to all templates.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return static
     */
    public function share($key, $value = null): ViewEngineInterface;
}
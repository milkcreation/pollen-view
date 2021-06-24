<?php

declare(strict_types=1);

namespace Pollen\View;

interface ViewInterface
{
    /**
     * Add a template function.
     *
     * @param string $name
     * @param callable $function
     *
     * @return ViewInterface
     */
    public function addFunction(string $name, callable $function): ViewInterface;

    /**
     * Get instance of view engine.
     *
     * @return ViewEngineInterface
     */
    public function getEngine(): ViewEngineInterface;

    /**
     * Return a view template.
     *
     * @param string $name
     * @param array $datas
     *
     * @return string
     */
    public function render(string $name, array $datas = []) : string;

    /**
     * Set template cache directory.
     *
     * @param string|null $cacheDir Absolute path of templates cache directory.
     *
     * @return static
     */
    public function setCacheDir(?string $cacheDir = null): ViewInterface;

    /**
     * Set template directory.
     *
     * @param string $directory Absolute path of templates directory.
     *
     * @return ViewInterface
     */
    public function setDirectory(string $directory): ViewInterface;

    /**
     * Set template override directory.
     *
     * @param string $overrideDir Absolute path of override templates directory.
     *
     * @return ViewInterface
     */
    public function setOverrideDir(string $overrideDir): ViewInterface;

    /**
     * Add a piece of shared data to all templates.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return static
     */
    public function share($key, $value = null): ViewInterface;
}

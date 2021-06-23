<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Support\Proxy\ContainerProxyInterface;

interface ViewEngineInterface extends ContainerProxyInterface
{
    /**
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
     * @param string $name
     * @param array $datas
     *
     * @return string
     */
    public function render(string $name, array $datas = []): string;

    /**
     * @param string $directory
     *
     * @return static
     */
    public function setDirectory(string $directory): ViewEngineInterface;

    /**
     * @param string $overrideDir
     *
     * @return static
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return static
     */
    public function share(string $key, $value): ViewEngineInterface;
}
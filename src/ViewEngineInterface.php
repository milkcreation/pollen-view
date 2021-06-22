<?php

declare(strict_types=1);

namespace Pollen\View;

interface ViewEngineInterface
{
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
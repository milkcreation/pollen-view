<?php

declare(strict_types=1);

namespace Pollen\View;

interface ViewInterface
{
    /**
     * @param string $name
     * @param callable $function
     *
     * @return ViewInterface
     */
    public function addFunction(string $name, callable $function): ViewInterface;

    /**
     * @return ViewEngineInterface
     */
    public function getEngine(): ViewEngineInterface;

    /**
     * @param string $name
     * @param array $datas
     *
     * @return string
     */
    public function render(string $name, array $datas = []) : string;

    /**
     * @param string $directory
     *
     * @return ViewInterface
     */
    public function setDirectory(string $directory): ViewInterface;

    /**
     * @param string $overrideDir
     *
     * @return ViewInterface
     */
    public function setOverrideDir(string $overrideDir): ViewInterface;
}

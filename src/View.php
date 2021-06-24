<?php

declare(strict_types=1);

namespace Pollen\View;

class View implements ViewInterface
{
    protected ViewEngineInterface $viewEngine;

    /**
     * @param ViewEngineInterface $viewEngine
     */
    public function __construct(ViewEngineInterface $viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }

    /**
     * @inheritDoc
     */
    public function addFunction(string $name, callable $function): ViewInterface
    {
        $this->getEngine()->addFunction($name, $function);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEngine(): ViewEngineInterface
    {
        return $this->viewEngine;
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $datas = []) : string
    {
        return $this->getEngine()->render($name, $datas);
    }

    /**
     * @inheritDoc
     */
    public function setCacheDir(?string $cacheDir = null): ViewInterface
    {
        $this->getEngine()->setCacheDir($cacheDir);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewInterface
    {
        $this->getEngine()->setDirectory($directory);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewInterface
    {
        $this->getEngine()->setOverrideDir($overrideDir);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): ViewInterface
    {
        $this->getEngine()->share($key, $value);

        return $this;
    }
}

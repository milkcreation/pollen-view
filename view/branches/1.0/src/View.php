<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\View\Engines\Plates\PlatesViewEngine;
use Pollen\View\Engines\Twig\TwigViewEngine;
use Pollen\View\Exception\UnableCreateEngineException;

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
     * @param callable $engineCallback
     *
     * @return static
     */
    public static function createFromPlates(callable $engineCallback): self
    {
        $plateEngine = $engineCallback(new PlatesViewEngine());

        if ($plateEngine instanceof PlatesViewEngine) {
            return new static($plateEngine);
        }

        throw new UnableCreateEngineException('Unable create View from PlatesViewEngine');
    }

    /**
     * @param callable $engineCallback
     *
     * @return static
     */
    public static function createFromTwig(callable $engineCallback): self
    {
        $twigEngine = $engineCallback(new TwigViewEngine());

        if ($twigEngine instanceof TwigViewEngine) {
            return new static($twigEngine);
        }

        throw new UnableCreateEngineException('Unable create View from TwigViewEngine');
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
        return $this->viewEngine->render($name, $datas);
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewInterface
    {
        $this->viewEngine->setDirectory($directory);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewInterface
    {
        $this->viewEngine->setOverrideDir($overrideDir);

        return $this;
    }
}

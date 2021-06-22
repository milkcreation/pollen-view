<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

use Pollen\Support\Proxy\ContainerProxy;
use Pollen\View\ViewEngineInterface;

/**
 * @mixin PlatesEngine
 */
class PlatesViewEngine implements ViewEngineInterface
{
    use ContainerProxy;

    /**
     * @var PlatesEngine
     */
    private PlatesEngine $platesEngine;

    public function __construct()
    {
        $this->platesEngine = new PlatesEngine(null, 'plates.php');
    }

    public function __call(string $method, array $parameters)
    {
        return $this->platesEngine->$method(...$parameters);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return $this->platesEngine->exists($name);
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $datas = []): string
    {
        return $this->platesEngine->render($name, $datas);
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewEngineInterface
    {
        $this->platesEngine->setDirectory($directory);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface
    {
        $this->platesEngine->addFolder('_override_dir', $overrideDir, true);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share(string $key, $value = null): ViewEngineInterface
    {
        $this->platesEngine->addData([$key => $value]);

        return $this;
    }
}

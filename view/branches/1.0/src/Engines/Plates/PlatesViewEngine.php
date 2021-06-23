<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

use Pollen\Support\Proxy\ContainerProxy;
use Pollen\View\AbstractViewEngine;
use Pollen\View\ViewEngineInterface;

/**
 * @mixin PlatesEngine
 */
class PlatesViewEngine extends AbstractViewEngine
{
    use ContainerProxy;

    /**
     * @var PlatesEngine
     */
    private PlatesEngine $platesEngine;

    /**
     * @param string $fileExtension
     */
    public function __construct(string $fileExtension = 'plates.php')
    {
        $this->platesEngine = new PlatesEngine(null, $fileExtension);
    }

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->platesEngine->$method(...$parameters);
    }

    /**
     * @inheritDoc
     */
    public function addFunction(string $name, callable $function): ViewEngineInterface
    {
        $this->platesEngine->registerFunction($name, $function);

        return $this;
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

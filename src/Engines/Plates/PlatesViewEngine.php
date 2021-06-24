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

    protected string $extension = 'plates.php';

    private ?PlatesEngine $platesEngine = null;

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->platesEngine()->$method(...$parameters);
    }

    /**
     * @inheritDoc
     */
    public function addFunction(string $name, callable $function): ViewEngineInterface
    {
        $this->platesEngine()->registerFunction($name, $function);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return $this->platesEngine()->exists($name);
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $datas = []): string
    {
        return $this->platesEngine()->render($name, $datas);
    }

    /**
     * @inheritDoc
     */
    public function setCacheDir(?string $cacheDir = null): ViewEngineInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewEngineInterface
    {
        $this->platesEngine()->setDirectory($directory);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface
    {
        $this->platesEngine()->addFolder('_override_dir', $overrideDir, true);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): ViewEngineInterface
    {
        $keys = is_array($key) ? $key : [$key => $value];

        $this->platesEngine()->addData($keys);

        return $this;
    }

    /**
     * Get|Instantiate Plates Engine.
     *
     * @return PlatesEngine
     */
    protected function platesEngine(): PlatesEngine
    {
        if ($this->platesEngine === null) {
            $this->platesEngine = new PlatesEngine(null, $this->options['extension'] ?? $this->extension);
        }

        return $this->platesEngine;
    }
}
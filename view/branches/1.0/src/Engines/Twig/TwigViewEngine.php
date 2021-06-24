<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Twig;

use Pollen\View\AbstractViewEngine;
use Pollen\View\Exception\MustHaveTemplateDirException;
use Twig\Environment as TwigEnvironment;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\View\ViewEngineInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigViewEngine extends AbstractViewEngine
{
    use ContainerProxy;

    protected string $extension = "html.twig";

    protected bool $pathsChanged = true;

    protected ?string $directory = null;

    protected ?string $overrideDir = null;

    private ?TwigEnvironment $twigEnvironment = null;

    /**
     * Call Twig Environment delegate method.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->twigEnvironment()->$method(...$parameters);
    }

    /**
     * @inheritDoc
     */
    public function addFunction(string $name, callable $function): ViewEngineInterface
    {
        $this->twigEnvironment()->addFunction(
            !$function instanceof TwigFunction ? new TwigFunction($name, $function) : $function
        );

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @todo
     */
    public function exists(string $name): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $datas = []): string
    {
        $this->twigPathsLoad();

        $name = "$name.$this->extension";

        try {
            return $this->twigEnvironment()->render($name, $datas);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function setCacheDir(?string $cacheDir = null): ViewEngineInterface
    {
        if ($cacheDir !== null) {
            $this->twigEnvironment()->setCache($cacheDir);
        } else {
            $this->twigEnvironment()->setCache(false);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewEngineInterface
    {
        $this->directory = $directory;
        $this->pathsChanged = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface
    {
        $this->overrideDir = $overrideDir;
        $this->pathsChanged = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): ViewEngineInterface
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach($keys as $k => $v) {
            $this->twigEnvironment()->addGlobal($k, $v);
        }

        return $this;
    }

    /**
     * Get|Instantiate Twig Environment.
     *
     * @return TwigEnvironment
     */
    protected function twigEnvironment(): TwigEnvironment
    {
        if ($this->twigEnvironment === null) {
            $this->twigEnvironment = new TwigEnvironment(new ChainLoader(), $this->options);
        }
        return $this->twigEnvironment;
    }

    /**
     * Load Twig Paths.
     *
     * @return void
     */
    protected function twigPathsLoad(): void
    {
        if ($this->pathsChanged) {
            if ($this->directory === null) {
                throw new MustHaveTemplateDirException(self::class);
            }

            $loaders = [];
            if ($this->overrideDir) {
                $loaders[] = new FilesystemLoader($this->overrideDir);
            }

            $loaders[] = new FilesystemLoader($this->directory);

            $this->twigEnvironment()->setLoader(new ChainLoader($loaders));
            $this->pathsChanged = false;
        }
    }
}

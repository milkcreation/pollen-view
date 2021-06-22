<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Twig;

use Twig\Environment as TwigEnvironment;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\View\ViewEngineInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

class TwigViewEngine implements ViewEngineInterface
{
    use ContainerProxy;

    protected bool $loaderChanged = true;

    protected ?ChainLoader $loader = null;

    protected ?FilesystemLoader $directoryLoader = null;

    protected ?FilesystemLoader $overrideLoader = null;

    /**
     * @var TwigEnvironment
     */
    private TwigEnvironment $twigEnvironment;

    public function __construct()
    {
        $this->loader = new ChainLoader();

        $this->twigEnvironment = new TwigEnvironment(new ChainLoader());
    }

    public function __call(string $method, array $parameters)
    {
        return $this->twigEnvironment->$method(...$parameters);
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
        if ($this->loaderChanged) {
            $this->setLoader();
        }

        $name = "$name.html.twig";

        try {
            return $this->twigEnvironment->render($name, $datas);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewEngineInterface
    {
        $this->directoryLoader = new FilesystemLoader($directory);
        $this->loaderChanged = true;

        return $this;
    }

    protected function setLoader(): void
    {
        if ($this->overrideLoader) {
            $loaders = [$this->overrideLoader, $this->directoryLoader];
        } else {
            $loaders = [$this->directoryLoader];
        }

        $this->twigEnvironment->setLoader(new ChainLoader($loaders));
        $this->loaderChanged = false;
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewEngineInterface
    {
        $this->overrideLoader = new FilesystemLoader($overrideDir);
        $this->loaderChanged = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share(string $key, $value = null): ViewEngineInterface
    {
        $this->twigEnvironment->addGlobal($key, $value);

        return $this;
    }
}

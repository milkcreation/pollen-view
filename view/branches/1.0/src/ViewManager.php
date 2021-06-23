<?php

declare(strict_types=1);

namespace Pollen\View;

use Closure;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Exception\ManagerRuntimeException;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\View\Engines\Plates\PlatesViewEngine;
use Pollen\View\Engines\Twig\TwigViewEngine;
use Pollen\View\Exception\UnableCreateEngineException;
use Pollen\View\Exception\UnableCreateViewException;
use Psr\Container\ContainerInterface as Container;
use Throwable;

class ViewManager implements ViewManagerInterface
{
    use BootableTrait;
    use ConfigBagAwareTrait;
    use ContainerProxy;

    private static ?ViewManagerInterface $instance = null;

    protected ?ViewInterface $defaultView = null;

    protected string $defaultViewEngineClass = PlatesViewEngine::class;

    /**
     * @var array|string[]
     */
    protected array $viewEngines = [
        'plates' => PlatesViewEngine::class,
        'twig'   => TwigViewEngine::class,
    ];

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if ($container !== null) {
            $this->setContainer($container);
        }

        $this->boot();

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * Retrieve main class instance.
     *
     * @return static
     */
    public static function getInstance(): ViewManagerInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new ManagerRuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->isBooted()) {
            $this->setBooted();
        }
    }

    /**
     * @param string|ViewEngineInterface|null $viewEngineDef
     * @param array $args
     * @param Closure|null $engineCallback
     *
     * @return ViewInterface
     */
    public function createView($viewEngineDef = null, array $args = [], ?Closure $engineCallback = null): ViewInterface
    {
        $args = array_values($args);

        if ($viewEngineDef === null) {
            try {
                $viewEngine = $this->resolveViewEngine(null, ...$args);
            } catch (Throwable $e) {
                throw new UnableCreateEngineException('', 0, $e);
            }

        } elseif ($viewEngineDef instanceof ViewEngineInterface) {
            $viewEngine = $viewEngineDef;
        } elseif (is_string($viewEngineDef)) {
            try {
                $viewEngine = $this->resolveViewEngine($viewEngineDef, ...$args);
            }  catch (Throwable $e) {
                throw new UnableCreateEngineException('', 0, $e);
            }
        } else {
            throw new UnableCreateEngineException();
        }

        if ($engineCallback !== null) {
            $viewEngine = $engineCallback($viewEngine);
        }

        try {
            return new View($viewEngine);
        } catch (Throwable $e) {
            throw new UnableCreateViewException();
        }
    }

    /**
     * @inheritDoc
     */
    public function getDefaultView(): ViewInterface
    {
        if ($this->defaultView === null) {
            $this->defaultView = new View($this->resolveViewEngine());

            $this->defaultView->setDirectory(getcwd());
        }

        return $this->defaultView;
    }

    /**
     * @param string $name
     * @param string $classname
     * @param bool $asDefault
     *
     * @return static
     */
    public function registerViewEngine(string $name, string $classname, bool $asDefault = false): ViewManagerInterface
    {
        $this->viewEngines[$name] = $classname;

        if ($asDefault) {
            $this->defaultViewEngineClass = $classname;
        }

        return $this;
    }

    /**
     * @param string|null $name
     * @param ...$args
     *
     * @return ViewEngineInterface
     */
    protected function resolveViewEngine(?string $name = null, ...$args): ViewEngineInterface
    {
        if ($name === null) {
            $viewEngineClass = $this->defaultViewEngineClass;
        } else {
            $viewEngineClass = $this->viewEngines[$name] ?? null;
        }

        if (!is_a($viewEngineClass, ViewEngineInterface::class, true)) {
            throw new UnableCreateEngineException();
        }

        /** @var ViewEngineInterface $viewEngine */
        $viewEngine = new $viewEngineClass(...$args);

        if ($container = $this->getContainer()) {
            $viewEngine->setContainer($container);
        }

        return $viewEngine;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // View

    /**
     * @inheritDoc
     */
    public function addFunction(string $name, callable $function): ViewInterface
    {
        $this->getDefaultView()->addFunction($name, $function);

        return $this->getDefaultView();
    }

    /**
     * @inheritDoc
     */
    public function getEngine(): ViewEngineInterface
    {
        return $this->getDefaultView()->getEngine();
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $datas = []): string
    {
        return $this->getDefaultView()->render($name, $datas);
    }

    /**
     * @inheritDoc
     */
    public function setDirectory(string $directory): ViewInterface
    {
        return $this->getDefaultView()->setDirectory($directory);
    }

    /**
     * @inheritDoc
     */
    public function setOverrideDir(string $overrideDir): ViewInterface
    {
        return $this->getDefaultView()->setOverrideDir($overrideDir);
    }
}

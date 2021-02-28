<?php

declare(strict_types=1);

namespace Pollen\View;

use BadMethodCallException;
use League\Plates\Engine as BaseViewEngine;
use Pollen\Support\Proxy\ContainerProxy;
use Throwable;
use RuntimeException;

class ViewEngine extends BaseViewEngine implements ViewEngineInterface
{
    use ContainerProxy;

    /**
     * Instance de la classe de délégation d'appel de méthodes.
     * @var object|null
     */
    protected $delegate;

    /**
     * Liste des méthodes de délégations permises.
     * @var array
     */
    protected $delegatedMixins = [];

    /**
     * Classe de chargement des gabarits d'affichage.
     * @return string
     */
    protected $loader = ViewLoader::class;

    /**
     * {@inheritDoc}
     *
     * @return ViewLoaderInterface
     */
    public function make($name): ViewLoaderInterface
    {
        $loader = new $this->loader($this, $name);

        if ($container = $this->getContainer()) {
            $loader->setContainer($container);
        }

        return $loader;
    }

    /**
     * @inheritDoc
     */
    public function callDelegate(string $method, array $args)
    {
        if ($this->delegate) {
            try {
                return $this->delegate->{$method}(...$args);
            } catch (Throwable $e) {
                throw new BadMethodCallException(
                    sprintf(
                        '[%s] throws an exception during the method call [%s] with message : %s',
                        get_class($this->delegate),
                        $method,
                        $e->getMessage()
                    )
                );
            }
        }
        throw new RuntimeException('Delegate class unavailable');
    }

    /**
     * @inheritDoc
     */
    public function canDelegate(string $mixin): bool
    {
        return $this->hasDelegate() && in_array($mixin, $this->delegatedMixins, true);
    }

    /**
     * @inheritDoc
     */
    public function hasDelegate(): bool
    {
        return (bool)$this->delegate;
    }

    /**
     * @inheritDoc
     */
    public function getDelegate(): ?object
    {
        return $this->delegate;
    }

    /**
     * @inheritDoc
     */
    public function setDelegate(object $delegate): ViewEngineInterface
    {
        $this->delegate = $delegate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDelegateMixin(string $mixin): ViewEngineInterface
    {
        $this->delegatedMixins[] = $mixin;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLoader(string $loader): ViewEngineInterface
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share(string $key, $value = null): ViewEngineInterface
    {
        $this->addData([$key => $value]);

        return $this;
    }
}
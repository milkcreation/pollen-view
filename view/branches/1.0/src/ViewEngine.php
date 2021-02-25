<?php

declare(strict_types=1);

namespace Pollen\View;

use BadMethodCallException;
use League\Plates\Engine as BaseViewEngine;
use Throwable;
use RuntimeException;

class ViewEngine extends BaseViewEngine implements ViewEngineInterface
{
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
     * {@inheritDoc}
     *
     * @return ViewTemplateInterface
     */
    public function make($name): ViewTemplateInterface
    {
        return new ViewTemplate($this, $name);
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
    public function share(string $key, $value = null): ViewEngineInterface
    {
        $this->addData([$key => $value]);

        return $this;
    }
}
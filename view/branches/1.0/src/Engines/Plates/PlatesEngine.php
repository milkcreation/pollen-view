<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

use League\Plates\Engine as BasePlatesEngine;
use BadMethodCallException;
use LogicException;
use Pollen\Support\Proxy\ContainerProxy;
use RuntimeException;
use Throwable;

class PlatesEngine extends BasePlatesEngine
{
    use ContainerProxy;

    /**
     * Instance de la classe de délégation d'appel de méthodes.
     */
    protected ?object $delegate = null;

    /**
     * Liste des méthodes de délégations permises.
     * @var string[]
     */
    protected array $delegatedMixins = [];

    /**
     * Classe de chargement des gabarits d'affichage.
     * @return string
     */
    protected string $templateClass = PlatesTemplate::class;

    /**
     * @inheritDoc
     */
    public function exists($name): bool
    {
        try {
            return parent::exists($this->getFolders()->exists('_override_dir') ? "_override_dir::$name" : $name);
        } catch (LogicException $e) {
            throw $e;
        }
    }

    /**
     * @param string $name
     *
     * @return PlatesTemplate
     */
    public function make($name): PlatesTemplate
    {
        $regex = <<< REGEXP
        \:\:
        REGEXP;

        if (!preg_match("/$regex/", $name)) {
            $name = $this->getFolders()->exists('_override_dir') ? "_override_dir::$name" : $name;
        }

        $template = new $this->templateClass($this, $name);

        if ($container = $this->getContainer()) {
            $template->setContainer($container);
        }

        return $template;
    }

    /**
     * Appel d'une méthode de délégation.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
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
     * Vérification des permissions d'appel d'une methode de délégation.
     *
     * @param string $mixin
     *
     * @return bool
     */
    public function canDelegate(string $mixin): bool
    {
        return $this->hasDelegate() && in_array($mixin, $this->delegatedMixins, true);
    }

    /**
     * Vérification d'existence d'une classe de délégation.
     *
     * @return bool
     */
    public function hasDelegate(): bool
    {
        return (bool)$this->delegate;
    }

    /**
     * Récupération de la classe de délégation.
     *
     * @return object|null
     */
    public function getDelegate(): ?object
    {
        return $this->delegate;
    }

    /**
     * Déclaration d'un instance de délégation d'appel de méthodes.
     *
     * @param object $delegate
     *
     * @return static
     */
    public function setDelegate(object $delegate): self
    {
        $this->delegate = $delegate;

        return $this;
    }

    /**
     * Déclaration d'une méthode de délégation.
     *
     * @param string $mixin
     *
     * @return static
     */
    public function setDelegateMixin(string $mixin): self
    {
        $this->delegatedMixins[] = $mixin;

        return $this;
    }

    /**
     * Définition de la classe de chargement des gabarits d'affichage.
     *
     * @param string $templateClass
     *
     * @return static
     */
    public function setTemplateClass(string $templateClass): self
    {
        $this->templateClass = $templateClass;

        return $this;
    }
}
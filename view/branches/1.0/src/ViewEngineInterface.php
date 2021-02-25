<?php

declare(strict_types=1);

namespace Pollen\View;

/**
 * @mixin \League\Plates\Engine
 */
interface ViewEngineInterface
{
    /**
     * @param string $name
     *
     * @return ViewTemplateInterface
     */
    public function make(string $name): ViewTemplateInterface;

    /**
     * Appel d'une méthode de délégation.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function callDelegate(string $method, array $args);

    /**
     * Vérification des permissions d'appel d'une methode de délégation.
     *
     * @param string $mixin
     *
     * @return bool
     */
    public function canDelegate(string $mixin): bool;

    /**
     * Vérification d'existence d'une classe de délégation.
     *
     * @return bool
     */
    public function hasDelegate(): bool;

    /**
     * Récupération de la classe de délégation.
     *
     * @return object|null
     */
    public function getDelegate(): ?object;

    /**
     * Déclaration d'un instance de délégation d'appel de méthodes.
     *
     * @param object $delegate
     *
     * @return static
     */
    public function setDelegate(object $delegate): ViewEngineInterface;

    /**
     * Déclaration d'une méthode de délégation.
     *
     * @param string $mixin
     *
     * @return static
     */
    public function setDelegateMixin(string $mixin): ViewEngineInterface;

    /**
     * Définition d'une variable partagée passée à l'ensemble des gabarits
     *
     * @param string $key Clé d'indice de la variable.
     * @param mixed $value Valeur de la variable.
     *
     * @return static
     */
    public function share(string $key, $value = null): ViewEngineInterface;
}
<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

/**
 * @mixin \League\Plates\Template\Template
 */
interface PlatesTemplateInterface
{
    /**
     * Récupération de la liste des paramètres.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Récupération de paramètres.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed|null $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Récupération|Linéarisation d'attributs HTML.
     *
     * @param array|null $attrs Liste des attributs HTML.
     * @param bool $linearized Activation de la linéarisation.
     *
     * @return string|array
     */
    public function htmlAttrs(?array $attrs = null, bool $linearized = true);
}
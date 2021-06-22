<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

use Pollen\Support\Arr;
use Pollen\Support\Html;
use League\Plates\Template\Template;
use Pollen\Support\Proxy\ContainerProxy;

class PlatesTemplate extends Template
{
    use ContainerProxy;

    /**
     * @var PlatesViewEngine
     */
    protected $engine;

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        if ($this->engine->canDelegate($name)) {
            return $this->engine->callDelegate($name, $arguments);
        }
        return parent::__call($name, $arguments);
    }

    /**
     * Récupération de la liste des paramètres.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Récupération de paramètres.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed|null $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * Récupération de l'instance du moteur de gabarit d'affichage.
     *
     * @return PlatesViewEngine
     */
    protected function getEngine(): PlatesViewEngine
    {
        return $this->engine;
    }

    /**
     * Récupération|Linéarisation d'attributs HTML.
     *
     * @param array|null $attrs Liste des attributs HTML.
     * @param bool $linearized Activation de la linéarisation.
     *
     * @return string|array
     */
    public function htmlAttrs(?array $attrs = null, bool $linearized = true)
    {
        $attr = $attrs ?? $this->get('attrs', []);

        return $linearized ? Html::attr($attr) : $attr;
    }
}
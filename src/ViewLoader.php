<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Support\Arr;
use Pollen\Support\Html;
use League\Plates\Template\Template;
use Pollen\Support\Proxy\ContainerProxy;

class ViewLoader extends Template implements ViewLoaderInterface
{
    use ContainerProxy;

    /**
     * @var ViewEngineInterface
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
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getEngine(): ViewEngineInterface
    {
        return $this->engine;
    }

    /**
     * @inheritDoc
     */
    public function htmlAttrs(?array $attrs = null, bool $linearized = true)
    {
        $attr = $attrs ?? $this->get('attrs', []);

        return $linearized ? Html::attr($attr) : $attr;
    }
}
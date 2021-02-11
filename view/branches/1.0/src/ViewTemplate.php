<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Support\Arr;
use Pollen\Support\HtmlAttrs;
use League\Plates\Template\Template;

class ViewTemplate extends Template implements ViewTemplateInterface
{
    /**
     * @var ViewEngineInterface
     */
    protected $engine;

    /**
     * @param ViewEngine $engine
     * @param string $name
     */
    public function __construct(ViewEngine $engine, string $name)
    {
        parent::__construct($engine, $name);
    }

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        if ($this->engine->hasDelegate($name)) {
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
        return HtmlAttrs::createFromAttrs(is_array($attrs) ? $attrs : $this->get('attrs', []), $linearized);
    }
}
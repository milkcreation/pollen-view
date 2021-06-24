<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Support\Proxy\ContainerProxy;

abstract class AbstractViewEngine implements ViewEngineInterface
{
    use ContainerProxy;

    protected array $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }
}
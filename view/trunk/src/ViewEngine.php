<?php

declare(strict_types=1);

namespace Pollen\View;

use League\Plates\Engine as BaseViewEngine;

class ViewEngine extends BaseViewEngine implements ViewEngineInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ViewTemplate
     */
    public function make($name): ViewTemplate
    {
        return new ViewTemplate($this, $name);
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
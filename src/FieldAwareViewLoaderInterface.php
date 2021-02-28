<?php

declare(strict_types=1);

namespace Pollen\View;

interface FieldAwareViewLoaderInterface
{
    /**
     * Rendu d'un champ.
     *
     * @param string $alias
     * @param mixed $idOrParams
     * @param array $params
     *
     * @return string
     */
    public function field(string $alias, $idOrParams = null, array $params = []): string;
}
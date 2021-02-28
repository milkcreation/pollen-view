<?php

declare(strict_types=1);

namespace Pollen\View;

interface PartialAwareViewLoaderInterface
{
    /**
     * Rendu d'une portion d'affichage.
     *
     * @param string $alias
     * @param mixed $idOrParams
     * @param array $params
     *
     * @return string
     */
    public function partial(string $alias, $idOrParams = null, array $params = []): string;
}
<?php

declare(strict_types=1);

namespace Pollen\View;

use Pollen\Partial\PartialManager;
use Pollen\Partial\PartialManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

trait PartialAwareViewLoader
{
    /**
     * Instance du gestionnaire de portions d'affichage.
     * @var PartialManagerInterface
     */
    private $partialManager;

    /**
     * Rendu d'une portion d'affichage.
     *
     * @param string $alias
     * @param mixed $idOrParams
     * @param array $params
     *
     * @return string
     */
    public function partial(string $alias, $idOrParams = null, array $params = []): string
    {
        if ($this->partialManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(PartialManagerInterface::class)) {
                $this->partialManager = $container->get(PartialManagerInterface::class);
            } else {
                try {
                    $this->partialManager = PartialManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->partialManager = new PartialManager();
                }
            }
        }
        return (string)$this->partialManager->get($alias, $idOrParams, $params);
    }
}
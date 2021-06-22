<?php

declare(strict_types=1);

namespace Pollen\View\Engines\Plates;

use Pollen\Field\FieldManager;
use Pollen\Field\FieldManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

trait PlatesFieldAwareTemplateTrait
{
    /**
     * Instance du gestionnaire de champs.
     */
    private ?FieldManagerInterface $fieldManager = null;

    /**
     * Rendu d'un champ.
     *
     * @param string $alias
     * @param mixed $idOrParams
     * @param array $params
     *
     * @return string
     */
    public function field(string $alias, $idOrParams = null, array $params = []): string
    {
        if ($this->fieldManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(FieldManagerInterface::class)) {
                $this->fieldManager = $container->get(FieldManagerInterface::class);
            } else {
                try {
                    $this->fieldManager = FieldManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->fieldManager = new FieldManager();
                }
            }
        }
        return $this->fieldManager->get($alias, $idOrParams, $params)->render();
    }
}
<?php

declare(strict_types=1);

/**
 * /src/Document/Adaptador/AdaptadoresManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document\Adaptador;

use function ksort;

/**
 * Class AdaptadoresManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AdaptadorManager
{
    /**
     * @var AdaptadorInterface[]
     */
    private array $adaptadores = [];

    /**
     * @return AdaptadorInterface[]
     */
    public function getAdaptadores(): array
    {
        return $this->adaptadores;
    }

    /**
     * @param AdaptadorInterface $adaptador
     */
    public function addAdaptador(AdaptadorInterface $adaptador): void
    {
        $this->adaptadores[$adaptador->getOrder()][] = $adaptador;
    }

    /**
     * @param $document
     * @param $entity
     * @param array|null $context
     */
    public function proccess($document, $entity, ?array $context = null): void
    {
        ksort($this->adaptadores);

        foreach ($this->adaptadores as $adaptadorOrdered) {
            /** @var AdaptadorInterface $adaptador */
            foreach ($adaptadorOrdered as $adaptador) {
                if ($adaptador->supports() === get_class($document)) {
                    $adaptador->process($document, $entity, $context);
                }
            }
        }
    }
}

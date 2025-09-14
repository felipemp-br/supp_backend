<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/EspecieProcesso/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\EspecieProcesso;

use Doctrine\ORM\NonUniqueResultException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{

    /**
     * Pipe0001 constructor.
     */
    public function __construct(private RequestStack $requestStack) {
    }

    public function supports(): array
    {
        return [
            EspecieProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     *
     * @throws NonUniqueResultException
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        if ($this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->especieProcessoWorkflow) && ($context->especieProcessoWorkflow)) {
                $restDto->setWorkflow(!$entity->getVinculacoesEspecieProcessoWorkflow()?->isEmpty());
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CompartilhamentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Pipe0005.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0005 implements PipeInterface
{
    /**
     * Pipe0005 constructor.
     *
     * @param RequestStack             $requestStack
     * @param TokenStorageInterface    $tokenStorage
     * @param CompartilhamentoResource $compartilhamentoResource
     */
    public function __construct(
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
        private CompartilhamentoResource $compartilhamentoResource
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|Processo          $entity
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        $context = null;
        if (null !== $this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
        }

        if (isset($context->compartilhamentoUsuario) && ('processo' === $context->compartilhamentoUsuario)) {
            $compartilhamentoEntity = $this->compartilhamentoResource->findOneBy(
                [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'processo' => $entity->getId(),
                ]
            );

            if ($compartilhamentoEntity) {
                /** @var Compartilhamento $compartilhamentoDTO */
                $compartilhamentoDTO = new Compartilhamento();
                $compartilhamentoDTO->setId($compartilhamentoEntity->getId());
                $compartilhamentoDTO->setUuid($compartilhamentoEntity->getUuid());
                $restDto->setCompartilhamentoUsuario($compartilhamentoDTO);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

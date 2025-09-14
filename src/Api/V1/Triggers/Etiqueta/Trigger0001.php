<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Etiqueta/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Etiqueta;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Realiza a vinculação entre a etiqueta e o usuário, setor, unidade e/ou orgaoCentral que a está criando!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        TokenStorageInterface $tokenStorage
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Etiqueta::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Etiqueta|RestDtoInterface|null $restDto
     * @param EtiquetaEntity|EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // não enviou nada, vincula ao usuário
        if ($restDto->getUsuario() ||
            (!$restDto->getUsuario() &&
            !$restDto->getSetor() &&
            !$restDto->getUnidade() &&
            !$restDto->getModalidadeOrgaoCentral())) {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta($entity);
            $vinculacaoEtiquetaDTO->setUsuario($usuario);
            $vinculacaoEtiquetaDTO->setPrivada($restDto->getPrivada());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }

        // enviou setor, vincula
        if ($restDto->getSetor()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta($entity);
            $vinculacaoEtiquetaDTO->setSetor($restDto->getSetor());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }

        // enviou unidade, vincula
        if ($restDto->getUnidade()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta($entity);
            $vinculacaoEtiquetaDTO->setUnidade($restDto->getUnidade());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }

        // enviou setor, vincula
        if ($restDto->getModalidadeOrgaoCentral()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta($entity);
            $vinculacaoEtiquetaDTO->setModalidadeOrgaoCentral($restDto->getModalidadeOrgaoCentral());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

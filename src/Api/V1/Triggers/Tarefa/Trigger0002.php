<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Distribuicao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DistribuicaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger  =Gera o objeto distribuição para a tarefa!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        private readonly DistribuicaoResource $distribuicaoResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $distribuicao = new Distribuicao();
        $distribuicao->setTarefa($entity);
        $distribuicao->setUsuarioPosterior($entity->getUsuarioResponsavel());
        $distribuicao->setSetorPosterior($entity->getSetorResponsavel());
        $distribuicao->setDistribuicaoAutomatica($entity->getDistribuicaoAutomatica());
        $distribuicao->setLivreBalanceamento($entity->getLivreBalanceamento());
        $distribuicao->setAuditoriaDistribuicao($entity->getAuditoriaDistribuicao());
        $distribuicao->setTipoDistribuicao($entity->getTipoDistribuicao());
        $distribuicao->setDataHoraDistribuicao(new DateTime());

        $this->distribuicaoResource->create($distribuicao, $transactionId);
    }

    public function getOrder(): int
    {
        return 3;
    }
}

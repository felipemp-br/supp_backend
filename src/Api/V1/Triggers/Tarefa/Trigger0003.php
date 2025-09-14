<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use function count;
use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Distribuicao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DistribuicaoResource;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Gera o objeto distribuição para a tarefa!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private DistribuicaoResource $distribuicaoResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        DistribuicaoResource $distribuicaoResource
    ) {
        $this->distribuicaoResource = $distribuicaoResource;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param Tarefa|null $restDto
     * @param TarefaEntity $entity
     * @param string $transactionId
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?Tarefa $restDto, TarefaEntity $entity, string $transactionId): void
    {
        /** @var \SuppCore\AdministrativoBackend\Entity\Distribuicao[] $ultimaDistribuicao */
        $ultimaDistribuicao = $this->distribuicaoResource->getRepository()->findUltimaDistribuicaoByTarefaId(
            $restDto->getId()
        );

        if (count($ultimaDistribuicao) &&
            (($entity->getUsuarioResponsavel()->getId() !== $ultimaDistribuicao[0]->getUsuarioPosterior()->getId()) ||
                ($entity->getSetorResponsavel()->getId() !==
                    $ultimaDistribuicao[0]->getSetorPosterior()->getId()))) {
            $distribuicao = new Distribuicao();
            $distribuicao->setTarefa($entity);
            $distribuicao->setUsuarioPosterior($entity->getUsuarioResponsavel());
            $distribuicao->setSetorPosterior($entity->getSetorResponsavel());
            $distribuicao->setUsuarioAnterior($ultimaDistribuicao[0]->getUsuarioPosterior());
            $distribuicao->setSetorAnterior($ultimaDistribuicao[0]->getSetorPosterior());
            $distribuicao->setDistribuicaoAutomatica($entity->getDistribuicaoAutomatica());

            $distribuicao->setLivreBalanceamento(
                $entity->getDistribuicaoAutomatica() && $entity->getTipoDistribuicao() >= 4
            );

            $distribuicao->setAuditoriaDistribuicao(
                $entity->getDistribuicaoAutomatica() ? $entity->getAuditoriaDistribuicao() : null
            );
            $distribuicao->setTipoDistribuicao(
                $entity->getDistribuicaoAutomatica() ? $entity->getTipoDistribuicao() : 0
            );
            $distribuicao->setDataHoraDistribuicao(new DateTime());

            $this->distribuicaoResource->create($distribuicao, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}

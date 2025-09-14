<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0018.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0018.
 *
 * @descSwagger=Cria a tarefa de reiteração de ofício
 *
 * @classeSwagger=Trigger0018
 */
class Trigger0018 implements TriggerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SetorRepository $setorRepository,
        private TarefaResource $tarefaResource,
        private EspecieTarefaResource $especieTarefaResource,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            DocumentoAvulsoDTO::class => [
                'beforeReiterar',
            ],
        ];
    }

    /**
     * @param DocumentoAvulsoDTO|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface    $entity
     * @param string                                   $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
            $dataFinalPrazo = new \DateTime();
            $dataFinalPrazo->add(new \DateInterval('P5D'));

            $protocolo = $this->setorRepository->findProtocoloInUnidade($restDto->getSetorDestino()->getId());

            if($protocolo) {
                $tarefa = new Tarefa();
                $tarefa->setProcesso($restDto->getProcessoDestino());
                $tarefa->setSetorResponsavel($protocolo);
                $tarefa->setDataHoraInicioPrazo(new \DateTime());
                $tarefa->setDataHoraFinalPrazo($dataFinalPrazo);
                $tarefa->setSetorOrigem($restDto->getSetorResponsavel());
                $tarefa->setEspecieTarefa($this->especieTarefaResource->findOneBy([
                    'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_8')
                ]));

                $this->tarefaResource->create($tarefa, $transactionId);

                $restDto->setDataHoraReiteracao(new \DateTime());
            }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Class Trigger0008.
 *
 * @descSwagger=Se o documento avulso não estiver encerrado mas com a tarefa encerrada, abre uma nova tarefa, Se o usuário responsável estiver afastado, abre no protocolo!
 * @classeSwagger=Trigger0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0008 implements TriggerInterface
{
    /**
     * Trigger0008 constructor.
     */
    public function __construct(
        private readonly AfastamentoResource $afastamentoResource,
        private readonly SetorRepository $setorRepository,
        private readonly TarefaResource $tarefaResource,
        private readonly EspecieTarefaResource $especieTarefaResource,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeResponder',
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
        $tarefaOrigem = $this->tarefaResource->getRepository()->findTarefaByDocumentoAvulso(
            $restDto->getId()
        );

        $this->transactionManager->addContext(
            new Context(
                'respostaDocumentoAvulso',
                true
            ),
            $transactionId
        );

        $especieTarefa = $this->especieTarefaResource->findOneBy(
            [
                'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_2'),
            ]
        );

        if ($tarefaOrigem && $tarefaOrigem->getUsuarioResponsavel()) {
            if ($tarefaOrigem->getUsuarioResponsavel()->getColaborador()) {
                $responsavelAfastado = $this->afastamentoResource->getRepository()
                    ->findAfastamento(
                        $tarefaOrigem->getUsuarioResponsavel()->getColaborador()?->getId(),
                        $tarefaOrigem->getDataHoraFinalPrazo()
                    );
            }
            // Tarefa origem não concluída e com responsável afastado
            if (null === $tarefaOrigem->getDataHoraConclusaoPrazo() && $responsavelAfastado) {
                // Atualizar etiqueta na tarefa origem.
                $tarefa = $this->tarefaResource->update(
                    $tarefaOrigem->getId(),
                    $this->tarefaResource->getDtoForEntity($tarefaOrigem->getId(), Tarefa::class),
                    $transactionId
                );
                $restDto->setTarefaOrigem($tarefa);

                // Cria uma nova tarefa para que o protocolo unidade seja informado da resposta do ofício.
                $this->transactionManager->removeContext('respostaDocumentoAvulso', $transactionId);
                $tarefaDTO = new Tarefa();
                $tarefaDTO->setProcesso($entity->getProcesso());
                $tarefaDTO->setEspecieTarefa($especieTarefa);
                $tarefaDTO->setDataHoraInicioPrazo($tarefaOrigem->getDataHoraInicioPrazo());
                $tarefaDTO->setDataHoraFinalPrazo($tarefaOrigem->getDataHoraFinalPrazo());
                $tarefaDTO->setUsuarioResponsavel(null);
                $tarefaDTO->setSetorResponsavel(
                    $this->setorRepository->findProtocoloInUnidade(
                        $entity->getSetorResponsavel()->getUnidade()->getId()
                    )
                );
                $tarefaDTO->setObservacao(
                    sprintf(
                        'TAREFA CRIADA DEVIDO À RESPOSTA DE OFÍCIO PARA USUÁRIO AFASTADO (%s).',
                        $tarefaOrigem->getUsuarioResponsavel()->getNome()
                    )
                );

                $this->tarefaResource->create($tarefaDTO, $transactionId);
            }
        }

        // Tarefa encerrada ou apagada
        if (!$entity->getDataHoraResposta() &&
            (!$tarefaOrigem || $tarefaOrigem->getDataHoraConclusaoPrazo())) {
            $setorResponsavel = $restDto->getSetorResponsavel();
            $usuarioResponsavel = $restDto->getUsuarioResponsavel();

            $inicioPrazo = new DateTime();
            $finalPrazo = new DateTime();
            $finalPrazo->add(new DateInterval('P5D'));

            $tarefaDTO = new Tarefa();
            $tarefaDTO->setProcesso($entity->getProcesso());
            $tarefaDTO->setEspecieTarefa($especieTarefa);
            $tarefaDTO->setSetorResponsavel($setorResponsavel);
            $tarefaDTO->setUsuarioResponsavel($usuarioResponsavel);
            $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
            $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

            try {
                $tarefa = $this->tarefaResource->create($tarefaDTO, $transactionId);
            } catch (Throwable) {
                // afastamento do usuário responsável ou outro impeditivo para mandar ao protocolo
                $tarefaDTO->setUsuarioResponsavel(null);
                $tarefaDTO->setSetorResponsavel(
                    $this->setorRepository->findProtocoloInUnidade(
                        $entity->getSetorResponsavel()->getUnidade()->getId()
                    )
                );
                $tarefa = $this->tarefaResource->create($tarefaDTO, $transactionId);
            }

            $restDto->setTarefaOrigem($tarefa);
        }

        $this->transactionManager->removeContext('respostaDocumentoAvulso', $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}

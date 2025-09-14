<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0028.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0028.
 *
 * @descSwagger=Cria a etiqueta de OFICIO RESPONDIDO ao criar Tarefa de Resposta de Ofício
 * @classeSwagger=Trigger0028
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0028 implements TriggerInterface
{
    /**
     * Trigger0028 constructor.
     */
    public function __construct(
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private EtiquetaRepository $etiquetaRepository,
        private ParameterBagInterface $parameterBag,
        private TransactionManager $transactionManager,
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
     * @param Tarefa|RestDtoInterface|null $tarefaDto
     * @param TarefaEntity|EntityInterface $tarefaEntity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $tarefaDto, EntityInterface $tarefaEntity, string $transactionId): void
    {
        if ($this->transactionManager->getContext('respostaDocumentoAvulso', $transactionId)) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta(
                $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_8'),
                        'sistema' => true,
                    ]
                )
            );
            $vinculacaoEtiquetaDTO->setTarefa($tarefaEntity);
            $vinculacaoEtiquetaDTO->setObjectClass(get_class($tarefaEntity));
            $vinculacaoEtiquetaDTO->setObjectUuid($tarefaEntity->getUuid());
            $vinculacaoEtiquetaDTO->setLabel('OFÍCIO RESPONDIDO');
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

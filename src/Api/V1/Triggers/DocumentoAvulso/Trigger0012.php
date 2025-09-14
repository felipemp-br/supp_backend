<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Atualiza a etiqueta da tarefa vinculada para ofício remetido!
 * @classeSwagger=Trigger0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0012 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0012 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'afterRemeter',
            ],
        ];
    }

    /**
     * @param EntityInterface|DocumentoAvulsoEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getTarefaOrigem()) {
            foreach ($entity->getTarefaOrigem()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($vinculacaoEtiqueta->getEtiqueta()->getSistema() &&
                    'OFÍCIO EM ELABORAÇÃO' === $vinculacaoEtiqueta->getEtiqueta()->getNome() &&
                    $entity->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                    DocumentoAvulsoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                        $vinculacaoEtiqueta->getId(),
                        VinculacaoEtiqueta::class
                    );
                    $vinculacaoEtiquetaDTO->setEtiqueta(
                        $this->etiquetaRepository->findOneBy(
                            [
                                'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_4'),
                                'sistema' => true,
                            ]
                        )
                    );
                    $vinculacaoEtiquetaDTO->setLabel('OFÍCIO REMETIDO');
                    $this->vinculacaoEtiquetaResource->update(
                        $vinculacaoEtiqueta->getId(),
                        $vinculacaoEtiquetaDTO,
                        $transactionId
                    );
                    break;
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

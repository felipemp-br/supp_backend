<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Caso seja o documento seja uma minuta e esteja vinculado a uma tarefa, será criada uma etiqueta!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0005 constructor.
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
            Documento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param EntityInterface|DocumentoEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$entity->getJuntadaAtual() && $entity->getTarefaOrigem() && !$entity->getDocumentoAvulsoRemessa()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta(
                $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_1'),
                        'sistema' => true,
                    ]
                )
            );
            $vinculacaoEtiquetaDTO->setTarefa($entity->getTarefaOrigem());
            $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
            $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
            $vinculacaoEtiquetaDTO->setLabel($entity->getTipoDocumento()->getSigla());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

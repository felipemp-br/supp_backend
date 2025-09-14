<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0010.php.
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
 * Class Trigger0010.
 *
 * @descSwagger=Caso seja o documento avulso seja uma minuta e esteja vinculado a uma tarefa, será criada uma etiqueta!
 * @classeSwagger=Trigger0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0010 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;
    private EtiquetaRepository $etiquetaRepository;
    private ParameterBagInterface $parameterBag;

    /**
     * Trigger0010 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        ParameterBagInterface $parameterBag
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'afterCreate',
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
        if (!$entity->getDataHoraRemessa() && $entity->getTarefaOrigem()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta(
                $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_3'),
                        'sistema' => true,
                    ]
                )
            );
            $vinculacaoEtiquetaDTO->setTarefa($entity->getTarefaOrigem());
            $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
            $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
            $vinculacaoEtiquetaDTO->setLabel('OFÍCIO EM ELABORAÇÃO');
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

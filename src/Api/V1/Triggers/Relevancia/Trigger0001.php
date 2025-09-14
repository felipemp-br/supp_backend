<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relevancia/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relevancia;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relevancia as RelevanciaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria etiqueta de relevancia no processo!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0001 constructor.
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
            Relevancia::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param EntityInterface|RelevanciaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (0 === $entity->getProcesso()->getRelevancias()->count()) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta(
                $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_6'),
                        'sistema' => true,
                    ]
                )
            );
            $vinculacaoEtiquetaDTO->setProcesso($entity->getProcesso());
            $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
            $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
            $vinculacaoEtiquetaDTO->setLabel($entity->getEspecieRelevancia()->getNome());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

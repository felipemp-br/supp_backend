<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Sigilo/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Sigilo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Sigilo as SigiloEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Repository\SigiloRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Cria etiqueta de sigilo no processo ou no documento!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    private SigiloRepository $sigiloRepository;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        SigiloRepository $sigiloRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
        $this->sigiloRepository = $sigiloRepository;
    }

    public function supports(): array
    {
        return [
            Sigilo::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param EntityInterface|SigiloEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $target = false;
        $method = '';
        $repositoryMethod = '';
        if ($entity->getProcesso()) {
            $target = $entity->getProcesso();
            $method = 'setProcesso';
            $repositoryMethod = 'findCountSigilosAtivosByProcessoId';
        }
        if ($entity->getDocumento()) {
            $target = $entity->getDocumento();
            $method = 'setDocumento';
            $repositoryMethod = 'findCountSigilosAtivosByDocumentoId';
        }
        if ($target && $target->getId() &&
            0 === $this->sigiloRepository->$repositoryMethod($target->getId())) {
            $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
            $vinculacaoEtiquetaDTO->setEtiqueta(
                $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_7'),
                        'sistema' => true,
                    ]
                )
            );
            $vinculacaoEtiquetaDTO->$method($target);
            $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
            $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
            $vinculacaoEtiquetaDTO->setLabel($entity->getTipoSigilo()->getNome());
            $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

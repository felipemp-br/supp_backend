<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Repository\EspecieSetorRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Reativar uma unidade reativa o procotolo e o arquivo.
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private EspecieSetorRepository $especieSetorRepository;

    private SetorResource $setorResource;

    /**
     * Trigger0004  constructor.
     */
    public function __construct(
       EspecieSetorRepository $especieSetorRepository,
       SetorResource $setorResource
    ) {
        $this->especieSetorRepository = $especieSetorRepository;
        $this->setorResource = $setorResource;
    }

    public function supports(): array
    {
        return [
            Setor::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null $restDto
     * @param SetorEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getParent() &&
            ($restDto->getAtivo() && !$entity->getAtivo())) {
            $protocolo = $this->setorResource->getRepository()->findProtocoloInUnidade($entity->getId());
            $protocoloDto = new Setor();
            $protocoloDto->setAtivo(true);
            //$this->setorResource->update($protocolo->getId(), $protocoloDto, $transactionId);

            $arquivo = $this->setorResource->getRepository()->findArquivoInUnidade($entity->getId());
            $arquivoDto = new Setor();
            $arquivoDto->setAtivo(true);
            //$this->setorResource->update($arquivo->getId(), $arquivoDto, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

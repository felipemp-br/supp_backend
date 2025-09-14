<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Assinatura/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Utils\AssinaturaService;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Excluir assinatura PAdES do PDF (assinatura e chancela).
 *
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        protected readonly AssinaturaService $assinaturaService,
        protected readonly AssinaturaResource $assinaturaResource,
        protected readonly ComponenteDigitalResource $componenteDigitalResource,
        protected readonly TransactionManager $transactionManager,
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            AssinaturaEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null            $restDto
     * @param EntityInterface|AssinaturaEntity $entity
     * @param string                           $transactionId
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $restDto,
        EntityInterface|AssinaturaEntity $entity,
        string $transactionId,
    ): void {
        if (AssinaturaPadrao::PAdES->value === $entity->getPadrao()) {
            // Recuperar as entidades Assinatura, relacionadas ao componente digital
            $assinaturas = $entity->getComponenteDigital()?->getAssinaturas();
            foreach ($assinaturas as $assinatura) {
                /** @var AssinaturaEntity $assinatura */
                if (AssinaturaPadrao::PAdES->value === $assinatura->getPadrao()
                    && $entity->getId() !== $assinatura->getId()
                ) {
                    // Apagar as outras entidades Assinatura PAdES, relacionadas ao componente digital
                    $this->assinaturaResource->getRepository()->remove($assinatura, $transactionId);
                }
            }

            // remover chancela e assinatura interna ao PDF
            $this->assinaturaService->removeSignaturePades(
                $entity->getComponenteDigital()->getId(),
                $transactionId
            );

            // se foi convertido de HTML para PDF
            $assinaturaConfig = $this->assinaturaService->getAssinaturaConfig(AssinaturaPadrao::PAdES);
            if (true === $assinaturaConfig['convertToHtmlAfterRemove']
                && $entity->getComponenteDigital()->getConvertidoPdf()
            ) {
                // Salvar PDF sem as assinaturas internas
                // retornar para HTML
                $this->componenteDigitalResource->convertPdfInternoToHTML2(
                    $entity->getComponenteDigital(),
                    $transactionId,
                    true
                );
            }
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
    }
}

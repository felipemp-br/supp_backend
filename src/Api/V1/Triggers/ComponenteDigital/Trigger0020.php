<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Caso não informado um documento para o componente digital, ele será automaticamente criado como minuta!
 *
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0020 implements TriggerInterface
{
    /**
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TransactionManager        $transactionManager
     * @param AssinaturaResource        $assinaturaResource
     */
    public function __construct(
        private ComponenteDigitalResource $componenteDigitalResource,
        private TransactionManager $transactionManager,
        private AssinaturaResource $assinaturaResource
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     * @param EntityInterface                         $entity
     * @param string                                  $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        ComponenteDigital|RestDtoInterface|null $restDto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        if ($this->transactionManager->getContext('clonarAssinatura', $transactionId)?->getValue()) {
            try {
                // pegando o mesmo tipo de documento do original
                $componenteOrigem = $restDto->getHash() ?
                    $this->componenteDigitalResource->findOneBy(['hash' => $restDto->getHash()]) : null;
                /** @var Assinatura $assinaturaClonada */
                foreach ($componenteOrigem->getAssinaturas() as $assinaturaClonada) {
                    if ($assinaturaClonada->getAssinatura() && !$assinaturaClonada->getOrigemDados()) {
                        $assinaturaDTO = new Assinatura();
                        $assinaturaDTO->setAlgoritmoHash($assinaturaClonada->getAlgoritmoHash());
                        $assinaturaDTO->setAssinatura($assinaturaClonada->getAssinatura());
                        $assinaturaDTO->setCadeiaCertificadoPEM($assinaturaClonada->getCadeiaCertificadoPEM());
                        $assinaturaDTO->setCadeiaCertificadoPkiPath($assinaturaClonada->getCadeiaCertificadoPkiPath());
                        $assinaturaDTO->setDataHoraAssinatura($assinaturaClonada->getDataHoraAssinatura());
                        $assinaturaDTO->setComponenteDigital($entity);
                        $assinaturaDTO->setCriadoPor($assinaturaClonada->getCriadoPor());
                        $assinaturaDTO->setPadrao($assinaturaClonada->getPadrao());
                        $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                    }
                }
                $this->transactionManager->removeContext(
                    'clonarAssinatura',
                    $transactionId
                );
            } catch(Exception $e) { }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}

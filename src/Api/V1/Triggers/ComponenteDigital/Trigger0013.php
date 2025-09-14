<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use function mb_strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0013.
 *
 * @descSwagger=Caso informado o id de um modelo, o conteudo é reprocessado!
 * @classeSwagger=Trigger0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0013 implements TriggerInterface
{
    private RendererManager $rendererManager;

    /**
     * Trigger0013 constructor.
     */
    public function __construct(
        RendererManager $rendererManager
    ) {
        $this->rendererManager = $rendererManager;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ComponenteDigital|null $restDto
     * @param EntityInterface|ComponenteDigitalEntity $entity
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var ComponenteDigital $componenteDigitalDTO */
        $componenteDigitalDTO = $restDto;

        if ($componenteDigitalDTO->getModelo() &&
            $entity->getModelo() &&
            $entity->getEditavel() &&
            ($entity->getModelo()->getId() !== $componenteDigitalDTO->getModelo()->getId())) {
            $componenteDigitalDTO->setEditavel(true);
            $componenteDigitalDTO->setFileName(
                $componenteDigitalDTO->getModelo()->getDocumento()->getComponentesDigitais()[0]->getFileName()
            );
            $componenteDigitalDTO->setMimetype('text/html');
            $componenteDigitalDTO->setNivelComposicao(3);
            $componenteDigitalDTO->setExtensao('html');

            $conteudo = $this->rendererManager->renderModelo(
                $componenteDigitalDTO,
                $transactionId,
                $componenteDigitalDTO->getModelo()->getContextoEspecifico() ?: []
            );

            $componenteDigitalDTO->setConteudo($conteudo);
            $componenteDigitalDTO->setTamanho(mb_strlen($conteudo));
            $componenteDigitalDTO->setHash(hash('SHA256', $conteudo));
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use function mb_strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0008.
 *
 * @descSwagger=Caso informado o id de um componente digital de origem, o conteudo é clonado e reprocessado!
 * @classeSwagger=Trigger0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0008 implements TriggerInterface
{
    /**
     * Trigger0008 constructor.
     */
    public function __construct(
        protected RendererManager $rendererManager,
        protected ComponenteDigitalResource $componenteDigitalResource
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeCreate',
                'beforeAprovar',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     * @param string $transactionId
     * @throws \Exception
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigitalEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        /** @var ComponenteDigitalDTO $componenteDigitalDTO */
        $componenteDigitalDTO = $restDto;

        if ($componenteDigitalDTO->getComponenteDigitalOrigem()) {
            /* @var ComponenteDigitalEntity $componenteDigitalClonado */
            $componenteDigitalClonado = $componenteDigitalDTO->getComponenteDigitalOrigem();
            $componenteDigitalDTO->setFileName($componenteDigitalClonado->getFileName());
            $componenteDigitalDTO->setHash($componenteDigitalClonado->getHash());
            $componenteDigitalDTO->setTamanho($componenteDigitalClonado->getTamanho());
            $componenteDigitalDTO->setMimetype($componenteDigitalClonado->getMimetype());
            $componenteDigitalDTO->setExtensao($componenteDigitalClonado->getExtensao());
            $componenteDigitalDTO->setModelo($componenteDigitalClonado->getModelo());
            $componenteDigitalDTO->setEditavel($componenteDigitalClonado->getEditavel());

            if ($componenteDigitalClonado->getEditavel()) {
                $conteudoOriginal = $this->componenteDigitalResource->download(
                    $componenteDigitalClonado->getId(),
                    $transactionId
                )->getConteudo();
                $conteudoReprocessado = $this->rendererManager->renderModelo(
                    $componenteDigitalDTO,
                    $transactionId,
                    [],
                    $conteudoOriginal
                );

                $componenteDigitalDTO->setConteudo($conteudoReprocessado);
                $componenteDigitalDTO->setTamanho(mb_strlen($conteudoReprocessado));
                $componenteDigitalDTO->setHash(hash('SHA256', $conteudoReprocessado));
            }
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}

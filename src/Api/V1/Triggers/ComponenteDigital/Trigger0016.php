<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0016.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use function hash;
use function mb_strtolower;
use function pathinfo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class Trigger0016.
 *
 * @descSwagger=Realiza o processamento do conteudo no formato data-uri base64 no caso de renderização no formato HTML!
 * @classeSwagger=Trigger0016
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0016 implements TriggerInterface
{
    private RendererManager $rendererManager;

    /**
     * Trigger0003 constructor.
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
                'beforeRenderHtmlContent',
            ],
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getConteudo()) {
            $conteudo = $this->rendererManager->renderCkeditor($restDto->getConteudo());

            $entity->setConteudo($conteudo)
                ->setFileName($restDto->getFileName())
                ->setHash(hash('SHA256', $restDto->getConteudo()))
                ->setExtensao('html')
                ->setMimetype('text/html')
                ->setTamanho(mb_strlen($conteudo))
                ->setAllowUnsafe(true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

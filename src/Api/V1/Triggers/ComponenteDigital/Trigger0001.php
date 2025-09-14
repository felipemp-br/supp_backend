<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0001.php.
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
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Realiza o processamento do conteudo no formato data-uri base64 no caso de upload!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
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
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getConteudo()) {
            $normalizer = new DataUriNormalizer();

            try {
                $file = $normalizer->denormalize($restDto->getConteudo(), 'SplFileObject');
                $conteudo = '';
                while (!$file->eof()) {
                    $conteudo .= $file->fgets();
                }

                if ($restDto->getId() && $restDto->getEditavel()) {
                    $conteudo = $this->rendererManager->renderCkeditorWithContext($entity, $conteudo);
                }

                $restDto->setConteudo($conteudo);
                $restDto->setHash(hash('SHA256', $restDto->getConteudo()));
                $restDto->setExtensao(mb_strtolower(pathinfo($restDto->getFileName(), PATHINFO_EXTENSION)));

                $entity->setHashAntigo($entity->getHash());
            } catch (NotNormalizableValueException $e) {
                // não faz nada, significa que não foi um upload
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

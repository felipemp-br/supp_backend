<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger=Não é permitido assinar CAdES se o documento (anexo ou principal) já foi assinado com PAdES e vice-versa!
 *
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    /**
     * Rule0011 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
    ) {
    }

    public function supports(): array
    {
        return [
            AssinaturaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /** @var AssinaturaDTO $restDto */
        $documento = $restDto?->getComponenteDigital()?->getDocumento();

        if (!empty($documento) && !empty($documento->getId())) {
            // Verificar todos os componentes digitais relacionados ao documento (anexos)
            $componentesDigitais = $this->componenteDigitalResource->getRepository()->findVinculadosByDocumento($documento);
            foreach ($componentesDigitais as $componenteDigital) {
                $assinaturas = $componenteDigital->getAssinaturas();
                foreach ($assinaturas as $assinatura) {
                    /** @var Assinatura $assinatura */
                    if ((empty($restDto?->getPadrao()) || AssinaturaPadrao::CAdES->value === $restDto?->getPadrao())
                        && AssinaturaPadrao::PAdES->value === $assinatura->getPadrao()) {
                        // Assinando com CAdES e detecto assinaturas PAdES
                        $this->rulesTranslate->throwException('assinatura', '0011');
                    } elseif (AssinaturaPadrao::PAdES->value === $restDto->getPadrao()
                        && (empty($assinatura->getPadrao()) || AssinaturaPadrao::CAdES->value === $assinatura->getPadrao())) {
                        // Assinando com PAdES e detecto assinaturas CAdES
                        $this->rulesTranslate->throwException('assinatura', '0011');
                    }
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 11;
    }
}

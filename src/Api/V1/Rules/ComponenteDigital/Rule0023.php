<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\AssinaturaService;

/**
 * Class Rule0023.
 *
 * @descSwagger=Não foi possível realizar a conversão do arquivo, pois está assinado... Remova a(s) assinatura(s) e tente novamente!
 *
 * @classeSwagger=Rule0023
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0023 implements RuleInterface
{
    /**
     * Rule0022 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     * @param AssinaturaService $assinaturaService
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        protected readonly AssinaturaService $assinaturaService,
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigital|EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigital|EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($restDto?->getExtensao() !== $entity->getExtensao()
            && $entity->getAssinaturas()->count() > 0) {
            $assinatura = $entity->getAssinaturas()->get(0);

            if (null == $assinatura->getPadrao() || AssinaturaPadrao::CAdES->value === $assinatura->getPadrao()) {
                $this->rulesTranslate->throwException('componenteDigital', '0023');
            }
            // após tentar converter para html
            if (AssinaturaPadrao::PAdES->value === $assinatura->getPadrao()
                && 'application/pdf' === $entity->getMimetype()
                && (null == $entity->getConteudo() && null == $restDto->getConteudo())
            ) {
                $this->rulesTranslate->throwException('componenteDigital', '0023');
            }
            // após excluir assinatura PAdES, será alterado o conteúdo
            if (AssinaturaPadrao::PAdES->value === $assinatura->getPadrao()
                && 'application/pdf' === $entity->getMimetype()
                && $this->assinaturaService->getPdfCountSignature($entity->getConteudo()) > 0) {
                $this->rulesTranslate->throwException('componenteDigital', '0023');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 23;
    }
}

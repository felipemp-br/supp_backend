<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Assinatura/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoAssinaturaExternaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0009.
 *
 * @descSwagger=Se houver juntada atual ou usuário externo com assinatura pades, a assinatura não poderá ser excluída em qualquer hipótese
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    /**
     * Rule0009 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly VinculacaoDocumentoAssinaturaExternaRepository $vinculacaoDocumentoAssinaturaExternaRepository,
    ) {}

    public function supports(): array
    {
        return [
            Assinatura::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura|RestDtoInterface|null $restDto
     * @param Assinatura|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getComponenteDigital()->getDocumento()->getJuntadaAtual()) {
            $this->rulesTranslate->throwException('assinatura', '0009');
        }

        if ($this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO') && $entity->getPadrao() === 'PAdES') {
            $this->rulesTranslate->throwException('assinatura', '0013');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 9;
    }
}
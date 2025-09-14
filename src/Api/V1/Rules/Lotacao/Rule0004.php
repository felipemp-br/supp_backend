<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0005.
 *
 * @descSwagger=Verifica se o usuário possui poderes para criar a lotação.
 * @classeSwagger=Rule0004
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        CoordenadorService $coordenadorService
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            LotacaoDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|LotacaoDTO|null $restDto
     * @param EntityInterface|LotacaoEntity    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) { //super admin
            return true;
        }

        if (!$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$restDto->getSetor()->getUnidade()]) &&
            !$this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral(
                [$restDto->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
            )
        ) {
            $this->rulesTranslate->throwException('lotacao', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

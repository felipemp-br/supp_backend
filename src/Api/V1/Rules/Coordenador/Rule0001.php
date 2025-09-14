<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Coordenador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador as CoordenadorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Coordenador;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o usuário tem permissão de criar o vínculo de usuário a Coordenador
 * @classeSwagger=Rule0002
 */
class Rule0001 implements RuleInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;

    private RulesTranslate $rulesTranslate;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RulesTranslate $rulesTranslate,
        CoordenadorService $coordenadorService
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->rulesTranslate = $rulesTranslate;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            CoordenadorDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|CoordenadorDTO|null $restDto
     * @param EntityInterface|Coordenador          $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getOrgaoCentral() &&
            !$this->authorizationChecker->isGranted('ROLE_ADMIN')
        ) { /* é preciso ser root */
            $this->rulesTranslate->throwException('coordenador', '0001');
        }

        if ($restDto->getUnidade() &&
            !$this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$restDto->getUnidade()->getModalidadeOrgaoCentral()]) &&
            !$this->authorizationChecker->isGranted('ROLE_ADMIN')
        ) { /* é preciso ser coordenadorOrgaoCentral */
            $this->rulesTranslate->throwException('coordenador', '0002');
        }

        if ($restDto->getSetor() &&
            !$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$restDto->getSetor()->getUnidade()]) &&
            !$this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [
                        $restDto->getSetor()
                            ->getUnidade()
                            ->getModalidadeOrgaoCentral(),
                    ]
                ) &&
            !$this->authorizationChecker->isGranted('ROLE_ADMIN')
        ) {/* é preciso ser coordenador de unidade ou do órgão central da unidade*/
            $this->rulesTranslate->throwException('coordenador', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

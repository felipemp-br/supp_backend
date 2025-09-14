<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Setor;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0012.
 *
 * @descSwagger=Verifica se tem permissão para alterar o setor.
 * @classeSwagger=Rule0013
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0012 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0011 constructor.
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
            SetorDTO::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param SetorDTO|RestDtoInterface|null $restDto
     * @param SetorEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // setor
        if ($entity->getParent() &&
            (
                !$this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral(
                    [$entity->getUnidade()->getModalidadeOrgaoCentral()]) &&
                !$this->coordenadorService->verificaUsuarioCoordenadorUnidade(
                    [$entity->getUnidade()])
            )
        ) {
            $this->rulesTranslate->throwException('setor', '0011');
        }

        // unidade
        if (!$entity->getParent() &&
            !$this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$entity->getModalidadeOrgaoCentral()])
        ) {
            $this->rulesTranslate->throwException('setor', '0011');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Lembrete;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lembrete;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Lembrete não pertence ao usuário e não pode ser excluído!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private AuthorizationCheckerInterface $authorizationChecker;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        CoordenadorService $coordenadorService
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            Lembrete::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete|RestDtoInterface|null $restDto
     * @param Lembrete|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto && $restDto->getProcesso()) {
            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$restDto->getProcesso()->getSetorAtual()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$restDto->getProcesso()->getSetorAtual()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$restDto->getProcesso()->getSetorAtual()->getUnidade()->getModalidadeOrgaoCentral()]
                );
            if (false === $isCoordenador &&
                $entity->getCriadoPor() &&
                $this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                $entity->getCriadoPor()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
                $this->rulesTranslate->throwException('lembrete', '0003');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Transicao/Rule0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0007.
 *
 * @descSwagger  =Verifica a permissão de arquivista
 * @classeSwagger=Rule0007
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TransicaoRepository $TransicaoRepository;

    private TokenStorageInterface $tokenStorage;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0007 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TransicaoRepository $TransicaoRepository,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->TransicaoRepository = $TransicaoRepository;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Transicao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$this->tokenStorage->getToken() ||
            $this->tokenStorage->getToken()->getUser()) {
            return true;
        }

        $ok = false;
        /** @var Lotacao $lotacao */
        foreach ($this->tokenStorage->getToken()->getUser()->getColaborador()->getLotacoes() as $lotacao) {
            if ($lotacao->getArquivista() &&
                ($lotacao->getSetor()->getId() === $restDto->getProcesso()->getSetorAtual()->getId())) {
                $ok = true;
                break;
            }
        }

        if (!$ok) {
            $this->rulesTranslate->throwException('transicao', '0007');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

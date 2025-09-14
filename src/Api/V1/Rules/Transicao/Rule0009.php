<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Transicao/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0009.
 *
 * @descSwagger  =Verifica se data prevista de transição está no passado
 * @classeSwagger=Rule0009
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private TokenStorageInterface $tokenStorage;

    /**
     * Rule0009 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
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
        if ($this->parameterBag->get('constantes.entidades.modalidade_transicao.const_1') === $restDto->getModalidadeTransicao()->getValor() ||
            $this->parameterBag->get('constantes.entidades.modalidade_transicao.const_2') === $restDto->getModalidadeTransicao()->getValor() ||
            $this->parameterBag->get('constantes.entidades.modalidade_transicao.const_5') === $restDto->getModalidadeTransicao()->getValor()) {
            if (!$restDto->getProcesso()->getDataHoraProximaTransicao()) {
                $this->rulesTranslate->throwException('transicao', '0009a');
            }

            $agora = new DateTime();

            if ($restDto->getProcesso()->getDataHoraProximaTransicao() > $agora) {
                $this->rulesTranslate->throwException('transicao', '0009b');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}

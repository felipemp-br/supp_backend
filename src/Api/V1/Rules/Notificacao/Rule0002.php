<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Notificaocao/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Notificacao;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Notificacao;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Apenas o destinatário pode apagar a notificação!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    /**
     * Rule0001 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate, TokenStorageInterface $tokenStorage)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Notificacao::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao|RestDtoInterface|null $restDto
     * @param Notificacao|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->tokenStorage->getToken()->getUser()->getId() !== $entity->getDestinatario()->getId()) {
            $this->rulesTranslate->throwException('notificacao', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

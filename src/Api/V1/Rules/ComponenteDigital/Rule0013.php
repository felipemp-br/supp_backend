<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use DateTime;
use function hash;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0013.
 *
 * @descSwagger=Não pode haver um lock de edição!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    /**
     * Rule0013 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // está editando o conteúdo?
        if ($restDto->getConteudo() &&
            hash('SHA256', $restDto->getConteudo()) !== $entity->getHash()) {
            $username = $this->tokenStorage->getToken()->getUser()->getUserIdentifier();

            $dataHoraLockEdicao = $entity->getDataHoraLockEdicao();
            $usernameLockEdicao = $entity->getUsernameLockEdicao();

            $agora = new DateTime();

            if ($dataHoraLockEdicao &&
                $username !== $usernameLockEdicao &&
                !$this->validaIntervalo($dataHoraLockEdicao, $agora)) {
                $this->rulesTranslate->throwException('componenteDigital', '0013');
            }
        }

        return true;
    }

    /**
     * @param DateTime $date
     * @param DateTime $agora
     *
     * @return bool
     */
    private function validaIntervalo(DateTime $date, DateTime $agora): bool
    {
        $interval = $date->diff($agora);
        if ($interval->y > 0 || $interval->m > 0 || $interval->d > 0 || $interval->h > 0) {
            return true;
        }
        if ($interval->i < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\GrupoContato;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GrupoContato as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GrupoContato as Entity;
use SuppCore\AdministrativoBackend\Repository\GrupoContatoRepository as Repository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica unicidade de nome
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param Repository            $repository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private Repository $repository,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'beforeCreate',
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param DTO|RestDtoInterface|null $restDto
     * @param Entity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface | DTO | null $restDto,
        EntityInterface | Entity $entity,
        string $transactionId
    ): bool {
        $result = $this->repository->findOneBy(
            [
                'nome' => $restDto->getNome(),
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
            ]
        );

        if ($result && $result->getId() != $restDto->getId()) {
            $this->rulesTranslate->throwException('grupoContato', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

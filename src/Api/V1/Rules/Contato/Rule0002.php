<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Contato;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Contato as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Contato as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ContatoRepository as Repository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Valida se o registro já foi cadastrado
 * @classeSwagger=Rule0001
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     * @param Repository     $repository
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private Repository $repository
    ) {
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
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
        $contato = $this->repository->findOneBy(
            [
                'grupoContato' => $restDto->getGrupoContato(),
                'tipoContato' => $restDto->getTipoContato(),
                'unidade' => $restDto->getUnidade(),
                'setor' => $restDto->getSetor(),
                'usuario' => $restDto->getUsuario(),
            ]
        );
        if ($contato) {
            $this->rulesTranslate->throwException('contato', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

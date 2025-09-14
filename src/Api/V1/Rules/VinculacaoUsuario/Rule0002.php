<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoUsuario/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoUsuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Este usuário já está vinculado!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                VinculacaoUsuarioRepository $vinculacaoUsuarioRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoUsuario::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoUsuario|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado($restDto->getUsuario()->getId(), $restDto->getUsuarioVinculado()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('vinculacaoUsuario', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}

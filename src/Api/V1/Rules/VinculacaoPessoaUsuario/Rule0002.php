<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoPessoaUsuario/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoPessoaUsuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Este usuário já está vinculado a pessoa!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoPessoaUsuarioRepository = $vinculacaoPessoaUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoPessoaUsuario::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoPessoaUsuario|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoPessoaUsuarioRepository->findByPessoaAndUsuarioVinculado(
            $restDto->getPessoa()->getId(), $restDto->getUsuarioVinculado()->getId()
        );
        if ($result) {
            $this->rulesTranslate->throwException('vinculacaoPessoaUsuario', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}

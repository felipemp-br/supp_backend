<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=O NUP está em tramitação externa! Não é possível abrir uma tarefa antes do recebimento da tramitação!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TramitacaoResource $tramitacaoResource;

    private TransactionManager $transactionManager;
    private TokenStorageInterface $tokenStorage;
    private VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TramitacaoResource $tramitacaoResource,
        TransactionManager $transactionManager,
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->transactionManager = $transactionManager;
        $this->vinculacaoPessoaUsuarioResource = $vinculacaoPessoaUsuarioResource;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tarefa|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        if($restDto->getProcesso()->getId()){
            $tramitacaoPendente = $this->tramitacaoResource->getRepository()
                ->findTramitacaoPendentePorProcesso($restDto->getProcesso()->getId());

            if ($tramitacaoPendente) {
                if (in_array(
                    'ROLE_PESSOA_VINCULADA_CONVENIADA',
                    $this->tokenStorage->getToken()->getRoleNames()
                )) {
                    $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
                        ->findOneBy([
                            'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                            'pessoa' => $tramitacaoPendente->getPessoaDestino(),
                        ]);

                    if ($vinculacaoUsuario) {
                        $this->transactionManager->addContext(
                            new Context(
                                'pessoaVinculadaConveniada',
                                true
                            ),
                            $transactionId
                        );

                        return true;
                    }
                }
                $this->rulesTranslate->throwException('tarefa', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

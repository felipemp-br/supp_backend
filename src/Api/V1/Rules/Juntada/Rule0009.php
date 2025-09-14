<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
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
 * Class Rule0009.
 *
 * @descSwagger=O NUP está em tramitação para um órgão externo e não pode receber juntadas!
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TramitacaoResource $tramitacaoResource;

    private TransactionManager $transactionManager;
    private TokenStorageInterface $tokenStorage;
    private VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    /**
     * Rule0009 constructor.
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
            Juntada::class => [
                'beforeCreate',
                'skipWhenCommand'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        if($restDto->getVolume()->getProcesso()->getId()){
            $result = $this->tramitacaoResource->getRepository()
                ->findTramitacaoPendentePorProcesso($restDto->getVolume()->getProcesso()->getId());

            if ($result) {
                if ($this->tokenStorage->getToken() &&
                    in_array(
                    'ROLE_PESSOA_VINCULADA_CONVENIADA',
                    $this->tokenStorage->getToken()->getRoleNames()
                )) {

                    $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
                        ->findOneBy([
                            'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                            'pessoa' => $result->getPessoaDestino(),
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
                $this->rulesTranslate->throwException('juntada', '0009');
            }
        }
        return true;
    }

    public function getOrder(): int
    {
        return 9;
    }
}

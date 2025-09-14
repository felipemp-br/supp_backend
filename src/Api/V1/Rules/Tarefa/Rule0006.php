<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * Class Rule0006.
 *
 * @descSwagger  =A Unidade foi configurada para receber tarefas de outras unidades apenas pelo protocolo!
 * @classeSwagger=Rule0006
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AclProviderInterface $aclProvider;

    /**
     * Rule0006 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AclProviderInterface $aclProvider,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->aclProvider = $aclProvider;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
                'skipWhenCommand',
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
        $acessoRestrito = false;

        if ($restDto->getProcesso()->getId()) {
            try {
                $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($restDto->getProcesso()));
                $pemissionMap = new BasicPermissionMap();
                $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );
            } catch (Throwable $e) {
                $acessoRestrito = true;
            }
            if (!$acessoRestrito &&
                $restDto->getProcesso()->getClassificacao() && $restDto->getProcesso()->getClassificacao()->getId()) {
                try {
                    $acl = $this->aclProvider->findAcl(
                        ObjectIdentity::fromDomainObject($restDto->getProcesso()->getClassificacao())
                    );
                    $pemissionMap = new BasicPermissionMap();
                    $acl->isGranted(
                        $pemissionMap->getMasks('MASTER', null),
                        [new RoleSecurityIdentity('ROLE_USER')]
                    );
                } catch (Throwable $e) {
                    $acessoRestrito = true;
                }
            }
        }

        if (($this->parameterBag->get('constantes.entidades.especie_setor.const_2') !== $restDto->getSetorResponsavel()->getEspecieSetor()->getNome()) &&
            $restDto->getSetorResponsavel()->getUnidade()->getApenasProtocolo() &&
            !$acessoRestrito &&
            ($restDto->getSetorOrigem() &&
                ($restDto->getSetorOrigem()->getUnidade()->getId() !==
                    $restDto->getSetorResponsavel()->getUnidade()->getId())) &&
            ($this->parameterBag->get('constantes.entidades.especie_setor.const_1') !== $restDto->getSetorResponsavel()->getEspecieSetor()->getNome())
        ) {
            $this->rulesTranslate->throwException('tarefa', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}

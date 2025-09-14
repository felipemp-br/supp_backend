<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ParametroAdministrativo/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ParametroAdministrativo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\CoordenadorRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\ParametroAdministrativoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger  =Valida se o usuário tem permissão para excluir editar e alterar.
 * @classeSwagger=Rule0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ParametroAdministrativoRepository $dominioAdministrativoRepository,
        private readonly CoordenadorRepository $coordenadorRepository,
        private readonly LotacaoRepository $lotacaoRepository,
    ) {
    }

    public function supports(): array
    {
        return [
            ParametroAdministrativo::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforeDelete',
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
        $user = $this->tokenStorage->getToken()->getUser();

        $lotacaoUser = $this->lotacaoRepository->findOneBy(['colaborador' => $user->getColaborador()->getId()]);
        $modalidadeOrgaoCentralUser = $lotacaoUser->getSetor()->getUnidade()->getModalidadeOrgaoCentral();

        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            if (!$entity->getId()) {
                if ($restDto->getDominioAdministrativo()?->getModOrgaoCentral()?->getId(
                    ) && $restDto->getDominioAdministrativo()->getModOrgaoCentral()->getId(
                    ) != $modalidadeOrgaoCentralUser->getId()) {
                    $this->rulesTranslate->throwException('parametroAdministrativo', '0003');
                }
            } else {
                if ($restDto->getDominioAdministrativo()?->getModOrgaoCentral()?->getId() &&
                    ($restDto->getDominioAdministrativo()->getModOrgaoCentral()->getId(
                        ) != $modalidadeOrgaoCentralUser->getId() ||
                        $entity->getDominioAdministrativo()->getModOrgaoCentral()->getId(
                        ) != $modalidadeOrgaoCentralUser->getId())
                ) {
                    $this->rulesTranslate->throwException('parametroAdministrativo', '0002');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

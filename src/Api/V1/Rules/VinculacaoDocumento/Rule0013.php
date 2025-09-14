<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoDocumento/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\AreaTrabalho;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\AreaTrabalhoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0013.
 *
 * @descSwagger=O usuário deve ser o dono da minuta para fazer a vinculação!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AreaTrabalhoRepository $areaTrabalhoRepository;

    private TokenStorageInterface $tokenStorage;

    /**
     * Rule0013 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                AreaTrabalhoRepository $areaTrabalhoRepository,
                                TokenStorageInterface $tokenStorage)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->areaTrabalhoRepository = $areaTrabalhoRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /** @var AreaTrabalho $areaTrabalhoDono */
        //$areaTrabalhoDono = $this->areaTrabalhoRepository->findAreaTrabalhoDono($restDto->getDocumento()->getId());
        //if ($areaTrabalhoDono) {
        //    if (!$restDto->getDocumento()->getJuntadaAtual() &&
        //        $areaTrabalhoDono->getUsuario()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
        //        $this->rulesTranslate->throwException('vinculacaoDocumento', '0013');
        //    }
        //}

        return true;
    }

    public function getOrder(): int
    {
        return 13;
    }
}

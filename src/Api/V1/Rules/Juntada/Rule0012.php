<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\AreaTrabalhoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0012.
 *
 * @descSwagger=Apenas o dono da minuta pode realizar juntadas!
 * @classeSwagger=Rule0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AreaTrabalhoRepository $areaTrabalhoRepository;

    private TokenStorageInterface $tokenStorage;

    /**
     * Rule0012 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AreaTrabalhoRepository $areaTrabalhoRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->areaTrabalhoRepository = $areaTrabalhoRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Juntada|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        //$result = $this->areaTrabalhoRepository->findAreaTrabalhoDono($restDto->getDocumento()->getId());
        //if ($result && $result->getUsuario()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
        //    $this->rulesTranslate->throwException('juntada', '0012');
        //}

        return true;
    }

    public function getOrder(): int
    {
        return 12;
    }
}

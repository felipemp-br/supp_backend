<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ModalidadeOrgaoCentral/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ModalidadeOrgaoCentral;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Repository\ModalidadeOrgaoCentralRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Órgão Central que tem filhos ativos não pode ser inativado!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private ModalidadeOrgaoCentralRepository $modalidadeOrgaoCentralRepository;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ModalidadeOrgaoCentralRepository $modalidadeOrgaoCentralRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->modalidadeOrgaoCentralRepository = $modalidadeOrgaoCentralRepository;
    }

    public function supports(): array
    {
        return [
            ModalidadeOrgaoCentralDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ModalidadeOrgaoCentralDTO|RestDtoInterface|null $restDto
     * @param ModalidadeOrgaoCentral|EntityInterface          $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getAtivo() && !$entity->getAtivo()) {
            $countFilhosAtivos = $this->modalidadeOrgaoCentralRepository->countFilhosAtivos($entity->getId());
            if ($countFilhosAtivos > 0) {
                $this->rulesTranslate->throwException('ModalidadeOrgaoCentral', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

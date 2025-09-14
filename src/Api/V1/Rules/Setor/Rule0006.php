<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Setor/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Setor;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006.
 *
 * @descSwagger  =Setor não pode ser inativado pois contém Processos/Documentos Avulsos!
 * @classeSwagger=Rule0006
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private ProcessoRepository $processoRepository;

    /**
     * Rule0006 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ProcessoRepository $processoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->processoRepository = $processoRepository;
    }

    public function supports(): array
    {
        return [
            Setor::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Setor|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getAtivo() && $entity->getAtivo()) {
            $impedeInativar = $this->processoRepository->findImpedeInativacao($entity->getId());
            if ($impedeInativar) {
                $this->rulesTranslate->throwException('setor', '0006');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}

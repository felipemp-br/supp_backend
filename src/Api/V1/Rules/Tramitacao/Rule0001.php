<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=NUP já se encontra em Tramitação!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ProcessoRepository $processoRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                ProcessoRepository $processoRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->processoRepository = $processoRepository;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Tramitacao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tramitacao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->processoRepository->findProcessoEmTramitacao($restDto->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('tramitacao', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Processos eletrônicos com componentes digitais juntados não podem ser convertidos em eletrônicos!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ProcessoRepository $processoRepository,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        if (($this->parameterBag->get('constantes.entidades.modalidade_meio.const_2')
                === $entity->getModalidadeMeio()->getValor())
            && ($this->parameterBag->get('constantes.entidades.modalidade_meio.const_1')
                === $restDto->getModalidadeMeio()?->getValor())
            && (true === $this->processoRepository->hasComponenteDigital($entity->getId()))
        ) {
            $this->rulesTranslate->throwException('processo', '0003');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }
}

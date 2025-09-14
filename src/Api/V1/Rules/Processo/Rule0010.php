<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0010.
 *
 * @descSwagger=A data de desarquivamento automático só pode ser agendada no setor ARQUIVO!
 * @classeSwagger=Rule0010
 *
 * @author Eduardo Romão <eduardo.romao@agu.gov.br>
 */
class Rule0010 implements RuleInterface
{
    /**
     * Rule0010 constructor.
     */
    public function __construct(
        protected readonly RulesTranslate $rulesTranslate,
        protected readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            ProcessoDto::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ProcessoDto|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        ProcessoDto|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if (($restDto->getDataHoraDesarquivamento() !== $entity->getDataHoraDesarquivamento())
            && ($this->parameterBag->get('constantes.entidades.especie_setor.const_2') ===
                $entity->getSetorAtual()->getEspecieSetor()?->getNome())
        ) {
            $this->rulesTranslate->throwException('processo', '0010');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 10;
    }
}

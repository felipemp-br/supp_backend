<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0009.
 *
 * @descSwagger=A data de desarquivamento automático deve ser maior que a data atual!
 *
 * @classeSwagger=Rule0009
 *
 * @author Eduardo Romão <eduardo.romao@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    /**
     * Rule0009 constructor.
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
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        $dataAtual = new DateTime();

        if ($restDto->getDataHoraDesarquivamento()
            && $restDto->getDataHoraDesarquivamento() <= $dataAtual
            && ($this->parameterBag->get('constantes.entidades.especie_setor.const_2') ===
                $restDto->getSetorAtual()?->getEspecieSetor()?->getNome())
        ) {
            $this->rulesTranslate->throwException('processo', '0009');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 9;
    }
}

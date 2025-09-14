<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0020.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0020.
 *
 * @descSwagger=Um processo de meio FÍSICO não pode ser arquivado pelo atalho de encaminhamento
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0020 implements RuleInterface
{
    /**
     * Rule0020 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
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
                'beforeArquivar',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        ProcessoDTO|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($restDto->getModalidadeMeio()?->getValor() ===
            $this->parameterBag->get('constantes.entidades.modalidade_meio.const_2')
        ) {
            $this->rulesTranslate->throwException('processo', '0021');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 20;
    }
}

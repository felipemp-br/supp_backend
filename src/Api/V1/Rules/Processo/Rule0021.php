<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0021.php.
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
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0021.
 *
 * @descSwagger=Um processo não pode ser transferido diretamente para o arquivo sem ser arquivado
 *
 * @classeSwagger=Rule0021
 */
class Rule0021 implements RuleInterface
{
    /**
     * Rule0021 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TransactionManager $transactionManager
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
        if ($this->transactionManager->getContext('transferirAcervo', $transactionId)
            && ($entity->getSetorAtual()->getEspecieSetor()?->getNome() ===
                $this->parameterBag->get('constantes.entidades.especie_setor.const_2')
                || $restDto->getSetorAtual()?->getEspecieSetor()?->getNome() ===
                $this->parameterBag->get('constantes.entidades.especie_setor.const_2'))
            && $entity->getSetorAtual()->getEspecieSetor()?->getNome()
            !== $restDto->getSetorAtual()?->getEspecieSetor()?->getNome()
        ) {
            $this->rulesTranslate->throwException('processo', '0022');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 21;
    }
}

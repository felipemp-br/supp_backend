<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0012.
 *
 * @descSwagger=Os Processos/Documentos Avulsos não possuem os mesmos interessados!
 * @classeSwagger=Rule0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TransactionManager $transactionManager;

    /**
     * Rule0012 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            VinculacaoProcesso::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoProcesso|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        if ($this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2') === $restDto->getModalidadeVinculacaoProcesso()->getValor()) {
            $a1 = [];
            $a2 = [];
            foreach ($restDto->getProcesso()->getInteressados() as $interessado) {
                $a1[] = $interessado->getPessoa()->getId();
            }
            foreach ($restDto->getProcessoVinculado()->getInteressados() as $interessado) {
                $a2[] = $interessado->getPessoa()->getId();
            }
            sort($a1);
            sort($a2);
            if ($a1 != $a2) {
                $this->rulesTranslate->throwException('vinculacaoProcesso', '0012');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 12;
    }
}

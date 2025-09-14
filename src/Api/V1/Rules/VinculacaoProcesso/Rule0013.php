<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0013.php.
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
 * Class Rule0013.
 *
 * @descSwagger=Os Processos/Documentos Avulsos não possuem os mesmos assuntos!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0013 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        private ParameterBagInterface $parameterBag,
        private TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
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
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        if ($this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2')
            === $restDto->getModalidadeVinculacaoProcesso()->getValor()) {
            $a1 = [];
            $a2 = [];
            foreach ($restDto->getProcesso()->getAssuntos() as $assunto) {
                $a1[] = $assunto->getAssuntoAdministrativo()->getId();
            }
            foreach ($restDto->getProcessoVinculado()->getAssuntos() as $assunto) {
                $a2[] = $assunto->getAssuntoAdministrativo()->getId();
            }
            sort($a1);
            sort($a2);
            if ($a1 != $a2) {
                $this->rulesTranslate->throwException('vinculacaoProcesso', '0013');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 13;
    }
}

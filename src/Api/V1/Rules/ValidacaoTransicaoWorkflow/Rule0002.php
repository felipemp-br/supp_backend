<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ValidacaoTransicaoWorkflow/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ValidacaoTransicaoWorkflow;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ValidacaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se a criação da Atividade atende as validações definidas na transição do workflow!
 * @classeSwagger=Rule0002
 */
class Rule0002 implements RuleInterface
{

    /**
     * Rule0001 constructor.
     */
    public function __construct(
                                private RulesTranslate $rulesTranslate,
                                private ValidacaoTransicaoWorkflowRepository $validacaoTransicaoWorkflowRepository) {
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
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

        $result = $this->validacaoTransicaoWorkflowRepository->getValidacaoTransicaoAtividadeTarefaFrom(
            $restDto->getEspecieAtividade(),
            $restDto->getTarefa()->getEspecieTarefa(),
            $restDto->getTarefa()->getProcesso()->getEspecieProcesso()
        );

        foreach ($result as $validacaoTransicaoWorkflow) {
            $contexto = json_decode($validacaoTransicaoWorkflow->getContexto(), true);

            if (isset($contexto['tipoDocumentoId'])) {
                $arrDocumentos = array_filter(
                    $restDto->getDocumentos()->toArray(),
                    fn ($documento) => $documento->getTipoDocumento()->getId() == $contexto['tipoDocumentoId']
                );

                if (!$arrDocumentos) {
                    $this->rulesTranslate->throwException('validacaoTransicaoWorkflow', '0002-0001');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

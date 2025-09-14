<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0013.
 *
 * @descSwagger=Valida as sugestões.
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{

    /**
     * Rule0013 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'beforeAprovarSugestao',
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
        $acoesSelecionadas = json_decode($restDto->getAcoesExecucaoSugestao());

        if (empty($acoesSelecionadas)) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0013a');
        }

        $acoesEtiqueta = array_map(fn($acao) => $acao->getId(), $entity->getEtiqueta()->getAcoes()->toArray());

        foreach ($acoesSelecionadas as $acaoId) {
            if (!in_array($acaoId, $acoesEtiqueta)) {
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0013b');
            }
        }

        switch ($entity->getEtiqueta()->getTipoExecucaoAcaoSugestao()) {
            case Etiqueta::TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_UNICA:
                if (count($acoesSelecionadas) !== 1) {
                    $this->rulesTranslate->throwException('vinculacao_etiqueta', '0013c');
                }
                break;
            case Etiqueta::TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_TODOS:
                if (count($acoesSelecionadas) !== count($acoesEtiqueta)) {
                    $this->rulesTranslate->throwException('vinculacao_etiqueta', '0013d');
                }
                break;
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

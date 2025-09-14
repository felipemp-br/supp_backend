<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007.
 *
 * @descSwagger=Atividades relacionadas à solicitação automatizada devem seguir as regras definidas.
 *
 * @classeSwagger=Rule0007
 */
class Rule0007 implements RuleInterface
{
    /**
     * @param RulesTranslate                  $rulesTranslate
     * @param SuppParameterBag                $parameterBag
     * @param SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly SuppParameterBag $parameterBag,
        private readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
    ) {
    }

    public function supports(): array
    {
        return [
            AtividadeDTO::class => [
                'assertCreate',
            ],
        ];
    }

    /**
     * @param AtividadeDTO|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface    $entity
     * @param string                             $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(AtividadeDTO|RestDtoInterface|null $restDto, AtividadeEntity|EntityInterface $entity, string $transactionId): bool
    {
        $solicitacaoAutomatizada = $this->solicitacaoAutomatizadaResource->findOneBy(
            ['processo' => $restDto->getTarefa()->getProcesso()]
        );

        if (!$solicitacaoAutomatizada) {
            return true;
        }

        if (!$this->parameterBag->has('supp_core.administrativo_backend.solicitacao_automatizada')) {
            $this->rulesTranslate->throwException('atividade', '0007a');
        }
        $config = $this->parameterBag->get('supp_core.administrativo_backend.solicitacao_automatizada');
        $nomeEspecieAtividade = $restDto->getEspecieAtividade()->getNome();

        if ($solicitacaoAutomatizada->getTarefaAnalise()?->getId() === $restDto->getTarefa()->getId()) {
            $especieAtividadesPermitidas = [
                $config['especie_atividade_deferimento'],
                $config['especie_atividade_indeferimento']
            ];
            if (!in_array($nomeEspecieAtividade, $especieAtividadesPermitidas)) {
                $this->rulesTranslate->throwException(
                    'atividade',
                    '0007b',
                    [
                        $config['especie_atividade_deferimento'],
                        $config['especie_atividade_indeferimento']
                    ]
                );
            }

            $tipoDocumentoAutorizado = $config['tipo_documento'];
            $documentoSelecionado = null;
            if (!$restDto->getDocumentos()->isEmpty()) {
                /** @var Documento $documento */
                foreach ($restDto->getDocumentos() as $documento) {
                    if ($documento->getTipoDocumento()->getSigla() === $tipoDocumentoAutorizado) {
                        $documentoSelecionado = $documento;
                    }
                }
                if (!$documentoSelecionado) {
                    $this->rulesTranslate->throwException('atividade', '0007c');
                }
            }
        }
        if (
            $solicitacaoAutomatizada->getTarefaAcompanhamentoCumprimento()?->getId() === $restDto->getTarefa()->getId()
            && $nomeEspecieAtividade !== $config['especie_atividade_acompanhamento_cumprimento']
        ) {
            $this->rulesTranslate->throwException(
                'atividade',
                '0007e',
                [
                    $config['especie_atividade_acompanhamento_cumprimento']
                ]
            );
        }
        if (
            $solicitacaoAutomatizada->getStatus() === StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO
            && $restDto->getTarefa()->getEspecieTarefa()->getNome() === $config['especie_tarefa_erro_solicitacao']
            && $restDto->getTarefa()->getId() !== $solicitacaoAutomatizada->getTarefaDadosCumprimento()?->getId()
            && $nomeEspecieAtividade !== $config['especie_atividade_erro_solicitacao']
        ) {
            $this->rulesTranslate->throwException(
                'atividade',
                '0007e',
                [
                    $config['especie_atividade_erro_solicitacao']
                ]
            );
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}

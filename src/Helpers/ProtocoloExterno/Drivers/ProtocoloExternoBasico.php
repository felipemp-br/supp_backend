<?php

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/Drivers/ProtocoloExternoBasico.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\Drivers;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoAdministrativoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\DadosProtocoloExterno;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExterno;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;

/**
 * Class ProtocoloExternoBasico.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProtocoloExternoBasico extends ProtocoloExterno
{
    /**
     * @param ClassificacaoResource         $classificacaoResource
     * @param SuppParameterBag              $suppParameterBag
     * @param EspecieTarefaResource         $especieTarefaResource
     * @param EspecieProcessoResource       $especieProcessoResource
     * @param AssuntoAdministrativoResource $assuntoAdministrativoResource
     * @param EtiquetaResource              $etiquetaResource
     * @param ModalidadeMeioResource        $modalidadeMeioResource
     * @param ModalidadeEtiquetaResource    $modalidadeEtiquetaResource
     * @param SetorResource                 $setorResource
     */
    public function __construct(
        private readonly ClassificacaoResource $classificacaoResource,
        private readonly SuppParameterBag $suppParameterBag,
        private readonly EspecieTarefaResource $especieTarefaResource,
        private readonly EspecieProcessoResource $especieProcessoResource,
        private readonly AssuntoAdministrativoResource $assuntoAdministrativoResource,
        private readonly EtiquetaResource $etiquetaResource,
        private readonly ModalidadeMeioResource $modalidadeMeioResource,
        private readonly ModalidadeEtiquetaResource $modalidadeEtiquetaResource,
        private readonly SetorResource $setorResource
    ) {
    }

    /**
     * @param FormularioEntity $formulario
     * @param ProcessoEntity   $processoEntity
     * @param ProcessoDTO      $processoDTO
     *
     * @return DadosProtocoloExterno|null
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getDadosProtocoloExterno(
        FormularioEntity $formulario,
        ProcessoEntity $processoEntity,
        ProcessoDTO $processoDTO
    ): ?DadosProtocoloExterno {
        $configFormulario = $this->getConfigFormulario($formulario);

        if (!$configFormulario) {
            return null;
        }

        $dadosProcessoTarefa = new DadosProtocoloExterno();

        $classificacaoEntity = $this
            ->classificacaoResource
            ->getRepository()
            ->findOneBy(['codigo' => $configFormulario['codigo_classificacao_processo']]);

        if (!$classificacaoEntity) {
            return null;
        }

        $especieProcessoEntity = $this
            ->especieProcessoResource
            ->getRepository()
            ->findOneBy(['nome' => $configFormulario['nome_especie_processo']]);

        if (!$especieProcessoEntity) {
            return null;
        }

        if ($configFormulario['nome_especie_tarefa']) {
            $especieTarefaEntity = $this
                ->especieTarefaResource
                ->getRepository()
                ->findOneBy(['nome' => $configFormulario['nome_especie_tarefa']]);

            if (!$especieTarefaEntity) {
                return null;
            }

            $dadosProcessoTarefa
                ->setEspecieTarefa($especieTarefaEntity)
                ->setPostItTarefa($configFormulario['post_it_tarefa'] ?? '')
                ->setObservacaoTarefa($configFormulario['observacao_tarefa'] ?? '');
        }

        $lembretesProcesso = [];
        foreach ($configFormulario['lembretes_processo'] as $lembrete) {
            $lembretesProcesso[] = $lembrete;
        }

        $assuntosAdministrativoProcesso = [];
        foreach ($configFormulario['assuntos_processo'] as $cfgAssuntoProcesso) {
            $assuntoProcesso = $this->assuntoAdministrativoResource->getRepository()->findOneBy(
                ['nome' => $cfgAssuntoProcesso['nome_assunto']]
            );

            if ($assuntoProcesso) {
                if (!$cfgAssuntoProcesso['principal']) {
                    $assuntosAdministrativoProcesso[] = $assuntoProcesso;
                } else {
                    array_unshift($assuntosAdministrativoProcesso, $assuntoProcesso);
                }
            }
        }

        $modEtiquetaProcesso = $this->modalidadeEtiquetaResource->getRepository()->findOneBy(['valor' => 'PROCESSO']);
        $etiquetasProcesso = [];
        foreach ($configFormulario['etiquetas_processo'] as $cfgEtiquetaProcesso) {
            $etiquetaProcesso = $this->etiquetaResource->getRepository()->findOneBy(
                [
                    'nome' => $cfgEtiquetaProcesso,
                    'modalidadeEtiqueta' => $modEtiquetaProcesso,
                ]
            );

            if ($etiquetaProcesso) {
                $etiquetasProcesso[] = $etiquetaProcesso;
            }
        }

        if (!empty($configFormulario['etiquetas_tarefa'])) {
            $modEtiquetaTarefa = $this->modalidadeEtiquetaResource->getRepository()->findOneBy(['valor' => 'TAREFA']);
            $etiquetasTarefa = [];
            foreach ($configFormulario['etiquetas_tarefa'] as $cfgEtiquetaTarefa) {
                $etiquetaTarefa = $this->etiquetaResource->getRepository()->findOneBy(
                    [
                        'nome' => $cfgEtiquetaTarefa,
                        'modalidadeEtiqueta' => $modEtiquetaTarefa,
                    ]
                );

                if ($etiquetaTarefa) {
                    $etiquetasTarefa[] = $etiquetaTarefa;
                }
            }

            $dadosProcessoTarefa->setEtiquetasTarefa($etiquetasTarefa);
        }

        $modalidadeMeioEntity = $this->modalidadeMeioResource->getRepository()->findOneBy(
            ['valor' => $configFormulario['modalidade_meio']]
        );

        if (!$modalidadeMeioEntity) {
            return null;
        }

        $unidadeEntity = $this->setorResource->getRepository()->findOneBy(
            ['sigla' => $configFormulario['sigla_unidade'], 'parent' => null]
        );

        if (!$unidadeEntity) {
            return null;
        }

        $setorEntity = $this->setorResource->getRepository()->findOneBy(
            [
                'nome' => $configFormulario['nome_setor_unidade'],
                'unidade' => $unidadeEntity,
            ]
        );

        if (!$setorEntity) {
            return null;
        }

        return $dadosProcessoTarefa
            ->setClassificacaoProcesso($classificacaoEntity)
            ->setTituloProcesso($configFormulario['titulo_processo'] ?? '')
            ->setDescricaoProcesso($configFormulario['descricao_processo'] ?? '')
            ->setAssuntosAdministrativoProcesso($assuntosAdministrativoProcesso)
            ->setEtiquetasProcesso($etiquetasProcesso)
            ->setLembretesProcesso($lembretesProcesso)
            ->setEspecieProcesso($especieProcessoEntity)
            ->setModalidadeMeio($modalidadeMeioEntity)
            ->setUnidade($unidadeEntity)
            ->setSetor($setorEntity);
    }

    /**
     * @param FormularioEntity $formulario
     *
     * @return array|null
     */
    private function getConfigFormulario(FormularioEntity $formulario): ?array
    {
        if (!$this->suppParameterBag->has('supp_core.administrativo_backend.formularios')) {
            return null;
        }

        return current(
            array_filter(
                $this->suppParameterBag->get('supp_core.administrativo_backend.formularios') ?? [],
                fn ($c) => ($c['sigla_formulario'] ?? false) === $formulario->getSigla()
            )
        );
    }

    /**
     * @param FormularioEntity $formulario
     *
     * @return bool
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function supports(FormularioEntity $formulario): bool
    {
        return !empty($this->getConfigFormulario($formulario));
    }
}

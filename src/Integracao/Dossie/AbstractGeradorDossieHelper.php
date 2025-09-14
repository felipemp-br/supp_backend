<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Integracao/Dossie/AbstractGeradorDossie.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;

/**
 * Class AbstractGeradorDossie.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractGeradorDossieHelper extends AbstractGeradorDossie
{
    /**
     * @param SuppParameterBag $suppParameterBag
     */
    public function __construct(
        private readonly SuppParameterBag $suppParameterBag
    ) {}

    /**
     * @throws Exception
     */
    protected function getConfig(): array {
        if ($this->getConfigNome()) {
            $configs =  array_filter(
                (array) $this->suppParameterBag->get($this->getConfigNome()),
                fn($config) => ($config['nome_tipo_dossie'] ?? false) === $this->getNomeTipoDossie()
            );

            return
                current($configs) ?:
                    throw new Exception("Configuração para dossiê {$this->getNomeTipoDossie()} não encontrada!");

        }
        throw new Exception("Configuração para dossiê {$this->getNomeTipoDossie()} não encontrada!");
    }

    protected function getConfigNome(): ?string {
        return null;
    }

    /**
     * @return string[]
     * @noinspection PhpMissingParentCallCommonInspection
     * @throws Exception
     */
    public function getParams(): array
    {
        $config = $this->getConfig();
        return [
            'ativo'                      => $this->getStrParam($config['ativo'] ?? 'undefined'),
            'pesquisa_assunto_pai'       => $this->getStrParam($config['pesquisa_assunto_pai'] ?? 'undefined'),
            'num_max_interessados'       => $this->getStrParam($config['num_max_interessados'] ?? 'undefined'),
            'template'                   => $this->getStrParam($config['template'] ?? 'undefined'),
            'tarefas_suportadas'         => $this->getStrParam($config['tarefas_suportadas'] ?? 'undefined'),
            'assuntos_suportados'        => $this->getStrParam($config['assuntos_suportados'] ?? 'undefined'),
            'siglas_unidades_suportadas' => $this->getStrParam($config['siglas_unidades_suportadas'] ?? 'undefined'),
        ];
    }

    /**
     * @param TarefaEntity|TarefaDTO $tarefa
     * @param InteressadoEntity|InteressadoDTO $interessado
     *
     * @return bool
     * @noinspection PhpMissingParentCallCommonInspection
     * @throws Exception
     */
    public function supports(TarefaEntity|TarefaDTO $tarefa, InteressadoEntity|InteressadoDTO $interessado): bool
    {
        return
            $this->isDossieAtivo() &&
            $this->pessoaSuportada($interessado->getPessoa()) &&
            $this->numeroInteressadosProcessoSuportado($tarefa->getProcesso()) &&
            $this->assuntoProcessoSuportado($tarefa->getProcesso()) &&
            $this->tarefaSuportada($tarefa) &&
            $this->unidadeResponsavelTarefaSuportada($tarefa);
    }

    /**
     * @param DossieEntity|DossieDTO $dossie
     * @return bool
     * @noinspection PhpMissingParentCallCommonInspection
     * @throws Exception
     */
    public function supportsSobDemanda(DossieEntity | DossieDTO $dossie): bool
    {
        return
            $this->isDossieAtivo() &&
            $this->pessoaSuportada($dossie->getPessoa()) &&
            $this->unidadeAtualProcessoSuportada($dossie->getProcesso());
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isDossieAtivo(): bool
    {
        $config = $this->getConfig();
        return $config['ativo'] ?? false;
    }

    /**
     * @param PessoaEntity|PessoaDTO|null $pessoa
     *
     * @return bool
     */
    public function pessoaSuportada(PessoaEntity|PessoaDTO|null $pessoa): bool
    {
        return
            (strlen($pessoa?->getNumeroDocumentoPrincipal() ?? '') === 11 ) ||
            (strlen($pessoa?->getNumeroDocumentoPrincipal() ?? '') === 14 );
    }

    /**
     * @param TarefaEntity|TarefaDTO $tarefa
     *
     * @return bool
     * @throws Exception
     */
    public function tarefaSuportada(TarefaEntity|TarefaDTO $tarefa): bool
    {
        $config = $this->getConfig();
        $tarefasSuportadas = $config['tarefas_suportadas'] ?? [];

        if (in_array('*', $tarefasSuportadas, true)) {
            return true;
        }

        return in_array($tarefa->getEspecieTarefa()->getNome(), $tarefasSuportadas, true);
    }

    /**
     * @param ProcessoEntity|ProcessoDTO|null $processo
     * @return bool
     * @throws Exception
     */
    public function numeroInteressadosProcessoSuportado(ProcessoEntity|ProcessoDTO|null $processo): bool
    {
        $config = $this->getConfig();
        if (!isset($config['num_max_interessados'])) {
            return true;
        }

        return ($processo ? $processo->getInteressados()->count() : 0) <= intval($config['num_max_interessados']);
    }

    /**
     * @param ProcessoEntity|ProcessoDTO|null $processo
     * @return bool
     * @throws Exception
     */
    public function assuntoProcessoSuportado(ProcessoEntity|ProcessoDTO|null $processo): bool
    {
        $config = $this->getConfig();
        $pesquisaAssuntoPai = $config['pesquisa_assunto_pai'] ?? false;
        $assuntosSuportados = $config['assuntos_suportados'] ?? [];

        if (in_array('*', $assuntosSuportados, true)) {
            return true;
        }

        /** @var AssuntoEntity $assuntoBase */
        $assuntos = [];
        if($processo) {
            foreach ($processo->getAssuntos() as $assuntoBase) {
                $assuntos[] = $assuntoBase->getAssuntoAdministrativo()->getNome();
                if ($pesquisaAssuntoPai) {
                    $proximoAssunto = $assuntoBase->getAssuntoAdministrativo();
                    /* @noinspection PhpAssignmentInConditionInspection */
                    while ($proximoAssunto = $proximoAssunto->getParent()) {
                        $assuntos[] = $proximoAssunto->getNome();
                    }
                }
            }
        }

        return count(array_intersect($assuntos, $assuntosSuportados)) > 0;
    }

    /**
     * @param TarefaEntity|TarefaDTO $tarefa
     *
     * @return bool
     * @throws Exception
     */
    public function unidadeResponsavelTarefaSuportada(TarefaEntity|TarefaDTO $tarefa): bool
    {
        $config = $this->getConfig();
        $unidadesSuportadas = $config['siglas_unidades_suportadas'] ?? [];
        if (in_array('*', $unidadesSuportadas, true)) {
            return true;
        }

        return in_array($tarefa->getSetorResponsavel()->getUnidade()->getSigla(), $unidadesSuportadas, true);
    }

    /**
     * @param ProcessoEntity|ProcessoDTO|null $processo
     * @return bool
     * @throws Exception
     */
    public function unidadeAtualProcessoSuportada(ProcessoEntity|ProcessoDTO|null $processo): bool
    {
        $config = $this->getConfig();
        $unidadesSuportadas = $config['siglas_unidades_suportadas'] ?? [];
        if (in_array('*', $unidadesSuportadas, true)) {
            return true;
        }

        return in_array($processo?->getSetorAtual()?->getUnidade()->getSigla(), $unidadesSuportadas, true);
    }
}

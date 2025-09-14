<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Tarefa;

use DateInterval;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\FundamentacaoRestricao;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository;
use SuppCore\AdministrativoBackend\Repository\EspecieTarefaRepository;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * Class ProcessoDesarquivamentoCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:administrativo:tarefa:limbo', description: 'Abre tarefas nos NUPs que estao no limbo')]
class TarefaLimboCommand extends Command
{
    use SymfonyStyleTrait;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TarefaRepository $tarefaRepository,
        private readonly ProcessoRepository $processoRepository,
        private readonly TarefaResource $tarefaResource,
        private readonly EspecieTarefaRepository $especieTarefaRepository,
        private readonly AfastamentoRepository $afastamentoRepository,
        private readonly TransactionManager $transactionManager,
        private readonly AfastamentoResource $afastamentoResource,
        private readonly SetorRepository $setorRepository,
        private readonly AclProviderInterface $aclProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'quantidadeDias',
                '-d',
                InputArgument::OPTIONAL,
                'Quantidade de dias para considerar processos no limbo.',
                10
            )
        ;
        parent::configure();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Retorna todos os processos que estão no limbo e cria uma tarefa para dar andamento.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Throwable
     * @throws DateMalformedIntervalStringException
     * @throws DateMalformedStringException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $processos = $this->processoRepository->findProcessosAbertosLimbo((int)$input->getOption('quantidadeDias'));

        $outputCode = Command::SUCCESS;
        $totalTarefasAbertas = 0;

        try {
            foreach ($processos as $processo) {
                $transactionId = $this->transactionManager->begin();

                $dataAtual = new DateTime();
                $finalPrazo = (clone $dataAtual)->add(new DateInterval('P5D'));

                $tarefaDTO = new Tarefa();
                $tarefaDTO->setEspecieTarefa(
                    $this->especieTarefaRepository->findByNomeAndGenero(
                        'DAR ANDAMENTO AO PROCESSO',
                        'ADMINISTRATIVO'
                    )
                );
                $tarefaDTO->setDataHoraInicioPrazo($dataAtual);
                $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);
                $tarefaDTO->setProcesso($processo);

                $this->incluirResponsavelPelaNovaTarefa($tarefaDTO, $processo, $finalPrazo);

                $this->tarefaResource->create($tarefaDTO, $transactionId);

                $this->transactionManager->commit($transactionId);
                ++$totalTarefasAbertas;
            }
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
            throw $t;
        }

        if ($input->isInteractive()) {
            $io->success($message ?? 'Total de '.$totalTarefasAbertas.' tarefa(s) aberta(s).');
        }

        return $outputCode;
    }

    /**
     * Inclui um responsável pela nova tarefa através das tentativas abaixo:
     *      1ª tentativa - Setor que tenha acesso para editar um processo restrito.
     *      2ª tentativa - Colaborador que tenha acesso para editar um processo restrito e que não esteja afastado.
     *      3ª tentativa - Responsável pela última tarefa que não esteja afastado.
     *      4ª tentativa - Coordenador do setor da última tarefa que não esteja afastado.
     *      5ª tentativa - Protocolo unidade do setor do processo informado.
     *
     * @throws Throwable
     */
    private function incluirResponsavelPelaNovaTarefa(
        Tarefa $tarefaDto,
        Processo $processo,
        DateTime $finalPrazo
    ): void {
        if ($processo->getFundamentacoesRestricao()) {
            if ($this->incluirSetorComAcessoNoProcesso($tarefaDto, $processo)) {
                return;
            }

            if ($this->incluiColaboradorComAcessoNoProcesso($tarefaDto, $processo, $finalPrazo)) {
                return;
            }
        }

        if ($this->tarefaRepository->findTarefasFechadasLimboByProcesso($processo->getId())) {
            $tarefa = $this->tarefaRepository->findTarefasFechadasLimboByProcesso($processo->getId());
            /* @var Tarefa $tarefaDtoOld */
            $tarefaDtoOld = $this->tarefaResource->getDtoForEntity(
                $tarefa[0]->getId(),
                Tarefa::class,
                null,
                $tarefa[0]
            );
            if ($this->incluiResponsavelPelaUltimaTarefa($tarefaDto, $tarefaDtoOld, $finalPrazo)) {
                return;
            }

            if ($this->incluiCoordenadorSetor($tarefaDto, $tarefaDtoOld, $finalPrazo)) {
                return;
            }
        }

        $setor = $this->setorRepository->findProtocoloInUnidade(
            $processo->getSetorAtual()->getUnidade()->getId()
        );
        $tarefaDto->setSetorResponsavel($setor);
    }

    /**
     * Inclui o primeiro setor encontrado com acesso para editar o processo informado.
     *
     * @throws Throwable
     */
    private function incluirSetorComAcessoNoProcesso(
        Tarefa $tarefaDto,
        Processo $processo
    ): bool {
        foreach ($processo->getFundamentacoesRestricao()->filter(
            fn(FundamentacaoRestricao $fr) => null !== $fr->getSetor()
        ) as $fundamentacao) {
            if ($this->temPermissaoParaEditarProcesso(
                $processo,
                new RoleSecurityIdentity('ACL_SETOR_'.$fundamentacao->getSetor()->getId())
            )) {
                $tarefaDto->setSetorResponsavel($fundamentacao->getSetor());
                return true;
            }
        }
        return false;
    }

    /**
     * Inclui o primeiro colaborador com acesso para editar o processo informado, desde que não esteja afastado.
     * O setor da lotação principal do colaborador será adicionado como setor responsável.
     *
     * @throws Throwable
    */
    private function incluiColaboradorComAcessoNoProcesso(
        Tarefa $tarefaDto,
        Processo $processo,
        DateTime $finalPrazo,
    ): bool {
        foreach ($processo->getFundamentacoesRestricao()->filter(
            fn(FundamentacaoRestricao $fr) => null !== $fr->getUsuario()
        ) as $fundamentacao) {
            $temAfastamento = $this->afastamentoRepository->findAfastamento(
                $fundamentacao->getUsuario()->getColaborador()->getId(),
                $finalPrazo
            );

            if (!$temAfastamento && $this->temPermissaoParaEditarProcesso(
                $processo,
                new UserSecurityIdentity(
                    $fundamentacao->getUsuario()->getUsername(),
                    Usuario::class
                )
            )) {
                $lotacaoPrincipal = $fundamentacao->getUsuario()->getColaborador()->getLotacoes()
                    ->filter(
                        fn(Lotacao $lotacao) => $lotacao->getPrincipal() === true
                    )->first();
                $tarefaDto->setSetorResponsavel($lotacaoPrincipal->getSetor());
                $tarefaDto->setUsuarioResponsavel($fundamentacao->getUsuario());
                return true;
            }
        }
        return false;
    }

    /**
     * Inclui o responsável pela última tarefa, caso ele não esteja afastado.
     *
     * @throws Throwable
     */
    private function incluiResponsavelPelaUltimaTarefa(
        Tarefa $tarefaDto,
        Tarefa $tarefaDtoOld,
        DateTime $finalPrazo,
    ):bool {
        $temAfastamento = $this->afastamentoRepository->findAfastamento(
            $tarefaDtoOld->getUsuarioResponsavel()->getColaborador()->getId(),
            $finalPrazo
        );

        if (!$temAfastamento) {
            $tarefaDto->setSetorResponsavel($tarefaDtoOld->getSetorResponsavel());
            $tarefaDto->setUsuarioResponsavel($tarefaDtoOld->getUsuarioResponsavel());
            return true;
        }
        return false;
    }

    /**
     * Inclui um coordenador do setor da última tarefa, caso ele não esteja afastado.
     */
    private function incluiCoordenadorSetor(
        Tarefa $tarefaDto,
        Tarefa $tarefaDtoOld,
        DateTime $finalPrazo,
    ):bool {
        $usuariosCoord = $this->tarefaResource->retornaUsuariosRegraDistribuicao(
            $tarefaDtoOld,
            TarefaResource::REGRA_DISTRIBUICAO_APENAS_COORDENADORES
        );
        $usuariosCoord = $this->afastamentoResource->limpaListaUsuariosAfastados(
            $usuariosCoord,
            $finalPrazo
        );

        if (!empty($usuariosCoord)) {
            foreach ($usuariosCoord as $usuarioCoord) {
                $tarefaDto->setSetorResponsavel($tarefaDtoOld->getSetorResponsavel());
                $tarefaDto->setUsuarioResponsavel($usuarioCoord);
                return true;
            }
        }
        return false;
    }

    /**
     * Verifica se a securityIdentity informada tem acesso para editar o processo especificado.
     */
    private function temPermissaoParaEditarProcesso(Processo $processo, $securityIdentity):bool
    {
        try {
            $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($processo));
            $permissionMap = new BasicPermissionMap();

            return $acl->isGranted(
                $permissionMap->getMasks('EDIT', null),
                [$securityIdentity]
            );
        } catch (Throwable) {
            return false;
        }
    }
}

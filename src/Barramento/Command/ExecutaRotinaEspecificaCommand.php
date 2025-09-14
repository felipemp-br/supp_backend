<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Command;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use ReflectionException;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoClient;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaComponenteDigital;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaDocumentoAvulso;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaProcesso;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoRecebeComponenteDigital;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoRecebeTramite;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Comando responsável por realizar um processamento específico no Barramento PEN.
 */
#[AsCommand(
    name: 'supp:barramento:rotinaEspecifica',
    description: 'Realiza o processamento de uma rotina específica no barramento (é preciso configurar no arquivo.'
)]
class ExecutaRotinaEspecificaCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * @param TransactionManager                $transactionManager
     * @param BarramentoRecebeTramite           $barramentoRecebeTramite
     * @param BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital
     * @param BarramentoEnviaComponenteDigital  $barramentoEnviaComponenteDigital
     * @param BarramentoEnviaDocumentoAvulso    $barramentoEnviaDocumentoAvulso
     * @param BarramentoClient                  $barramentoClient
     * @param BarramentoEnviaProcesso           $barramentoEnvioProcesso
     * @param ParameterBagInterface             $parameterBag
     */
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly BarramentoRecebeTramite $barramentoRecebeTramite,
        private readonly BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital,
        private readonly BarramentoEnviaComponenteDigital $barramentoEnviaComponenteDigital,
        private readonly BarramentoEnviaDocumentoAvulso $barramentoEnviaDocumentoAvulso,
        private readonly BarramentoClient $barramentoClient,
        private readonly BarramentoEnviaProcesso $barramentoEnvioProcesso,
        private readonly ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
    }

    /**
     * Método de configuração dos parâmetros esperados.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption(
            'rotina',
            null,
            InputOption::VALUE_REQUIRED,
            'rotina específica'
        )->addOption(
            'idt',
            null,
            InputOption::VALUE_REQUIRED,
            'nro do idt'
        )->addOption(
            'documentoAvulsoId',
            null,
            InputOption::VALUE_OPTIONAL,
            'nro do documentoAvulsoId'
        )->addOption(
            'tramitacaoUuid',
            null,
            InputOption::VALUE_OPTIONAL,
            'nro do tramitacaoUuid'
        )->addOption(
            'repositorioEstruturasDestinatarioId',
            null,
            InputOption::VALUE_OPTIONAL,
            'nro do repositorioEstruturasDestinatarioId'
        )->addOption(
            'estruturaDestinatarioId',
            null,
            InputOption::VALUE_OPTIONAL,
            'nro do estruturaDestinatarioId'
        );
    }

    /**
     * Rotina realizada após executar o comando.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $idRepositorioDeEstruturasRemetente = 2;
        $idEstruturaRemetente = 'prod' === $this->parameterBag->get('kernel.environment') ? 86088 : 81778;

        $transactionId = $this->transactionManager->begin();
        switch ($input->getOption('rotina')) {
            case 'receberTramite':
                $this->transactionManager->addContext(
                    new Context('criacaoProcessoBarramento', true),
                    $transactionId
                );
                $this->barramentoRecebeTramite
                    ->receberTramite((int) $input->getOption('idt'), $transactionId);
                break;
            case 'receberComponenteDigital':
                $this->barramentoRecebeComponenteDigital
                    ->receberComponentesDigitais((int) $input->getOption('idt'), $transactionId);
                break;
            case 'receberReciboDeTramite':
                $this->barramentoClient
                    ->receberReciboDeTramite((int) $input->getOption('idt'));
                break;
            case 'enviaComponenteDigital':
                $this->barramentoEnviaComponenteDigital
                    ->enviaComponentesDigitais((int) $input->getOption('idt'), $transactionId);
                break;
            case 'enviaCiencia':
                $this->barramentoClient->cienciaRecusa((int) $input->getOption('idt'));
                break;
            case 'enviaProcesso':
                $this->barramentoEnvioProcesso->enviarProcesso(
                    $idRepositorioDeEstruturasRemetente,
                    $idEstruturaRemetente,
                    (int) $input->getOption('repositorioEstruturasDestinatarioId'),
                    (int) $input->getOption('estruturaDestinatarioId'),
                    (int) $input->getOption('tramitacaoUuid'),
                    $transactionId
                );
                break;
            case 'enviaDocumentoAvulso':
                $this->barramentoEnviaDocumentoAvulso->enviarDocumento(
                    $idRepositorioDeEstruturasRemetente,
                    $idEstruturaRemetente,
                    (int) $input->getOption('repositorioEstruturasDestinatarioId'),
                    (int) $input->getOption('estruturaDestinatarioId'),
                    (int) $input->getOption('documentoAvulsoId'),
                    $transactionId
                );
                break;
        }
        $this->transactionManager->commit($transactionId);

        $io->success('Rotina executada com sucesso');

        return self::SUCCESS;
    }
}

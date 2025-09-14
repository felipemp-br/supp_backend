<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\TipoDossie;

use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDossieResource;
use SuppCore\AdministrativoBackend\Entity\TipoDossie;
use SuppCore\AdministrativoBackend\Integracao\Dossie\AbstractGeradorDossie;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists twig functions, filters, globals and tests present in the current project.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
#[AsCommand(name: 'debug:dossie', description: 'Exibe uma lista de tipos de dossiês existentes e das fábricas registradas.')]
class DebugCommand extends Command
{
    public function __construct(
        private readonly TipoDossieResource $tipoDossieResource,
        private readonly DossieManager $dossieManager
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    protected function configure()
    {
        $this
            ->addOption('params', 'p', InputOption::VALUE_NONE, 'Imprime os parâmetros de config internos da fábrica')
            ->setHelp("O comando <info>%command.name%</info> exibe uma lista de tipos de dossiês cadastrados\ne as fábricas registradas")
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @see setCode()
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rowsFabricasSemTipoDossieCadastrado = array_map(
            fn (AbstractGeradorDossie $f) => ['', '<error>NÃO ENCONTRADO</error>', '', $f::class, []],
            array_filter(
                $this->dossieManager->getGeradoresDossiesRegistrados(),
                fn (AbstractGeradorDossie $f) => !$f->getTipoDossie()
            )
        );

        $rowsFabricasComTipoDossieCadastrado = array_map(
            fn (AbstractGeradorDossie $f) => [
                "{$f->getTipoDossie()->getId()}",
                "{$f->getNomeTipoDossie()}",
                "{$f->getTipoDossie()->getPeriodoGuarda()}",
                $f::class,
            ],
            array_filter(
                $this->dossieManager->getGeradoresDossiesRegistrados(),
                fn (AbstractGeradorDossie $f) => $f->getTipoDossie()
            )
        );

        $rowsComTipoDossieCadastradoSemFabricas = array_map(
            fn (TipoDossie $t) => [
                "{$t->getId()}",
                "{$t->getNome()}",
                "{$t->getPeriodoGuarda()}",
                '<error>NÃO ENCONTRADO</error>',
            ],
            array_filter(
                $this->tipoDossieResource->getRepository()->findAll(),
                fn (TipoDossie $t) => !$this->dossieManager->getGeradorDossiePorTipoDossie($t)
            )
        );

        (new Table($output))
            ->setHeaders(['ID', 'TipoIndicador', 'Período Guarda', 'Fárica'])
            ->setRows(
                count($rowsFabricasSemTipoDossieCadastrado) || count($rowsComTipoDossieCadastradoSemFabricas) ?
                    [
                        ...$rowsFabricasComTipoDossieCadastrado,
                        new TableSeparator(),
                        ...$rowsFabricasSemTipoDossieCadastrado,
                        ...$rowsComTipoDossieCadastradoSemFabricas,
                    ] :
                    [
                        ...$rowsFabricasComTipoDossieCadastrado,
                    ]
            )
            ->render();

        if ($input->getOption('params')) {
            foreach ($this->dossieManager->getGeradoresDossiesRegistrados() as $f) {
                (new Table($output))
                    ->setHeaders([new TableCell($f::class, ['colspan' => 2])])
                    ->addRows(
                        $f->getParams() ?
                        array_map(fn ($i, $v) => [$i, $v], array_keys($f->getParams()), $f->getParams()) :
                        [[new TableCell('Nenhum parâmetro informado.', ['colspan' => 2])]]
                    )
                    ->render();
            }
        }

        return Command::SUCCESS;
    }
}

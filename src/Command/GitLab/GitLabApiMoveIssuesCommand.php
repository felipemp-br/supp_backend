<?php

declare(strict_types=1);
/**
 * /src/Command/User/GitLabApiMoverIssuesCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\GitLab;

use PHPUnit\Logging\Exception;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GitLabApiMoverIssuesCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:administrativo:git-lab-api-move-issues',
    description: 'Mover etiquetar entre projeto no GitLab'
)]
class GitLabApiMoveIssuesCommand extends Command
{
    use SymfonyStyleTrait;

    public const API_URL = 'https://gitlab.agu.gov.br/api/v4';

    public function __construct()
    {
        parent::__construct();

        $this->addOption(
            'token',
            null,
            InputOption::VALUE_REQUIRED,
            'Access Token GitLab.'
        )->addOption(
            'idProjetoOrigem',
            null,
            InputOption::VALUE_REQUIRED,
            'Id do projeto de origem das issues.'
        )->addOption(
            'idProjetoDestino',
            null,
            InputOption::VALUE_REQUIRED,
            'Id projeto de destino das issues.'
        )->addOption(
            'idIssue',
            null,
            InputOption::VALUE_OPTIONAL,
            'Id da issues a ser movida.'
        );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        if (!$input->getOption('token')
            || !$input->getOption('idProjetoOrigem')
            || !$input->getOption('idProjetoDestino')
        ) {
            throw new Exception('[ERROR] --token, --idProjetoOrigem e --idProjetoDestino são obrigatórios.');
        }

        $headers = ["PRIVATE-TOKEN: {$input->getOption('token')}"];

        if ($input->getOption('idIssue')) {
            $io->info(
                sprintf(
                    'Enviando issue: %s do Projeto %s para Projeto %s',
                    $input->getOption('idIssue'),
                    $input->getOption('idProjetoOrigem'),
                    $input->getOption('idProjetoDestino')
                )
            );

            // GET /projects/:id/issues/:issue_iid
            $urlPathInfo = sprintf(
                '/projects/%d/issues/%d',
                $input->getOption('idProjetoOrigem'),
                $input->getOption('idIssue')
            );

            $sourceIssue = $this->fetchIssues($urlPathInfo, $headers);

            if ($sourceIssue) {
                $urlPathInfo = sprintf(
                    '/projects/%d/issues/%d/move',
                    $input->getOption('idProjetoOrigem'),
                    $sourceIssue['iid']
                );

                $this->moveIssueToTargetProject(
                    $urlPathInfo,
                    $headers,
                    $input->getOption('idProjetoDestino')
                );
            }

            $io->success('Issue movida com sucesso!');
        } else {
            $io->success('Movendo Issue(s)...');

            do {
                // GET /projects/:id/issues
                $urlPathInfo = sprintf(
                    '/projects/%d/issues?state=opened&per_page=100',
                    $input->getOption('idProjetoOrigem')
                );

                $sourceIssues = $this->fetchIssues($urlPathInfo, $headers);

                if ($sourceIssues) {
                    $io->info('Enviando: '.count($sourceIssues).' issues.');

                    foreach ($io->progressIterate($sourceIssues) as $issue) {
                        // POST /projects/:id/issues/:id/move
                        $urlPathInfo = sprintf(
                            '/projects/%d/issues/%d/move',
                            $input->getOption('idProjetoOrigem'),
                            $issue['iid']
                        );

                        $this->moveIssueToTargetProject(
                            $urlPathInfo,
                            $headers,
                            $input->getOption('idProjetoDestino')
                        );
                    }
                }
            } while ($sourceIssues);

            $io->success('Issues movidas com sucesso!');
        }

        return Command::SUCCESS;
    }

    /**
     * @param $urlPathInfo
     * @param $headers
     *
     * @return array
     */
    public function fetchIssues($urlPathInfo, $headers): array
    {
        $url = self::API_URL.$urlPathInfo;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $this->curlErrors($curl);

        return json_decode($response, true);
    }

    /**
     * @param $urlPathInfo
     * @param $headers
     * @param $idProjetoDestino
     *
     * @return void
     */
    public function moveIssueToTargetProject($urlPathInfo, $headers, $idProjetoDestino): void
    {
        $formData = [
            'to_project_id' => $idProjetoDestino,
        ];

        $url = self::API_URL.$urlPathInfo;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
        curl_exec($curl);
        curl_close($curl);

        $this->curlErrors($curl);
    }

    /**
     * @param $curl
     *
     * @return void
     */
    public function curlErrors($curl): void
    {
        if (401 == curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
            throw new Exception('[ERROR] 401: Não autorizado!');
        }

        if (404 == curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
            throw new Exception('[ERROR] 404: Não encontrado!');
        }

        if (curl_error($curl)) {
            throw new Exception('[ERROR] '.curl_error($curl));
        }
    }
}

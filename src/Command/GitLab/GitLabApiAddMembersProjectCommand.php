<?php

declare(strict_types=1);
/**
 * /src/Command/User/GitLabApiAddMembersProjectCommand.php.
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
 * Class GitLabApiAddMembersProjectCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:administrativo:git-lab-api-add-members-project',
    description: 'Mover etiquetar entre projeto no GitLab'
)]
class GitLabApiAddMembersProjectCommand extends Command
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
            'idUser',
            null,
            InputOption::VALUE_OPTIONAL,
            'Id do usuário a ser adicionado no projeto.'
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

        if ($input->getOption('idUser')) {
            $io->info(
                sprintf(
                    'Adicionando membro: %s do Projeto %s para Projeto %s',
                    $input->getOption('idUser'),
                    $input->getOption('idProjetoOrigem'),
                    $input->getOption('idProjetoDestino')
                )
            );

            // GET /projects/:id/members/:user_id
            $urlPathInfo = sprintf(
                '/projects/%d/members/%d',
                $input->getOption('idProjetoOrigem'),
                $input->getOption('idUser')
            );

            $sourceMember = $this->fetchMembers($urlPathInfo, $headers);

            if ($sourceMember) {
                // POST /projects/:id/members
                $urlPathInfo = sprintf(
                    '/projects/%d/members',
                    $input->getOption('idProjetoDestino')
                );

                $this->addMemberToTargetProject(
                    $urlPathInfo,
                    $headers,
                    $sourceMember
                );
            }

            $io->success('Membro adicionando ao projeto com sucesso!');
        } else {
            $io->success('Adicionando membro(s)...');

            do {
                // GET /projects/:id/members
                $urlPathInfo = sprintf(
                    '/projects/%d/members?state=opened&per_page=100',
                    $input->getOption('idProjetoOrigem')
                );

                $sourceMembers = $this->fetchMembers($urlPathInfo, $headers);

                if ($sourceMembers) {
                    $io->info(
                        sprintf(
                            'Adicionando: %s membros no Projeto:%s',
                            count($sourceMembers),
                            $input->getOption('idProjetoDestino')
                        )
                    );

                    foreach ($io->progressIterate($sourceMembers) as $member) {
                        // POST /projects/:id/members
                        $urlPathInfo = sprintf(
                            '/projects/%d/members',
                            $input->getOption('idProjetoDestino')
                        );

                        $this->addMemberToTargetProject($urlPathInfo, $headers, $member);
                    }
                }
            } while ($sourceMembers);

            $io->success('Membros adicionandos ao projeto com sucesso!');
        }

        return Command::SUCCESS;
    }

    /**
     * @param $urlPathInfo
     * @param $headers
     *
     * @return array
     */
    public function fetchMembers($urlPathInfo, $headers): array
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
     * @param $member
     *
     * @return void
     */
    public function addMemberToTargetProject($urlPathInfo, $headers, $member): void
    {
        $formData = [
            'user_id' => $member['id'],
            'access_level' => $member['access_level'],
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

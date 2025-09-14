<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\SolicitacaoAutomatizada;

use DateTime;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SolicitacaoAutomatizadaAguardandoCumprimentoCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SolicitacaoAutomatizadaAguardandoCumprimentoCommand extends AbstractSolicitacaoAutomatizadaCommand
{
    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return 'supp:administrativo:solicitacao_automatizada:aguardando_cumprimento';
    }

    /**
     * @return void
     */
    public function configure(): void
    {
        $this->addOption(
            'dias',
            null,
            InputOption::VALUE_OPTIONAL,
            'Quantidade de dias utilizada para retono de solicitações anteriores a data.',
            30,
            [
                30,
                45,
                60,
            ]
        );
        $this->addOption(
            'tipos_solicitacoes_automatizadas',
            null,
            InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
            'Siglas dos tipos de solicitações automatizadas a serem retornadas.',
            [
                '*'
            ],
            [
                'PACIFICA_SAL_MAT_RURAL',
            ]
        );
    }

    protected function getCurrentStatus(): StatusSolicitacaoAutomatizada
    {
        return StatusSolicitacaoAutomatizada::AGUARDANDO_CUMPRIMENTO;
    }

    protected function getEtapaProcesso(): string
    {
        return 'verificação de cumprimento';
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return array
     */
    protected function getCriteria(
        InputInterface $input,
        OutputInterface $output
    ): array {
        $tiposSolicitacoesAutomatizadas = $input->getOption('tipos_solicitacoes_automatizadas');
        if (count($tiposSolicitacoesAutomatizadas) === 1 && in_array('*', $tiposSolicitacoesAutomatizadas)) {
            $tiposSolicitacoesAutomatizadas = [];
        }
        return [
            ...parent::getCriteria(
                $input,
                $output
            ),
            'criadoEm' => sprintf(
                'lte:%s',
                (new DateTime(
                    sprintf(
                        '-%s days',
                        $input->getOption('dias')
                    )
                ))
                    ->setTime(23, 59, 59)
                    ->format('Y-m-d\TH:i:s')
            ),
            ...(
                empty($tiposSolicitacoesAutomatizadas) ?
                    []
                    : [
                        'tipoSolicitacaoAutomatizada.sigla' => sprintf(
                            'in:%s',
                            join(
                                ',',
                                $tiposSolicitacoesAutomatizadas
                            )
                        )
                ]
            )
        ];
    }
}

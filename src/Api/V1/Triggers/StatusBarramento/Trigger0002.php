<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoClient;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerReadInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Atualiza o status do tramite de acordo com o barramento
 *
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerReadInterface
{
    /**
     * @param TransactionManager       $transactionManager
     * @param StatusBarramentoResource $statusBarramentoResource
     * @param BarramentoClient         $client
     * @param TramitacaoResource       $tramitacaoResource
     */
    public function __construct(
        private TransactionManager $transactionManager,
        private StatusBarramentoResource $statusBarramentoResource,
        private BarramentoClient $client,
        private TramitacaoResource $tramitacaoResource
    ) {
    }

    public function supports(): array
    {
        return [
            StatusBarramento::class => [
                'beforeFind',
            ],
        ];
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @param array $populate
     * @param array $result
     *
     * @return void
     */
    public function execute(
        array &$criteria,
        array &$orderBy,
        int &$limit,
        int &$offset,
        array &$populate,
        array &$result
    ): void {
        $id = $criteria['tramitacao.id'];
        if (!empty($criteria['tramitacao.id'])) {
            $transactionId = $this->transactionManager->begin();
            $id = (int) str_replace('eq:', '', $id);

            $statusBarramento = $this->statusBarramentoResource
                ->findOneBy(['tramitacao' => $id]);

            if ($statusBarramento) {
                $responseConsultaIDT = $this->client->consultarTramites($statusBarramento->getIdt());

                // tramite foi cancelado
                if (isset($responseConsultaIDT->tramitesEncontrados->tramite->situacaoAtual)
                    && $statusBarramento->getCodSituacaoTramitacao() !==
                    $responseConsultaIDT->tramitesEncontrados->tramite->situacaoAtual) {
                    $statusBarramentoDTO = $this->statusBarramentoResource->getDtoForEntity(
                        $statusBarramento->getId(),
                        StatusBarramentoDTO::class
                    );
                    $statusBarramentoDTO->setCodSituacaoTramitacao(
                        $responseConsultaIDT->tramitesEncontrados->tramite->situacaoAtual
                    );
                    $this->statusBarramentoResource->update(
                        $statusBarramento->getId(),
                        $statusBarramentoDTO,
                        $transactionId
                    );

                    if (7 === $responseConsultaIDT->tramitesEncontrados->tramite->situacaoAtual
                            && !$statusBarramento->getTramitacao()->getDataHoraRecebimento()) {
                        $tramitacaoDTO = $this->tramitacaoResource->getDtoForEntity(
                            $statusBarramento->getTramitacao()->getId(),
                            Tramitacao::class
                        );
                        $tramitacaoDTO->setDataHoraRecebimento(new \DateTime());
                        $this->tramitacaoResource->update(
                            $statusBarramento->getTramitacao()->getId(),
                            $tramitacaoDTO,
                            $transactionId
                        );
                    }
                }

                $this->transactionManager->commit($transactionId);
            } else {
                /** @var $tramitacaoDTO */
                $tramitacaoDTO = $this->tramitacaoResource->getDtoForEntity(
                    $id,
                    Tramitacao::class
                );
                if (!$tramitacaoDTO->getDataHoraRecebimento()) {
                    $tramitacaoDTO->setDataHoraRecebimento(new \DateTime());
                    $this->tramitacaoResource->update(
                        $id,
                        $tramitacaoDTO,
                        $transactionId
                    );
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

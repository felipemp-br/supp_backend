<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic\MessageHandlers;

use ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException;
use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TeseResource;
use SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateTeseMessage;
use SuppCore\AdministrativoBackend\Document\Mapper\TeseIndexMapper;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class PopulateTeseMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PopulateTeseMessageHandler
{
    /**
     * @param IndexService    $teseIndex
     * @param TeseResource    $teseResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly IndexService $teseIndex,
        private readonly TeseResource $teseResource,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param PopulateTeseMessage $message
     *
     * @throws BulkWithErrorsException
     */
    public function __invoke(PopulateTeseMessage $message): void
    {
        $startId = $message->getStartId();
        $endId = $message->getEndId();
        $currentId = $startId;

        while ($currentId <= $endId) {
            try {
                $tese = $this->teseResource->getRepository()->find($currentId);
                if (!$tese) {
                    ++$currentId;
                    continue;
                }

                $this->teseIndex->persist((new TeseIndexMapper())->map($tese));
            } catch (\Throwable $e) {
                $this->logger->critical(
                    'supp_ad | PopulateTeseMessageHandler | Mensagem: '
                    .$e->getMessage().' - '.$e->getTraceAsString()
                );
            }
            ++$currentId;
        }

        $this->teseIndex->commit('none');
    }
}

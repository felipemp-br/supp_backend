<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tese/MessageHandler/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tese\MessageHandler;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TeseResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Tese\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Document\Mapper\TeseIndexMapper;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class IndexacaoMessageHandler.
 */
#[AsMessageHandler]
class IndexacaoMessageHandler
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
     * @param IndexacaoMessage $message
     */
    public function __invoke(IndexacaoMessage $message): void
    {
        try {
            $tese = $this->teseResource
                ->getRepository()->findOneBy(['uuid' => $message->getUuid()]);
            if (!$tese) {
                return;
            }

            $this->teseIndex->persist((new TeseIndexMapper())->map($tese));
            $this->teseIndex->commit('none');
        } catch (\Exception $e) {
            $this->logger->critical(
                'supp_ad | indexacaoMessageHandler | Mensagem: '
                .$e->getMessage().' - '.$e->getTraceAsString()
            );
        }
    }
}

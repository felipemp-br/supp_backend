<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Datalake\Processors;

use Doctrine\Common\Annotations\AnnotationException;
use Exception;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Integracao\Datalake\KafkaTopicProcessorInterface;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * src/Integracao/Datalake/Processors/DossieTopicProcessor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DossieTopicProcessor implements KafkaTopicProcessorInterface
{
    public function __construct(
        private readonly DossieResource $dossieResource,
        private readonly DossieManager $dossieManager,
        private readonly TransactionManager $transactionManager
    ) {
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return 'dossie.patrimonial';
    }

    /**
     * @throws ReflectionException
     * @throws AnnotationException
     * @throws Exception
     */
    public function processTopicData(array $data): void
    {
        $transactionId = $this->transactionManager->getCurrentTransactionId() ?:
            $this->transactionManager->begin();

        if (!isset($data['dossie_id']) || !isset($data['dossie_conteudo'])) {
            return;
        }

        /** @var DossieDTO $dossieDTO */
        $dossieDTO = $this
            ->dossieResource
            ->getDtoForEntity($data['dossie_id'], DossieDTO::class);

        $geradorDossie = $this
            ->dossieManager
            ->getGeradorDossiePorTipoDossie($dossieDTO->getTipoDossie());

        $this->transactionManager->addAsyncDispatch(
            $geradorDossie
                ->setData(json_encode($data['dossie_conteudo']))
                ->getMessageClass(
                    $dossieDTO->getUuid(),
                    $dossieDTO->getCriadoPor()?->getId(),
                    $dossieDTO->getSobDemanda()
                ),
            $transactionId
        );

        $this->transactionManager->commit();
    }
}

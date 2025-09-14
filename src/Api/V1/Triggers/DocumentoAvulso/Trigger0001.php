<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Realiza a geração do documento remessa e de seu componente digital de acordo com o modelo informado!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private ComponenteDigitalResource $componenteDigitalResource;
    private TransactionManager $transactionManager;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ComponenteDigitalResource $componenteDigitalResource,
        TransactionManager $transactionManager
    ) {
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var DocumentoAvulso $documentoAvulsoDTO */
        $documentoAvulsoDTO = $restDto;

        $componenteDigitalDTO = new ComponenteDigital();

        if ($documentoAvulsoDTO->getTarefaOrigem()) {
            $componenteDigitalDTO->setTarefaOrigem($documentoAvulsoDTO->getTarefaOrigem());
        } else {
            $componenteDigitalDTO->setProcessoOrigem($documentoAvulsoDTO->getProcesso());
        }

        $componenteDigitalDTO->setModelo($documentoAvulsoDTO->getModelo());
        $componenteDigitalDTO->setEditavel(true);
        $componenteDigitalDTO->setFileName(
            $documentoAvulsoDTO->getModelo()->getNome().'.html'
        );
        $componenteDigitalDTO->setMimetype('text/html');
        $componenteDigitalDTO->setNivelComposicao(3);
        $componenteDigitalDTO->setExtensao('html');

        if($documentoAvulsoDTO->getSetorOrigem()){
            $this->transactionManager->addContext(
                new Context('documento_avulso_setor_origem', $documentoAvulsoDTO->getSetorOrigem()),
                $transactionId
            );
        }
        $this->transactionManager->addContext(
            new Context('minuta_documento_avulso', [true]),
            $transactionId
        );

        $this->transactionManager->addContext(
            new Context('documento_avulso', $documentoAvulsoDTO),
            $transactionId
        );

        $componenteDigital = $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
        $this->transactionManager->removeContext('minuta_documento_avulso', $transactionId);
        $this->transactionManager->removeContext('documento_avulso_setor_origem', $transactionId);
        $this->transactionManager->removeContext('documento_avulso', $transactionId);

        $documentoAvulsoDTO->setDocumentoRemessa($componenteDigital->getDocumento());
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tramitacao/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tramitacao;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger  =Recebe a tramitacao dos processos apensados e anexados
 * @classeSwagger=Trigger0006
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private TramitacaoResource $tramitacaoResource;

    private TransactionManager $transactionManager;

    /**
     * Trigger0006 constructor.
     */
    public function __construct(
        TramitacaoResource $tramitacaoResource,
        TransactionManager $transactionManager
    ) {
        $this->tramitacaoResource = $tramitacaoResource;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Tramitacao|RestDtoInterface|null $restDto
     * @param TramitacaoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$entity->getUsuarioRecebimento() &&
            $restDto->getUsuarioRecebimento()) {
            /** @var VinculacaoProcesso $vinculacaoProcessoEntity */
            foreach ($entity->getProcesso()->getVinculacoesProcessos() as $vinculacaoProcessoEntity) {
                if ('APENSAMENTO' === $vinculacaoProcessoEntity->getModalidadeVinculacaoProcesso()->getValor() ||
                    'ANEXAÇÃO' === $vinculacaoProcessoEntity->getModalidadeVinculacaoProcesso()->getValor()) {
                    $tramitacaoVinculada = $this->tramitacaoResource->getRepository(
                    )->findTramitacaoPendentePorProcesso($vinculacaoProcessoEntity->getProcessoVinculado()->getId());
                    if ($tramitacaoVinculada) {
                        $this->transactionManager->addContext(
                            new Context('criacaoTramitacaoProcessoApensado', true),
                            $transactionId
                        );

                        /** @var Tramitacao $tramitacaoDto */
                        $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                            $tramitacaoVinculada->getId(),
                            Tramitacao::class
                        );
                        $tramitacaoDto->setUsuarioRecebimento($restDto->getUsuarioRecebimento());
                        $tramitacaoDto->setDataHoraRecebimento(new DateTime());
                        $this->tramitacaoResource->update(
                            $tramitacaoVinculada->getId(),
                            $tramitacaoDto,
                            $transactionId
                        );

                        $this->transactionManager->removeContext(
                            'criacaoTramitacaoProcessoApensado',
                            $transactionId
                        );
                    }
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Counters/Notificacao/Counter0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Counters\Notificacao;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Counter\BaseCounter;
use SuppCore\AdministrativoBackend\Counter\CounterInterface;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;

/**
 * Class Counter0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Counter0001 extends BaseCounter implements CounterInterface
{
    /**
     * @return PushMessage[]
     */
    public function getMessages(): array
    {
        $pushMessage = new PushMessage();
        $pushMessage->setIdentifier('notificacoes_pendentes');
        $pushMessage->setChannel(
            $this->tokenStorage->getToken()->getUser()->getUserIdentifier()
        );
        $pushMessage->setResource(
            NotificacaoResource::class
        );
        $pushMessage->setCriteria(
            [
                'destinatario.username' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
                'dataHoraLeitura' => 'isNull',
                'dataHoraExpiracao' => 'gt:'.(new DateTime('now'))->format('Y-m-d\TH:i:s'),
            ]
        );

        return [$pushMessage];
    }

    public function getOrder(): int
    {
        return 1;
    }
}

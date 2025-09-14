<?php

/** @noinspection PhpUnused */

declare(strict_types=1);
/**
 * /src/EventListener/PostCreateClientListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use ONGR\ElasticsearchBundle\Event\Events;
use ONGR\ElasticsearchBundle\Event\PostCreateClientEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PostCreateClientListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PostCreateClientListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::POST_CLIENT_CREATE => ['onPostClientCreate'],
        ];
    }

    /**
     * @param PostCreateClientEvent $event
     */
    public function onPostClientCreate(PostCreateClientEvent $event)
    {
        $event->getClient()->setSSLVerification(false);
    }
}

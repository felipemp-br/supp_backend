<?php

declare(strict_types=1);
/**
 * /src/Doctrine/ORM/SoftDeleteable/SoftDeleteableRequestListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\SoftDeleteable;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SoftDeleteableRequestListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SoftDeleteableRequestListener
{
    protected EntityManagerInterface $entityManager;
    protected RequestStack $requestStack;

    /**
     * SoftDeleteableRequestListener constructor.
     *
     * @param $entityManager
     * @param $requestStack
     */
    public function __construct($entityManager, $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        try {
            $context = array_filter(
                JSON::decode($this->requestStack->getCurrentRequest()->get('context', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'context\' parameter is not valid JSON.', $error);
        }

        if (isset($context['mostrarApagadas']) && (true === $context['mostrarApagadas'])) {
            if (array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
                $this->entityManager->getFilters()->disable('softdeleteable');
            }
        }
    }
}

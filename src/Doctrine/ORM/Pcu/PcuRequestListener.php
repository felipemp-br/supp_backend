<?php

declare(strict_types=1);
/**
 * /src/Doctrine/ORM/Pcu/PcuRequestListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\Pcu;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class PcuRequestListener.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PcuRequestListener
{
    protected EntityManagerInterface $entityManager;
    protected AuthorizationCheckerInterface $authorizationChecker;
    protected RequestStack $requestStack;
    protected TokenStorageInterface $tokenStorage;

    /**
     * PcuRequestListener constructor.
     *
     * @param EntityManagerInterface        $entityManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RequestStack                  $requestStack
     * @param TokenStorageInterface         $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage
    ) {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser()) {
            return;
        }

        try {
            $context = array_filter(
                JSON::decode($this->requestStack->getCurrentRequest()->get('context', '{}'), true),
                fn ($value): bool => null !== $value
            );
        } catch (LogicException $error) {
            throw new HttpException(HttpFoundationResponse::HTTP_BAD_REQUEST, 'Current \'context\' parameter is not valid JSON.', $error);
        }

        if ((false === isset($context['isAdmin']) || (true !== $context['isAdmin']))) {
            if (!array_key_exists('pcu', $this->entityManager->getFilters()->getEnabledFilters())) {
                $this->entityManager->getFilters()->enable('pcu');
            }
        }
    }
}

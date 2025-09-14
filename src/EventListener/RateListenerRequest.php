<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EventListener;

use DateTime;
use Redis;
use RedisException;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RateListenerRequest.
 */
class RateListenerRequest implements EventSubscriberInterface
{
    private array $apiRateLimitStatus = [];

    /**
     * @param SuppParameterBag $suppParameterBag
     * @param RateLimiterFactory $perUserLimiter
     * @param RateLimiterFactory $perApiLimiter
     * @param RateLimiterFactory $perApiNightLimiter
     * @param TokenStorageInterface $tokenStorage
     * @param Redis $redisClient
     */
    public function __construct(
        private readonly SuppParameterBag $suppParameterBag,
        private readonly RateLimiterFactory $perUserLimiter,
        private readonly RateLimiterFactory $perApiLimiter,
        private readonly RateLimiterFactory $perApiNightLimiter,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly Redis $redisClient
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     * @throws RedisException
     */
    public function onKernelRequest(
        RequestEvent $event
    ): void {
        $this->apiRateLimitStatus = [];
        if ($this->redisClient->get('maintenance')) {
            throw new BadRequestHttpException("Sistema em manutenção!", null, 403);
        }

        if (!$this->tokenStorage->getToken() ||
            !$this->tokenStorage->getToken()->getUser() ||
            (false === ($this->tokenStorage->getToken()->getUser() instanceof Usuario))) {
            return;
        }

        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $rateLimitAPIKey = $request->headers->get('X-RateLimit-API');
        if (!$rateLimitAPIKey) {
            // per user
            $userIdentifier = $this->tokenStorage->getToken()->getUser()->getUserIdentifier();
            $userLimiter = $this->perUserLimiter->create($userIdentifier);
            $userLimit = $userLimiter->consume();
            if (false === $userLimit->isAccepted()) {
                throw new BadRequestHttpException(
                    "Usuário bloqueado por 15 minutos por consumo excessivo!",
                    null,
                    403
                );
            }
        } else {
            // per api
            $robosAutorizados =
                $this->suppParameterBag->has('supp_core.administrativo_backend.rate_listener.robos') ?
                    $this->suppParameterBag->get('supp_core.administrativo_backend.rate_listener.robos') :
                    [
                        'Pgf/Conecta', 'Mercurio', 'Panda', 'SAP-Prev', 'Loki', 'ExtratorCreta', 'Camelo',
                        'RoboGeralPrf3', 'optimus', 'prime', 'baixa_intimacoes_sapiens', 'new_cides', 'vanoni'
                    ];

            if (!in_array($rateLimitAPIKey, $robosAutorizados)) {
                throw new BadRequestHttpException(
                    "API não autorizada para uso no Super Sapiens!",
                    null,
                    403
                );
            }

            $horarioLimite =
                $this->suppParameterBag->has('supp_core.administrativo_backend.rate_listener.horario') ?
                    $this->suppParameterBag->get('supp_core.administrativo_backend.rate_listener.horario') :
                    '22:00:00-06:00:00';

            [$horaInicio, $horaFim] = explode('-', $horarioLimite);
            $horaAtual = (new DateTime())->format('H:i:s');
            $apiLimiter = ($horaAtual >= $horaInicio && $horaAtual <= $horaFim) ?
                $this->perApiNightLimiter->create($rateLimitAPIKey) :
                $this->perApiLimiter->create($rateLimitAPIKey);

            $apiLimit = $apiLimiter->consume();
            if (false === $apiLimit->isAccepted()) {
                throw new BadRequestHttpException(
                    "API bloqueada por 15 minutos por consumo excessivo!",
                    null,
                    403
                );
            }

            $this->apiRateLimitStatus = [
                'X-RateLimit-API'           => $rateLimitAPIKey,
                'X-RateLimit-Remaining'     => $apiLimit->getRemainingTokens(),
                'X-RateLimit-Retry-After'   => $apiLimit->getRetryAfter()->getTimestamp(),
                'X-RateLimit-Limit'         => $apiLimit->getLimit(),
            ];
        }
    }

    public function getApiRateLimitStatus(): array
    {
        return $this->apiRateLimitStatus;
    }
}

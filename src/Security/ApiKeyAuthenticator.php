<?php

declare(strict_types=1);
/**
 * /src/Security/ApiKeyAuthenticator.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use Exception;
use SuppCore\AdministrativoBackend\Repository\ApiKeyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Class ApiKeyAuthenticator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class ApiKeyAuthenticator implements AuthenticatorInterface
{
    public function __construct(
        private ApiKeyRepository $apiKeyRepository
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * @throws Exception
     */
    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('X-AUTH-TOKEN');

        if (!$token) {
            throw new Exception('O header X-AUTH-TOKEN não foi enviado na request');
        }

        $apiKey = $this->apiKeyRepository->findOneBy(['token' => $token, 'ativo' => true, 'apagadoEm' => null]);

        if (!$apiKey) {
            throw new Exception('Nenhuma API key encontrada para o token fornecido');
        }

        if (false === $apiKey->getUsuario()->getEnabled()) {
            throw new Exception('O usuário da API Key fornecida está desabilitado');
        }

        $passport = new SelfValidatingPassport(new UserBadge($apiKey->getUsuario()->getUserIdentifier()));
        $passport->setAttribute('apiKeyId', $apiKey->getId());

        return $passport;
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $token = new PostAuthenticationToken(
            $passport->getUser(),
            $firewallName,
            array_merge($passport->getUser()->getRoles(), ['ROLE_API'])
        );

        $token->setAttribute('apiKeyId', $passport->getAttribute('apiKeyId'));
        $token->setAttribute('trusted', 'apiKey');

        return $token;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
}

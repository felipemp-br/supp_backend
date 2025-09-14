<?php

declare(strict_types=1);
/**
 * /src/Utils/MercureJWTProvider.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\MicrosecondBasedDateConversion;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder as JWTBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;

/**
 * Class MercureJWTProvider.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class MercureJWTProvider implements TokenProviderInterface
{
    private ParameterBagInterface $parameterBag;

    /**
     * JWTDecodedSubscriber constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        ParameterBagInterface $parameterBag
    ) {
        $this->parameterBag = $parameterBag;
    }

    public function getJwt(): string
    {
        $mercureSecret = $this->parameterBag->get('mercure_jwt_secret');

        $token = (new JWTBuilder(new JoseEncoder(), new MicrosecondBasedDateConversion()))
            ->withClaim('mercure', ['publish' => []])
            ->getToken(new Sha256(), InMemory::plainText($mercureSecret));

        return $token->toString();
    }
}

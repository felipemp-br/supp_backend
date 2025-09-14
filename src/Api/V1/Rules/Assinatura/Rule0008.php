<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Assinatura/Rule0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\X509Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0008.
 *
 * @descSwagger=Apenas o usuário que efetuou a assinatura digital pode excluí-la.
 * @classeSwagger=Rule0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008 implements RuleInterface
{
    /**
     * Rule0008 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly X509Service $x509Service,
    ) {
    }

    public function supports(): array
    {
        return [
            Assinatura::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            $entity->getCadeiaCertificadoPEM()) {
            $sCertChain = $entity->getCadeiaCertificadoPEM();
            // é teste?
            if ('cadeia_teste' === $sCertChain) {
                return true;
            }

            // PEM invalido
            $aCertChain = explode('-----END CERTIFICATE-----', $sCertChain);
            if (0 == count($aCertChain)) {
                return true;
            }

            $firstCert = $aCertChain[0].'-----END CERTIFICATE-----';

            $parsed = $this->x509Service->getCredentials($firstCert);

            if (!$parsed) {
                // se não conseguiu fazer parser do certificado, retorna true. Tal erro deve ser capturado em outra Rule
                return true;
            }

            // assinatura A1 institucional
            if ($parsed['institucional'] &&
                $entity->getCriadoPor()->getUsername() === $this->tokenStorage->getToken()->getUser()->getUsername()) {
                return true;
            }

            // assinatura A1, A3 externo
            if ($parsed['username'] === $this->tokenStorage->getToken()->getUser()->getUsername()) {
                return true;
            }
        }

        $this->rulesTranslate->throwException('assinatura', '0008');
    }

    public function getOrder(): int
    {
        return 8;
    }
}

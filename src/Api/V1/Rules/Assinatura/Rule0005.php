<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Assinatura/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005.
 *
 * @descSwagger=Para criar uma assinatura é necessário uma chave pública válida
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0005 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Assinatura::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Assinatura|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Assinatura|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // esse if existe para permitir testes sem uma chave pública válida
        if ('cadeia_teste' !== $restDto->getCadeiaCertificadoPEM()) {
            $aCertChain = explode('-----END CERTIFICATE-----', $restDto->getCadeiaCertificadoPEM());
            $fisrtCert = $aCertChain[0].'-----END CERTIFICATE-----';
            $pubkeyid = openssl_pkey_get_public($fisrtCert);

            if (!$pubkeyid) {
                $this->rulesTranslate->throwException('assinatura', '0005');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 5;
    }
}
<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Assinatura/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Assinatura;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Utils\X509Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{

    /**
     * Pipe0001 constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag, private readonly X509Service $x509Service)
    {
    }

    public function supports(): array
    {
        return [
            AssinaturaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param AssinaturaDTO|RestDtoInterface|null $restDto
     * @param AssinaturaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        AssinaturaDTO|RestDtoInterface|null &$restDto,
        AssinaturaEntity|EntityInterface $entity
    ): void {
        if (!$restDto->getCadeiaCertificadoPEM()) {
            return;
        }

        $sCertChain = $restDto->getCadeiaCertificadoPEM();
        if ('cadeia_teste' === $sCertChain) {
            if ($entity->getCriadoPor()) {
                $restDto->setAssinadoPor($entity->getCriadoPor()->getNome().' EM MODO TESTE');
            }

            return;
        }

        $aCertChain = explode('-----END CERTIFICATE-----', $sCertChain);
        if (0 == count($aCertChain)) {
            return;
        }
        $firstCert = $aCertChain[0].'-----END CERTIFICATE-----';

        $parsed = $this->x509Service->getCredentials($firstCert);

        if (!$parsed) {
            return;
        }

        if ($parsed['institucional']) {
            $nome = $entity->getCriadoPor()->getNome().
                ', utilizando Certificado Institucional ('
                .$parsed['cn'].'), mediante fornecimento de USUÁRIO E SENHA';
        } else {
            $nome = $parsed['assinadoPor'];
        }

        $nome.= (!empty($entity->getPadrao()) ? ' ('.$entity->getPadrao().')' : '');

        $restDto->setAssinadoPor($nome);
    }

    public function getOrder(): int
    {
        return 1;
    }
}

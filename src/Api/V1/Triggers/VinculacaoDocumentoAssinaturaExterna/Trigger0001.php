<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoDocumentoAssinaturaExterna/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

 namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoDocumentoAssinaturaExterna;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria data de expiração e resolve usuário caso já exista no supp para solicitação de assinatura!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        private readonly UsuarioResource $usuarioResource
    ) { }

    public function supports(): array
    {
        return [
            VinculacaoDocumentoAssinaturaExternaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumentoAssinaturaExternaDTO $restDto
     * 
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $dataAtual = new DateTime();
        // Adiciona 3 dias para data de expiração
        $restDto->setExpiraEm($dataAtual->modify("+3 days"));

        if($restDto->getNumeroDocumentoPrincipal()) {
            $usuario = $this->usuarioResource->findOneBy(['username' => $restDto->getNumeroDocumentoPrincipal()]);
            if($usuario) {
                $restDto->setUsuario($usuario);

                $restDto->setNumeroDocumentoPrincipal(null);
                $restDto->setEmail(null);
            }
        }
        
    }

    public function getOrder(): int
    {
        return 1;
    }
}

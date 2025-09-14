<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Sigilo/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Sigilo;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SigiloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Gera o código de indexação para os sigilos da Lei de Acesso à Informação!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private SigiloResource $sigiloResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        SigiloResource $sigiloResource
    ) {
        $this->sigiloResource = $sigiloResource;
    }

    public function supports(): array
    {
        return [
            Sigilo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Sigilo|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getTipoSigilo()->getLeiAcessoInformacao()) {
            if ($restDto->getProcesso()) {
                $codigoIndexacao = $restDto->getProcesso()->getNUP().'.';
            } elseif ($restDto->getDocumento()->getProcessoOrigem()) {
                $codigoIndexacao = $restDto->getDocumento()->getProcessoOrigem()->getNUP().'.';
            } else {
                $codigoIndexacao = $restDto->getDocumento()->getTarefaOrigem()->getProcesso()->getNUP().'.';
            }

            //R, S, U
            switch ($restDto->getTipoSigilo()->getNivelAcesso()) {
                case 2:
                    $codigoIndexacao .= 'R.';
                    break;
                case 3:
                    $codigoIndexacao .= 'S.';
                    break;
                case 4:
                    $codigoIndexacao .= 'U.';
                    break;
            }

            //Categoria
            $codigoIndexacao .= $restDto->getModalidadeCategoriaSigilo()->getValor().'.';

            //Data de Produção
            $dateTime = new DateTime();
            $codigoIndexacao .= $dateTime->format('d-m-Y').'.';

            //Data de Validade
            if ($restDto->getDataHoraValidadeSigilo()) {
                $codigoIndexacao .= $restDto->getDataHoraValidadeSigilo()->format('d-m-Y').'.N';
            }

            $restDto->setCodigoIndexacao($codigoIndexacao);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}

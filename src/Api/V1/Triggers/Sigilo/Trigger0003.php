<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Sigilo/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Sigilo;

use DateInterval;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SigiloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Seta o nível de acesso e determina automaticamente a validade do sigilo!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private SigiloResource $sigiloResource;

    /**
     * Trigger0003 constructor.
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

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setNivelAcesso($restDto->getTipoSigilo()->getNivelAcesso());

        $dateTime = clone $restDto->getDataHoraInicioSigilo();

        //definição da data de validade dependendo do nivelAcesso
        switch ($restDto->getTipoSigilo()->getNivelAcesso()) {
            case 1: // 100 anos
                $dateTime->add(new DateInterval('P100Y'));
                $restDto->setDataHoraValidadeSigilo($dateTime);
                break;
            case 2: // reservada 5 anos
                $dateTime->add(new DateInterval('P5Y'));
                $restDto->setDataHoraValidadeSigilo($dateTime);
                break;
            case 3: // secreta 15 anos
                $dateTime->add(new DateInterval('P15Y'));
                $restDto->setDataHoraValidadeSigilo($dateTime);
                break;
            case 4: // ultrassecreta 25 anos
                $dateTime->add(new DateInterval('P25Y'));
                $restDto->setDataHoraValidadeSigilo($dateTime);
                break;
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}

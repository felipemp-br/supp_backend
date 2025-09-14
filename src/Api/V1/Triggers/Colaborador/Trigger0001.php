<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Colaborador/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Colaborador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=A criação de um colaborador ajusta o nível de acesso para 1
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private UsuarioResource $usuarioResouce;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(UsuarioResource $usuarioResouce)
    {
        $this->usuarioResouce = $usuarioResouce;
    }

    public function supports(): array
    {
        return [
            Colaborador::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Colaborador|RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var Usuario $usuarioDto */
        $usuarioDto = $this->usuarioResouce->getDtoForEntity(
            $restDto->getUsuario()->getId(),
            Usuario::class
        );
        if (('TERCEIRIZADO' !== $restDto->getModalidadeColaborador()->getValor()) &&
            ('ESTAGIÁRIO' !== $restDto->getModalidadeColaborador()->getValor())) {
            $usuarioDto->setNivelAcesso(1);
        }
        $usuarioDto->setColaborador($entity);
        $this->usuarioResouce->update(
            $restDto->getUsuario()->getId(),
            $usuarioDto,
            $transactionId,
            true
        );
    }

    public function getOrder(): int
    {
        return 1;
    }
}

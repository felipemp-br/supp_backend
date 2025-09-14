<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/CargoUsuarioField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class CargoUsuarioField.
 *
 * Cargo do Usuário.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CargoUsuarioField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'modalidadeCargo';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_cargo_usuario_field',
                'nome' => 'CARGO DO USUÁRIO',
                'descricao' => 'CARGO DO USUÁRIO',
                'html' => '<span data-method="modalidadeCargo" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\CargoUsuarioField">*cargoUsuario*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Usuario::class,
            ],
        ];
    }

    /**
     * @param string $transactionId
     * @param array  $context
     * @param array  $options
     *
     * @return string
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        /** @var Usuario $usuario */
        $usuario = $context['usuario'];
        if (!isset($usuario) ||
            !$usuario->getColaborador() ||
            !$usuario->getColaborador()->getCargo()) {
            return '';
        }

        return $usuario->getColaborador()->getCargo()->getNome();
    }
}

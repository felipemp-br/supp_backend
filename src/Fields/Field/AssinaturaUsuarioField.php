<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/AssinaturaUsuarioField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;

/**
 * Class AssinaturaUsuarioField.
 *
 * Assinatura em texto definida pelo Usuário.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssinaturaUsuarioField implements FieldInterface
{
    public function __construct(private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'assinaturaUsuario';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_assinatura_usuario_field',
                'nome' => 'ASSINATURA DO USUÁRIO',
                'descricao' => 'ASSINATURA EM TEXTO DEFINIDA PELO USUÁRIO',
                'html' => '<span data-method="assinaturaUsuario" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\AssinaturaUsuarioField">*assinaturaUsuario*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Usuario::class,
                Tarefa::class,
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
        if (!isset($usuario)) {
            return '';
        }

        /** @var Tarefa $tarefa */
        $tarefa = $context['tarefa'];

        // assessor
        if (isset($tarefa) &&
            ($tarefa->getUsuarioResponsavel()->getId() !== $usuario->getId()) &&
            ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $tarefa->getUsuarioResponsavel()->getId(),
                $usuario->getId()
            ))) {
            $usuarioTarefa = $tarefa->getUsuarioResponsavel();
        } else {
            $usuarioTarefa = $usuario;
        }

        $assinatura = $usuarioTarefa->getAssinaturaHTML();

        $tokens = explode("\n", $assinatura);
        $assinaturaFormatada = '';
        if (count($tokens) > 1) {
            foreach ($tokens as $token) {
                $assinaturaFormatada .= '<p class="centralizado">'.$token.'</p>';
            }
        }
        if ('' != $assinaturaFormatada) {
            return $assinaturaFormatada;
        } else {
            return $assinatura;
        }
    }
}

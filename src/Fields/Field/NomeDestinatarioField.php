<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/NomeDestinatarioField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class NomeDestinatarioField.
 *
 * Nome do destinatário da comunicação
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NomeDestinatarioField implements FieldInterface
{
    public function __construct(
        private TransactionManager $transactionManager
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'destinatario';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_nome_destinatario_field',
                'nome' => 'NOME DO DESTINATÁRIO',
                'descricao' => 'NOME DO DESTINATÁRIO DA COMUNICAÇÃO',
                'html' => '<span data-method="destinatario" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\NomeDestinatarioField">*nomeDestinatario*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Documento::class,
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
        /** @var DocumentoAvulso $documentoAvulso */
        $documentoAvulso = $context['documentoAvulso'];
        if (!isset($documentoAvulso)
            || !$documentoAvulso) {
            return '';
        }

        if ($documentoAvulso->getPessoaDestino()) {
            return $documentoAvulso->getPessoaDestino()->getNome();
        }

        // regra de excepcional inserida em razão de parecer da CGU
        if ($documentoAvulso->getSetorDestino()
            && ('CONSULTORIA JURÍDICA JUNTO AO MINISTÉRIO DA SAÚDE' == $documentoAvulso->getSetorDestino()->getNome())
            && (
                ('CUMPRIMENTO DE DECISÃO JUDICIAL' == $documentoAvulso->getEspecieDocumentoAvulso()->getNome())
                || ('REITERAÇÃO DE CUMPRIMENTO DE DECISÃO JUDICIAL' == $documentoAvulso->getEspecieDocumentoAvulso()->getNome())
                || ('REVOGAÇÃO OU SUSPENSÃO DE CUMPRIMENTO DE DECISÃO JUDICIAL' == $documentoAvulso->getEspecieDocumentoAvulso()->getNome())
                || ('COMPLEMENTAÇÃO DE CUMPRIMENTO DE DECISÃO JUDICIAL' == $documentoAvulso->getEspecieDocumentoAvulso()->getNome()))
        ) {
            return 'Responsável pelo NÚCLEO DE JUDICIALIZAÇÃO DA SECRETARIA EXECUTIVA DO MINISTÉRIO DA SAÚDE';
        }

        if ($documentoAvulso->getSetorDestino()) {
            return 'Responsável pela '.$documentoAvulso->getSetorDestino(
            )->getNome();
        }

        return '';
    }
}

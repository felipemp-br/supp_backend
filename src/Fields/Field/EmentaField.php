<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/EmentaField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class EmentaField.
 *
 * Gera uma ementa a partir dos assuntos do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EmentaField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'ementa';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_ementa_field',
                'nome' => 'EMENTA',
                'descricao' => 'GERA UMA EMENTA À PARTIR DOS ASSUNTOS DO NUP',
                'html' => '<span data-method="ementa" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\EmentaField">*ementa*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
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
        /** @var Processo $processo */
        $processo = $context['processo'];
        if (!isset($processo)) {
            return '';
        }
        $retorno = '';
        /** @var Assunto $assunto */
        foreach ($processo->getAssuntos() as $assunto) {
            if ($assunto->getPrincipal()) {
                $fila = [];
                $fila[] = $assunto->getAssuntoAdministrativo()->getNome();
                $parent = $assunto->getAssuntoAdministrativo()->getParent();
                while ($parent) {
                    if (('ATIVIDADE MEIO' !== $parent->getNome()) &&
                        ('ATIVIDADE FIM' !== $parent->getNome())) {
                        $fila[] = $parent->getNome();
                    }
                    $parent = $parent->getParent();
                }
                $fila = array_reverse($fila);
                foreach ($fila as $item) {
                    $retorno .= $item.'. ';
                }
            }
        }

        return $retorno;
    }
}

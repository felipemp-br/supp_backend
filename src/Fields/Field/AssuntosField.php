<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/AssuntosField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Repository\AssuntoRepository;

/**
 * Class AssuntosField.
 *
 * Assuntos do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssuntosField implements FieldInterface
{
    private AssuntoRepository $assuntoRepository;

    /**
     * AssuntosField constructor.
     *
     * @param AssuntoRepository $assuntoRepository
     */
    public function __construct(AssuntoRepository $assuntoRepository)
    {
        $this->assuntoRepository = $assuntoRepository;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'assuntos';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_assuntos_field',
                'nome' => 'ASSUNTOS',
                'descricao' => 'ASSUNTOS DO NUP',
                'html' => '<span data-method="assuntos" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\AssuntosField">*assuntos*</span>',
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

        if (!isset($processo) || !count($processo->getAssuntos())) {
            return '';
        }

        $assuntoPrincipal = $processo->getAssuntos()[0];

        if ($processo->getId()) {
            /** @var Assunto $assuntoPrincipal */
            $assuntoPrincipal = $this->assuntoRepository->findPrincipal($processo->getId());
        } else {
            /** @var Assunto $assunto */
            foreach ($processo->getAssuntos() as $assunto) {
                if ($assunto->getPrincipal()) {
                    $assuntoPrincipal = $assunto;
                    break;
                }
            }
        }

        if (!$assuntoPrincipal) {
            return '';
        }

        if (count($processo->getAssuntos()) > 1) {
            return $assuntoPrincipal->getAssuntoAdministrativo()->getNome().' E OUTROS';
        } else {
            return $assuntoPrincipal->getAssuntoAdministrativo()->getNome();
        }
    }
}

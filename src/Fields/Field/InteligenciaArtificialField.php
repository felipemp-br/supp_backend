<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Fields\Field;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers\IAFieldParser;
use Throwable;

/**
 * InteligenciaArtificialField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteligenciaArtificialField implements FieldInterface
{
    /**
     * Constructor.
     *
     * @param IAFieldParser   $iaFieldParser
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly IAFieldParser $iaFieldParser,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param string $transactionId
     * @param array  $context
     * @param array  $options
     *
     * @return string
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        $value = '';
        if (isset($options[0]) && $this->iaFieldParser->support($options[0])) {
            try {
                $property = $this->iaFieldParser->getJsonSchemaProperty(
                    htmlspecialchars_decode($options[0]),
                    $context['processo']
                );
                $parsedValue = $property->getParsedValue();
                $value = match ($property->getJsonSchemaPropertyInfo()->getType()) {
                    'array' => join('<br>', $parsedValue),
                    'boolean' => $parsedValue ? 'SIM' : 'NÃO',
                    'date' => $parsedValue->format('d/m/Y'),
                    'date-time' => $parsedValue->format('d/m/Y H:i:s'),
                    default => $parsedValue,
                };
            } catch (Throwable $e) {
                $this->logger->error(
                    'Falha ao fazer parse do campo de IA',
                    [
                        'field' => $options[0],
                        'message' => $e->getMessage(),
                        'trace' => $e->getTrace(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                    ]
                );
            }
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'formulario';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.ia_field',
                'nome' => 'FORMULÁRIO',
                'descricao' => 'METADADOS DE INTELIGÊNCIA ARTIFICIAL',
                'html' => '<span data-method="formulario" data-options="" '.
                    'data-service="SuppCore\AdministrativoBackend\Fields\Field\InteligenciaArtificialField">*formulario*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
            ],
        ];
    }
}

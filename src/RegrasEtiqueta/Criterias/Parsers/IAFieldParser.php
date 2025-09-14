<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers;

use Psr\Cache\CacheItemPoolInterface;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums\OperatorEnum;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions\InvalidSchemaPropertyPathException;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions\SelectorNotFoundException;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers\JsonSchemaHelper;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers\PlainDataComparerHelper;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models\JsonSchemaProperty;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models\JsonSchemaPropertyInfo;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Selectors\SelectorInterface;
use SuppCore\AdministrativoBackend\Repository\DadosFormularioRepository;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

/**
 * IAFieldParser.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class IAFieldParser implements FieldParserInterface
{
    public const EXPRESSION_BLOCK_SEPARATOR = ':';

    /** @var SelectorInterface[] */
    private readonly array $selectors;

    /**
     * Constructor.
     *
     * @param Traversable               $selectors
     * @param DadosFormularioRepository $dadosFormularioRepository
     * @param JsonSchemaHelper          $jsonSchemaHelper
     * @param PlainDataComparerHelper   $comparerHelper
     * @param CacheItemPoolInterface    $inMemoryCache
     */
    public function __construct(
        #[TaggedIterator('supp_core.administrativo_backend.regras_etiqueta.criterias.selector')]
        Traversable $selectors,
        private readonly DadosFormularioRepository $dadosFormularioRepository,
        private readonly JsonSchemaHelper $jsonSchemaHelper,
        private readonly PlainDataComparerHelper $comparerHelper,
        private readonly CacheItemPoolInterface $inMemoryCache
    ) {
        $this->selectors = iterator_to_array($selectors);
    }

    /**
     * @param string   $field
     * @param Processo $processo
     *
     * @return bool
     */
    public function support(string $field): bool
    {
        return str_starts_with($field, 'ia:');
    }

    /**
     * Formato de field esperado: ia:seletor_documento=ultimo_documento_tipo#1&formulario=1#processo.valor_causa
     * Formato de value esperado: eq:1.
     *
     * @param string        $field
     * @param string        $value
     * @param Processo|null $processo
     *
     * @return bool
     *
     * @throws InvalidSchemaPropertyPathException
     * @throws SelectorNotFoundException
     */
    public function parse(string $field, string $value, ?Processo $processo = null): bool
    {
        if (!$processo) {
            return false;
        }

        $jsonSchemaProperty = $this->getJsonSchemaProperty($field, $processo);

        if ($jsonSchemaProperty) {
            [$operator, $operatorValue] = $this->getOperatorAndValueFromValueExpression($value);

            return $this->comparerHelper->compare(
                $operatorValue,
                $jsonSchemaProperty->getParsedValue(),
                $operator
            );
        }

        return false;
    }

    /**
     * Retorna a propriedade JSON Schema do campo informado.
     *
     * @param string   $expression
     * @param Processo $processo
     *
     * @return JsonSchemaProperty|null
     *
     * @throws InvalidSchemaPropertyPathException
     * @throws SelectorNotFoundException
     */
    public function getJsonSchemaProperty(string $expression, Processo $processo): ?JsonSchemaProperty
    {
        return $this->getCachedData(
            sprintf(
                'ia:dados_formulario:%s:processo:%s:json_schema_property',
                $expression,
                $processo->getId(),
            ),
            function () use ($expression, $processo) {
                [$driver, $selectorExpression, $fomularioExpression, $field] = explode(
                    self::EXPRESSION_BLOCK_SEPARATOR,
                    $expression
                );
                $documento = $this->getDocumento($selectorExpression, $processo);
                if (!$documento) {
                    return null;
                }
                $dadosFormulario = $this->getDadosFormulario($fomularioExpression, $documento);
                if (!$dadosFormulario) {
                    return null;
                }
                $field = str_replace('field=', '', $field);
                $jsonSchemaPropertyInfo = $this->getJsonSchemaPropertyInfo($field, $dadosFormulario);

                return $this->jsonSchemaHelper->getJsonSchemaPropertyByPath(
                    $field,
                    $dadosFormulario->getDataValue(),
                    $jsonSchemaPropertyInfo
                );
            },
            true
        );
    }

    /**
     * Retorna as informações da propriedade no json schema do formulario.
     *
     * @param string          $field
     * @param DadosFormulario $dadosFormulario
     *
     * @return JsonSchemaPropertyInfo
     *
     * @throws InvalidSchemaPropertyPathException
     */
    protected function getJsonSchemaPropertyInfo(
        string $field,
        DadosFormulario $dadosFormulario
    ): JsonSchemaPropertyInfo {
        return $this->getCachedData(
            sprintf(
                'ia_formulario_%s_schema_field_info_%s',
                $dadosFormulario->getFormulario()->getId(),
                $field
            ),
            function () use ($field, $dadosFormulario) {
                $jsonSchemaPropertyInfo = $this->jsonSchemaHelper->getSchemaPropertyInfoByPath(
                    $field,
                    $dadosFormulario->getFormulario()->getDataSchema()
                );
                if (!$jsonSchemaPropertyInfo->isValidPath()) {
                    throw new InvalidSchemaPropertyPathException($field);
                }

                return $jsonSchemaPropertyInfo;
            }
        );
    }

    /**
     * Retorna os dados de formulario para o documento e expressão de formulario informada.
     *
     * @param string    $expression
     * @param Documento $documento
     *
     * @return DadosFormulario|null
     */
    protected function getDadosFormulario(
        string $expression,
        Documento $documento
    ): ?DadosFormulario {
        return $this->getCachedData(
            sprintf(
                'ia:dados_formulario:%s:documento:%s',
                $expression,
                $documento->getId()
            ),
            function () use ($expression, $documento) {
                $dadosFormulario = null;
                $parsedExpression = [];
                parse_str($expression, $parsedExpression);
                $result = $this->dadosFormularioRepository->findByAdvanced(
                    [
                        'documento.id' => sprintf('eq: %s', $documento->getId()),
                        'formulario.id' => sprintf('eq: %s', $parsedExpression['formulario']),
                    ],
                    limit: 1,
                    offset: 0
                );

                if ($result['total'] > 0) {
                    $dadosFormulario = $result['entities'][0];
                    // Necessário para fazer o load dos dados para ficarem cacheados.
                    $dadosFormulario->getFormulario()->getDataSchema();
                }

                return $dadosFormulario;
            },
            true
        );
    }

    /**
     * Retorna o documento para o processo e expressão de seletor informado.
     *
     * @param string   $expression
     * @param Processo $processo
     *
     * @return Documento|null
     *
     * @throws SelectorNotFoundException
     */
    protected function getDocumento(string $expression, Processo $processo): ?Documento
    {
        return $this->getCachedData(
            sprintf(
                'ia:selector:%s:processo:%s:documento',
                $expression,
                $processo->getId()
            ),
            fn () => $this->getSelector($expression, $processo)->getDocumento($expression, $processo),
            true
        );
    }

    /**
     * Retorna o seletor de documento.
     *
     * @param string   $expression
     * @param Processo $processo
     *
     * @return SelectorInterface
     *
     * @throws SelectorNotFoundException
     */
    protected function getSelector(string $expression, Processo $processo): SelectorInterface
    {
        foreach ($this->selectors as $selector) {
            if ($selector->support($expression, $processo)) {
                return $selector;
            }
        }

        throw new SelectorNotFoundException($expression);
    }

    /**
     * Retorna o dado em cache ou carrega o dado pela função informada.
     *
     * @param string   $key
     * @param callable $getData
     *
     * @return mixed
     */
    protected function getCachedData(string $key, callable $getData, bool $allowNull = false): mixed
    {
        $cacheItem = $this->inMemoryCache->getItem(
            hash('sha256', $key)
        );
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        $data = $getData();
        if ($data || $allowNull) {
            $cacheItem->set($data);
            $this->inMemoryCache->save($cacheItem);
        }

        return $data;
    }

    /**
     * @param string $expression
     *
     * @return array
     */
    protected function getOperatorAndValueFromValueExpression(string $expression): array
    {
        $pos = strpos($expression, ':');
        if (false !== $pos) {
            $operator = substr($expression, 0, $pos);
            $value = substr($expression, $pos + 1);
        } else {
            $operator = $expression;
            $value = null;
        }

        return [
            OperatorEnum::from($operator),
            $value,
        ];
    }
}

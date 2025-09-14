<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use Closure;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Class ParameterParser.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ParameterParser
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @return Closure[]
     */
    protected function getParsers(): array
    {
        return [
            'bool' => fn ($param) => (bool) $param,
            'int' => fn ($param) => (int) $param,
            'string' => fn ($param) => (string) $param,
            'float' => fn ($param) => (float) $param,
            'toLower' => fn ($param) => strtolower($param),
            'toUpper' => fn ($param) => strtoupper($param),
            'ucFirst' => fn ($param) => ucfirst($param),
            'base64Encode' => fn ($param) => base64_encode($param),
            'base64Decode' => fn ($param) => base64_decode($param),
            'jsonEncode' => fn ($param) => json_encode($param),
            'jsonDecode' => fn ($param) => json_decode($param, true),
            'array' => fn ($param) => [$param],
            'env' => function ($param) {
                try {
                    return $this->parameterBag->get($param);
                } catch (Throwable) {
                    return $param;
                }
            },
        ];
    }

    /**
     * Returns supported provider types.
     *
     * @return string[]
     */
    public function getProvidedTypes(): array
    {
        return array_keys($this->getParsers());
    }

    /**
     * Parse string, ex: env:ucFirst:supp_core.administrativo_backend.nome_sistema.
     */
    public function parseString(string $value): mixed
    {
        $i = strpos($value, ':');

        if (false === $i) {
            return $value;
        }
        $parsers = $this->getParsers();
        $parseValue = $this->getValue($value);

        foreach ($this->getParsersFromValue($value) as $parseKey) {
            $parseValue = $this->parseDeep($parseValue, $parsers[$parseKey]);
        }

        return $parseValue;
    }

    /**
     * Parse array.
     */
    public function parseArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->parseArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->parseString($value);
            }
        }

        return $data;
    }

    /**
     * Generic parser.
     */
    public function parse(mixed $value): mixed
    {
        if (is_string($value)) {
            return $this->parseString($value);
        }

        if (is_array($value)) {
            return $this->parseArray($value);
        }

        return $value;
    }

    /**
     * Returns the to be parsed value.
     */
    protected function getValue(string $value): string
    {
        $i = strpos($value, ':');
        if (false === $i) {
            return $value;
        }
        $nextCheck = substr($value, $i + 1);

        return $this->getValue($nextCheck);
    }

    /**
     * Returns parsers from string.
     */
    public function getParsersFromValue(string $value, array &$parsers = []): array
    {
        $i = strpos($value, ':');
        if (false === $i) {
            return $parsers;
        }

        $nextCheck = substr($value, $i + 1);
        $parser = substr($value, 0, $i);
        if (!$parser) {
            return $parsers;
        }

        if (!in_array($parser, $this->getProvidedTypes())) {
            throw new RuntimeException(sprintf('Parser informado %s não é suportado', $parser));
        }

        $parsers[] = $parser;
        $this->getParsersFromValue($nextCheck, $parsers);

        return $parsers;
    }

    /**
     * @param $value
     * @param Closure $parser
     * @return mixed
     */
    private function parseDeep($value, Closure $parser): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->parseDeep($item, $parser);
            }
        } else {
            $value = $parser($value);
        }

        return $value;
    }
}

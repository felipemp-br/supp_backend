<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers;

use DateTime;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums\OperatorEnum;

/**
 * PlainDataComparerHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PlainDataComparerHelper
{
    /**
     * Executa a comparação dos dados.
     * O value é o dado da query e o valueToCompare é o que seria o dado do banco.
     *
     * @param mixed        $value
     * @param mixed        $mainValue
     * @param OperatorEnum $operatorEnum
     *
     * @return bool
     */
    public function compare(mixed $value, mixed $mainValue, OperatorEnum $operatorEnum): bool
    {
        switch ($operatorEnum) {
            case OperatorEnum::IN:
            case OperatorEnum::AT_LEAST_ONE_ELEMENT_EQUAL:
                return [] === array_diff($this->parseValueToEquality($value, $mainValue), $mainValue);
            case OperatorEnum::NOT_IN:
                return !$this->compare($value, $mainValue, OperatorEnum::IN);
            case OperatorEnum::ALL_ELEMENTS_NOT_EQUAL:
            case OperatorEnum::NO_ELEMENTS_EQUAL:
                return count(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::NOT_EQUAL)
                    )
                ) === count($mainValue);
            case OperatorEnum::AT_LEAST_ONE_ELEMENT_NOT_EQUAL:
                return count(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::NOT_EQUAL)
                    )
                ) > 0;
            case OperatorEnum::EQUAL:
                return $this->parseValueToEquality($value, $mainValue) == $mainValue;
            case OperatorEnum::NOT_EQUAL:
                return !$this->compare($value, $mainValue, OperatorEnum::EQUAL);
            case OperatorEnum::ALL_ELEMENTS_EQUAL:
            case OperatorEnum::NO_ELEMENTS_NOT_EQUAL:
                return !empty($mainValue) && count(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::EQUAL)
                    )
                ) === count($mainValue);
            case OperatorEnum::GREATER_THAN:
                return $mainValue > self::parseValueToEquality($value, $mainValue);
            case OperatorEnum::GREATER_THAN_EQUAL:
                return $mainValue >= self::parseValueToEquality($value, $mainValue);
            case OperatorEnum::LOWER_THAN:
                return $mainValue < self::parseValueToEquality($value, $mainValue);
            case OperatorEnum::LOWER_THAN_EQUAL:
                return $mainValue <= self::parseValueToEquality($value, $mainValue);
            case OperatorEnum::IS_NULL:
                return is_null($mainValue)
                    || '' === $mainValue
                    || (is_array($mainValue) && empty($mainValue));
            case OperatorEnum::IS_NOT_NULL:
                return !$this->compare($value, $mainValue, OperatorEnum::IS_NULL);
            case OperatorEnum::BETWEEN:
                @[$start, $end] = explode(',', (string) $value);

                return $mainValue >= $this->parseValueToEquality($start, $mainValue)
                    && $mainValue <= $this->parseValueToEquality($end, $mainValue);
            case OperatorEnum::CONTAINS:
                $pattern = str_replace(
                    '%',
                    '.*',
                    preg_quote($this->toText($this->parseValueToEquality($value, $mainValue)), '/')
                );

                return preg_match("/^$pattern$/i", $mainValue) > 0;
            case OperatorEnum::ALL_ELEMENTS_CONTAINS:
            case OperatorEnum::NO_ELEMENTS_NOT_CONTAINS:
                return !empty($mainValue) && count(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::CONTAINS)
                    )
                ) === count($mainValue);
            case OperatorEnum::NO_ELEMENTS_CONTAINS:
                return empty(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::CONTAINS)
                    )
                );
            case OperatorEnum::ALL_ELEMENTS_NOT_CONTAINS:
                return count($mainValue) === count(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::NOT_CONTAINS)
                    )
                );
            case OperatorEnum::AT_LEAST_ONE_ELEMENT_CONTAINS:
                return !empty(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::CONTAINS)
                    )
                );
            case OperatorEnum::NOT_CONTAINS:
                return !$this->compare($value, $mainValue, OperatorEnum::CONTAINS);
            case OperatorEnum::AT_LEAST_ONE_ELEMENT_NOT_CONTAINS:
                return !empty(
                    array_filter(
                        $mainValue,
                        fn ($item) => $this->compare($value, $item, OperatorEnum::NOT_CONTAINS)
                    )
                );
            default:
                return false;
        }
    }

    /**
     * @param mixed $value
     * @param mixed $mainValue
     *
     * @return mixed
     */
    private function parseValueToEquality(mixed $value, mixed $mainValue): mixed
    {
        switch (true) {
            case $mainValue instanceof DateTime:
                $pattern = '/^(\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2})?)$/';
                if (1 === preg_match($pattern, (string) $value)) {
                    return new DateTime($value);
                }

                return null;
            case is_array($mainValue) && !is_array($value):
                return [$this->toText($value)];
            case is_string($value):
                return $this->toText($value);
            default:
                return $value;
        }
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function toText($value): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower((string) $value));
    }
}

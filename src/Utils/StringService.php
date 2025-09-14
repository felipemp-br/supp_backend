<?php

declare(strict_types=1);
/**
 * /src/Utils/StringService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use DateTime;

/**
 * Class StringService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class StringService
{
    /**
     * @param $str
     *
     * @return string|string[]|null
     */
    public static function iso2ascii($str)
    {
        $str = strtr($str, "\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5".
            "\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed".
            "\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2".
            "\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa\xda".
            "\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf\xf8", 'aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNo'.
            'OoOoOoOoOoOoouUuUuUuUyYyaAso');
        $str = trim(@iconv('ISO-8859-1', 'ASCII//IGNORE', $str));
        $str = preg_replace('/[[:blank:]]+/', ' ', $str);

        return $str;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public static function iso2lower($str)
    {
        return strtolower(self::iso2ascii($str));
    }

    /**
     * @param $str
     *
     * @return string
     */
    public static function iso2upper($str)
    {
        return strtoupper(self::iso2ascii($str));
    }

    /**
     * @param $str
     *
     * @return string
     */
    public static function iso2short($str)
    {
        $str = preg_replace('/[^A-Z]/', ' ', self::iso2upper($str));
        $str = trim(preg_replace('/\s+/', ' ', $str));
        $tks = preg_split('/\s+/', $str, -1, PREG_SPLIT_NO_EMPTY);
        $res = [];
        foreach ($tks as $t) {
            if (in_array($t, ['DE', 'DO', 'DA', 'DAS', 'DOS'])) {
                continue;
            }
            if (strlen($t) <= 1) {
                continue;
            }
            $res[] = $t;
        }

        return trim(implode(' ', $res));
    }

    /**
     * @param $str
     *
     * @return string
     */
    public static function utf82short($str)
    {
        return self::iso2short(mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8'));
    }

    /**
     * @param $str
     *
     * @return false|int
     */
    public static function is_utf8($str)
    {
        // From http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
                   [\x09\x0A\x0D\x20-\x7E]            # ASCII
                 | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
                 |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
                 | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
                 |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
                 |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
                 | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
                 |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
             )*$%xs', $str);
    }

    public static function isFloat(?string $value): bool
    {
        return !is_null($value) && preg_match("/^\d+(\.\d+)?|\.\d+$/", $value);
    }

    public static function isInt(?string $value): bool
    {
        return !is_null($value) && preg_match("/^\d+$/", $value);
    }

    public static function isBool(?string $value): bool
    {
        return in_array($value, ['true', 'false', '1', '0', true, false, 1, 0, null], true);
    }

    public static function isNumber(?string $value): bool
    {
        return self::isFloat($value) || self::isInt($value);
    }

    /**
     * @param string|null $value
     * @param string      $format
     *
     * @return bool
     */
    public static function isBrazilianDate(?string $value, string $format = 'd/m/Y'): bool
    {
        $data = DateTime::createFromFormat($format, $value);
        return !is_null($value) && $data && $data->format($format) === $value;
    }

    /**
     * @param string|null $value
     * @param string      $format
     *
     * @return bool
     */
    public static function isDateTime(?string $value, string $format = 'Y-m-d\TH:i:s'): bool
    {
        $data = DateTime::createFromFormat($format, $value);
        return !is_null($value) && $data && $data->format($format) === $value;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function removeAccents(string $text): string {
        $search = [
            'à', 'á', 'â', 'ã', 'ä', 'å', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å',
            'ç', 'Ç',
            'è', 'é', 'ê', 'ë', 'È', 'É', 'Ê', 'Ë',
            'ì', 'í', 'î', 'ï', 'Ì', 'Í', 'Î', 'Ï',
            'ñ', 'Ñ',
            'ò', 'ó', 'ô', 'õ', 'ö', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',
            'ù', 'ü', 'ú', 'Ù', 'Ü', 'Ú',
            'ÿ', 'Ÿ',
        ];

        $replace = [
            'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'A',
            'c', 'C',
            'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',
            'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',
            'n', 'N',
            'o', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O',
            'u', 'u', 'u', 'U', 'U', 'U',
            'y', 'Y',
        ];

        return str_replace($search, $replace, $text);
    }

    public static function boolval(?string $value): bool
    {
        return in_array($value, ['true', '1', true, 1], true);
    }
}

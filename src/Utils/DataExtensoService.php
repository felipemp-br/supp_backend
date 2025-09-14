<?php
/**
 * App/Utils/DataExtensoService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

/**
 * Class DataExtensoService.
 */
class DataExtensoService
{
    /**
     * Retorna a data informada no formato extenso.
     *
     * @param $data
     * @param $separador
     *
     * @return string Data em extenso
     */
    public function dataPorExtenso($data, $separador)
    {
        $data = explode($separador, $data);

        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];

        switch ($mes) {
            case 1: $mes = 'janeiro';
                break;
            case 2: $mes = 'fevereiro';
                break;
            case 3: $mes = 'março';
                break;
            case 4: $mes = 'abril';
                break;
            case 5: $mes = 'maio';
                break;
            case 6: $mes = 'junho';
                break;
            case 7: $mes = 'julho';
                break;
            case 8: $mes = 'agosto';
                break;
            case 9: $mes = 'setembro';
                break;
            case 10: $mes = 'outubro';
                break;
            case 11: $mes = 'novembro';
                break;
            case 12: $mes = 'dezembro';
                break;
        }

        return "$dia de $mes de $ano";
    }

    /**
     * Converte o primeiro caractere da string para maisculo e os demais para minusculo.
     *
     * @param $string
     *
     * @return string
     */
    public function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1), 'UTF-8').mb_strtolower(mb_substr($string, 1), 'UTF-8');
    }
}

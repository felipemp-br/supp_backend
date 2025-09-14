<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Traits;

use DateTime;
use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados;

/**
 * Trait responsável pela criação de objetos reutilizáveis na integração com o Barramento.
 */
trait BarramentoUtil
{
    /**
     * Nome da fonte de dados utilizada para rastrar objetos persistido no Sapiens oriundos do Barramento.
     */
    protected string $fonteDeDados = 'BARRAMENTO_PEN';

    /**
     * Cria e retorna um objeto OrigemDados com informações desta integração.
     *
     * @return OrigemDados
     */
    protected function origemDadosFactory(): OrigemDados
    {
        $origemDados = new OrigemDados();
        $origemDados->setServico('barramento');
        $origemDados->setFonteDados($this->fonteDeDados);
        $origemDados->setDataHoraUltimaConsulta(new DateTime());

        return $origemDados;
    }

    /**
     * Obtém valor de um atributo do objeto caso ele exista e retorna null quando não existir
     * Solução para evitar uma infinidade de issets dentro dos métodos.
     *
     * @param stdClass|null $object
     * @param string|null $atributo
     *
     * @return mixed
     */
    protected function getValueIfExist(?stdClass $object, ?string $atributo): mixed
    {
        $value = null;
        if (property_exists($object, $atributo)) {
            $value = $object->{$atributo};
        }

        return $value;
    }

    /**
     * Converte string do padrão do barramento (Y-m-d\TH:i:sP) em DateTime.
     *
     * @param string|null $data
     *
     * @return DateTime|null
     * @throws Exception - Falha ao converter a string em DateTime
     */
    protected function converteDataBarramento(?string $dataString): DateTime|null|bool
    {
        if ($dataString) {
            try {
                $data = DateTime::createFromFormat('Y-m-d\TH:i:sP', $dataString);
            } catch (Exception) {
                throw new Exception('Erro ao converter a data do formatdo do barramento para o formato DateTime.');
            }

            if (!$data) {
                $dataString = str_replace('T', ' ', $dataString);
                $data = DateTime::createFromFormat('Y-m-d H:i:s.uP', $dataString);
            }
        }

        return $data ?? $dataString;
    }

    /**
     * Converte string para caixa alta na codificação UTF-8.
     *
     * @param string|null $string $string
     *
     * @return string|null
     */
    protected function upperUtf8(?string $string): ?string
    {
        if ($string) {
            $string = mb_strtoupper($string, 'UTF-8');
        }
        return $string;
    }

    /**
     * Retorna valor mapeado no array caso exista.
     *
     * @param array|null $arrayMapeamento
     * @param string|null $indice
     * @return mixed
     */
    protected function getValorMapeado(?array $arrayMapeamento, ?string $indice): mixed
    {
        if (!isset($arrayMapeamento[$indice])) {
            return false;
        }

        return $arrayMapeamento[$indice];
    }

    /**
     * Cria e insere atributo ao objeto caso haja valor.
     *
     * @param stdClass|null $objeto
     * @param string|null $nomeAtributo
     * @param string|null $valor
     *
     * @return stdClass|null
     */
    protected function setAtributoObjeto(?stdClass $objeto, ?string $nomeAtributo, ?string $valor): ?stdClass
    {
        if ($valor) {
            $objeto->{$nomeAtributo} = $valor;
        }

        return $objeto;
    }

    /**
     * Retorna arrays de hashes que pertencem ao processo;.
     *
     * @param stdClass|null $metadados Metadados do processo
     * @return array|null
     */
    protected function getHashesFromProcesso(?stdClass $metadados): ?array
    {
        if (!is_array($metadados->documento)) {
            $metadados->documento = [
                $metadados->documento,
            ];
        }

        $hashes = [];
        foreach ($metadados->documento as $documento) {
            if (isset($documento->retirado) && $documento->retirado) {
                continue;
            }
            if (!is_array($documento->componenteDigital)) {
                $documento->componenteDigital = [
                    $documento->componenteDigital,
                ];
            }

            foreach ($documento->componenteDigital as $componenteDigital) {
                $hashes[] = $componenteDigital->hash->_;
            }
        }

        // Procedimento recursivo caso haja processos apensados
        $processosApensados = $this->getValueIfExist($metadados, 'processoApensado');
        if ($processosApensados) {
            if (!is_array($processosApensados)) {
                $processosApensados = [$processosApensados];
            }

            foreach ($processosApensados as $processoApensado) {
                $this->getHashesFromProcesso($processoApensado);
            }
        }

        sort($hashes);

        return $hashes;
    }

    /**
     * Retorna arrays de hashes que pertencem ao documento avulso;.
     *
     * @param stdClass|null $metadados Metadados do documento avulso
     * @return array|null
     */
    protected function getHashesFromDocumentoAvulso(?stdClass $metadados): ?array
    {
        $hashes = [];
        if (!is_array($metadados->componenteDigital)) {
            $metadados->componenteDigital = [
                $metadados->componenteDigital,
            ];
        }

        foreach ($metadados->componenteDigital as $componenteDigital) {
            $hashes[] = $componenteDigital->hash->_;
        }

        sort($hashes);

        return $hashes;
    }

    /**
     * @param $needle
     * @param $needle_field
     * @param $haystack
     * @param $strict
     * @return bool
     */
    protected function inArrayField($needle, $needle_field, $haystack, $strict = false)
    {
        if ($strict) {
            foreach ($haystack as $item) {
                if (isset($item->$needle_field) && $item->$needle_field === $needle) {
                    return true;
                }
            }
        } else {
            foreach ($haystack as $item) {
                if (isset($item->$needle_field) && $item->$needle_field == $needle) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Retorna arrays de hashes que pertencem ao processo.
     *
     * @param stdClass|null $metadados Metadados do processo
     * @return array|null
     */
    protected function getHashesRecebidos(?stdClass $metadados, $hashesRecebidos): ?array
    {
        $hashes = [];
        if (isset($metadados->documento)) {

            if (!is_array($metadados->documento)) {
                $metadados->documento = [
                    $metadados->documento,
                ];
            }

            foreach ($metadados->documento as $documento) {
                if (isset($documento->retirado) && $documento->retirado) {
                    continue;
                }
                if (!is_array($documento->componenteDigital)) {
                    $documento->componenteDigital = [
                        $documento->componenteDigital,
                    ];
                }

                foreach ($documento->componenteDigital as $componenteDigital) {
                    foreach ($hashesRecebidos as $hs) {
                        if ($componenteDigital->hash->_ === $hs) {
                            $hashes[] = $componenteDigital->hash->_;
                        }
                    }
                }
            }
        }

        if (isset($metadados->componenteDigital)) {

            if (!is_array($metadados->componenteDigital)) {
                $metadados->componenteDigital = [
                    $metadados->componenteDigital,
                ];
            }

            foreach ($metadados->componenteDigital as $componenteDigital) {
                foreach ($hashesRecebidos as $hs) {
                    if ($componenteDigital->hash->_ === $hs) {
                        $hashes[] = $componenteDigital->hash->_;
                    }
                }
            }
        }

        sort($hashes);

        return $hashes;
    }
}

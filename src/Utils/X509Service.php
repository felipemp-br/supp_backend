<?php

/**
 * @noinspection LongLine
 * @noinspection PhpMultipleClassDeclarationsInspection
 * @phpcs:disable Generic.Files.LineLength.TooLong
 *
 */
declare(strict_types=1);
/**
 * /src/Utils/X509Service.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use DateTime;
use DateTimeZone;
use phpseclib3\File\X509;
use phpseclib3\Math\BigInteger;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Class X509Service.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class X509Service
{
    /**
     * X509Service constructor.
     *
     * @param ParameterBagInterface $parameterBag
     * @param SuppParameterBag $suppParameterBag
     */
    public function __construct(protected readonly ParameterBagInterface $parameterBag, protected readonly SuppParameterBag $suppParameterBag)
    {
    }


    /**
     * Recupera dados de um certificado (PEM) passado por parâmetro
     *
     * @param string $pem
     *
     * @return array|null [
     * 'cn' => string,          <br/>
     * 'username' => string,    <br/>
     * 'nome' => string|null,   <br/>
     * 'titular_cpf' => string|null,    <br/>
     * 'titular_cnpj' => string|null,   <br/>
     * 'responsavel_nome' => string|null,<br/>
     * 'responsavel_cpf' => string|null,<br/>
     * 'emissor' => string|null,        <br/>
     * 'serialNumber' => string|null,   <br/>
     * 'tipo' => string|null,       <br/>
     * 'naturezaJuridica' => string,<br/>
     * 'equipamento' => bool,       <br/>
     * 'validadeInicio' => string,  <br/>
     * 'validadeFim' => string, <br/>
     * 'descricao' => string,   <br/>
     * 'institucional' => bool, <br/>
     * 'assinadoPor' => string<br/>
     *]
     */
    public function getCredentials(string $pem): ?array
    {
        if (!$this->isCertificateValid($pem)) {
            return null;
        }

        $x509 = new X509();
        $c = $x509->loadX509($pem);

        if (empty($c)) {
            return null;
        }

        $cn = $this->getCommonName($x509, $c);
        $username = null;
        $nome = $this->getNome($x509);
        $titular_cpf = null;
        $titular_cnpj = null;
        $responsavel_nome = null;
        $responsavel_cpf = null;
        $emissor = $this->getEmissor($x509, $c);
        $serialNumber = $this->getSerialNumber($c);
        $tipo = $this->getTipo($c);
        $equipamento = false;
        $validadeInicio = $this->getValidadeInicio($c);
        $validadeFim = $this->getValidadeFim($c);


        // CPF
        //      Para certificado de Pessoa Física:
        //      OID = 2.16.76.1.3.1 - nas primeiras 8 posições, a data de nascimento (ddmmaaaa) do titular,
        //      nas 11 posições subsequentes, o Cadastro de Pessoa Física (CPF) do titular

        //      Para certificados de Pessoa Jurídica:
        //      OID = 2.16.76.1.3.4 - nas primeiras 8 posições, a data de nascimento (ddmmaaaa) do responsável,
        //      nas 11 posições subsequentes, o Cadastro de Pessoa Física (CPF) do responsável
        //
        // CNPJ
        //      Para certificados de Pessoa Jurídica:
        //      OID = 2.16.76.1.3.3 - nas 14 posições o número do CNPJ da pessoa jurídica titular do certificado
        if (isset($c['tbsCertificate']['extensions']) && $x509->getExtension('id-ce-subjectAltName')) {
            $subjectAltNames = $x509->getExtension('id-ce-subjectAltName');
            foreach ($subjectAltNames as $extensionValue) {
                // Para certificados de Equipamento:  contém dNSName
                if (isset($extensionValue['dNSName'])) {
                    $equipamento = !empty($extensionValue['dNSName']);
                }
                if (isset($extensionValue['otherName']['type-id']) &&
                    isset($extensionValue['otherName']['value']['octetString'])) {
                    // CPF do titular do (certificado de PF)
                    if ('2.16.76.1.3.1' === $extensionValue['otherName']['type-id']) {
                        $titular_cpf = $username = substr($extensionValue['otherName']['value']['octetString'], 8, 11);
                    }
                    // CPF do responsável  (certificado de PJ/Equipamento)
                    if ('2.16.76.1.3.4' === $extensionValue['otherName']['type-id']) {
                        $responsavel_cpf = substr($extensionValue['otherName']['value']['octetString'], 8, 11);
                    }
                    // Nome do responsável (certificado de PJ/Equipamento)
                    if ('2.16.76.1.3.2' === $extensionValue['otherName']['type-id']) {
                        $responsavel_nome = $extensionValue['otherName']['value']['octetString'];
                    }
                    // CNPJ do titular (certificado PJ/Equipamento)
                    if ('2.16.76.1.3.3' === $extensionValue['otherName']['type-id']) {
                        $titular_cnpj = $username = substr($extensionValue['otherName']['value']['octetString'], 0, 14);
                    }
                    // Para certificados de Equipamento CNPJ
                    if ('2.16.76.1.3.8' === $extensionValue['otherName']['type-id']) {
                        $equipamento = true;
                    }
                }
            }
        }


        // CNPJ
        // Para certificados de Equipamento:
        // Quando o titular_cnpj não é encontrado no OID 2.16.76.1.3.3, pode estar no atributo SERIALNUMBER do subject
        if (!$titular_cnpj && isset($c['tbsCertificate']['subject']['rdnSequence'])) {
            foreach ($c['tbsCertificate']['subject']['rdnSequence'] as $rdnSequence) {
                foreach ($rdnSequence as $sequence) {
                    if (isset($sequence['type'])
                        && 'id-at-serialNumber' === $sequence['type']
                        && isset($sequence['value']['printableString'])) {
                           $titular_cnpj = $username = $sequence['value']['printableString'];
                           break 2;
                    }
                }
            }
        }


        // Natureza jurídica
        if (empty($titular_cnpj) && empty($titular_cpf)) {
            // se for uma CA não tem CPF nem CNPJ
            $natureza = null;
        } else {
            $natureza = empty($titular_cnpj) ? 'PF' : 'PJ';
        }


        // Institucional
        $institucional = $this->isInstitucional($titular_cnpj, $cn);


        // Descrição
        $descricao = match ($natureza) {
            'PF' => 'Certificado '.$tipo.' de Pessoa Física ('.$nome.'  CPF:'.$titular_cpf.', SN:'.$serialNumber.'), emitido por '.$emissor,
            'PJ' => 'Certificado '.$tipo.' de Pessoa Jurídica ('.$nome.'  CNPJ:'.$titular_cnpj.', SN:'.$serialNumber.'), emitido por '.$emissor,
            default => 'Certificado de CA',
        };

        // Assinado por
        $assinadoPor = match ($natureza) {
            'PF' => $nome.', utilizando certificado '.$tipo.' de Pessoa Física (CPF:'.$titular_cpf.', SN:'.$serialNumber.'), emitido por '.$emissor,
            'PJ' => $nome.', utilizando certificado '.$tipo.' de Pessoa Jurídica (CNPJ:'.$titular_cnpj.', SN:'.$serialNumber.'), emitido por '.$emissor,
            default => 'CA',
        };



        return [
            'cn' => $cn,                // *
            'username' => $username,    // *
            'nome' => $nome,            // *
            'titular_cpf' => $titular_cpf,
            'titular_cnpj' => $titular_cnpj,
            'responsavel_nome' => $responsavel_nome,
            'responsavel_cpf' => $responsavel_cpf,
            'emissor' => $emissor,
            'serialNumber' => $serialNumber,
            'tipo' => $tipo,
            'naturezaJuridica' => $natureza,
            'equipamento' => $equipamento,
            'validadeInicio' => $validadeInicio,
            'validadeFim' => $validadeFim,
            'descricao' => $descricao,
            'institucional' => $institucional,
            'assinadoPor' => $assinadoPor,
        ];
    }


    /**
     * Formatar a data hora em d/m/Y H:i:s T
     *
     * @param string $dateString
     * @return string
     */
    private function formatDateTime(string $dateString): string
    {
        try {
            // Cria um objeto DateTime a partir da string
            $date = DateTime::createFromFormat('D, d M Y H:i:s O', $dateString);

            if ($date) {
                $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
                // Formata a data dia/mês/ano hora:minuto:segundos fuso horário
                return $date->format('d/m/Y H:i:s T');
            }
            // Retorna o valor original se a conversão falhar
            return $dateString;
        } catch (Throwable) {
            // Retorna o valor original se ocorrer um erro
            return $dateString;
        }
    }


    /**
     * Recuperar o Common Name (CN)
     *
     * @param X509 $x509
     * @param mixed $cert
     * @return string|null
     */
    private function getCommonName(X509 $x509, mixed $cert): ?string
    {
        $cn = null;

        // Common Name (CN)
        if (isset($x509->getDNProp('id-at-commonName')[0])) {
            $cn = $x509->getDNProp('id-at-commonName')[0];
        }

        // Common Name (CN) se o anterior não der certo
        if (empty($cn)) {
            if (isset($cert['tbsCertificate']['subject']['rdnSequence'])) {
                foreach ($cert['tbsCertificate']['subject']['rdnSequence'] as $rdnSequence) {
                    foreach ($rdnSequence as $attr) {
                        if (isset($attr['type']) &&
                            ('id-at-commonName' === $attr['type'])) {
                            $cn = $attr['value']['utf8String'];
                            break 2;
                        }
                    }
                }
            }
        }

        return $cn;
    }


    /**
     * Recuperar o nome do titular
     *
     * @param X509 $x509
     * @return string|null
     */
    private function getNome(X509 $x509): ?string
    {
        $nome = null;

        // Nome
        if (isset($x509->getDNProp('id-at-commonName')[0])) {
            $cn = $x509->getDNProp('id-at-commonName')[0];
            $nome =  explode(':', $cn)[0];
        }

        return $nome;
    }


    /**
     * Recuperar emissor do certificado
     *
     * @param X509 $x509
     * @param mixed $cert
     * @return string|null
     */
    private function getEmissor(X509 $x509, mixed $cert): ?string
    {
        $emissor = null;

        // Emissor do certificado
        if (isset($cert['tbsCertificate']['issuer'])) {
            // Obtém os atributos do issuer
            $emissor = $x509->getIssuerDNProp('id-at-commonName')[0] ?? null;
        }

        return $emissor;
    }


    /**
     * Recuperar número de serial do certificado
     *
     * @param mixed $cert
     * @return string|null
     */
    private function getSerialNumber(mixed $cert): ?string
    {
        $serialNumber = null;

        // SerialNumber do certificado
        if (isset($cert['tbsCertificate']['serialNumber'])) {
            $serialNumber = $cert['tbsCertificate']['serialNumber'];
            if ($serialNumber instanceof BigInteger) {
                // Converte o serialNumber (Math/BigInteger) para string
                $serialNumber = $serialNumber->toString();
            } else {
                $serialNumber = (string) $serialNumber;
            }
        }

        return $serialNumber;
    }

    /**
     * Recuperar o tipo de certificado A1, A3
     *
     * @param mixed $cert
     * @return string|null
     */
    private function getTipo(mixed $cert): ?string
    {
        $tipo = null;

        // Tipo A1, A3
        if (isset($cert['tbsCertificate']['extensions'])) {
            foreach ($cert['tbsCertificate']['extensions'] as $extension) {
                // Verifica se a extensão Certificate Policies está presente
                if ($extension['extnId'] === 'id-ce-certificatePolicies') {
                    foreach ($extension['extnValue'] as $extensionValue) {
                        $policyIdentifier = $extensionValue['policyIdentifier'] ?? '';
                        if (str_contains($policyIdentifier, '2.16.76.1.2.1.')) {
                            $tipo = 'A1';
                            break 2;
                        }
                        if (str_contains($policyIdentifier, '2.16.76.1.2.3.')) {
                            $tipo = 'A3';
                            break 2;
                        }
                    }
                }
            }
        }

        return $tipo;
    }

    /**
     * Recupera o início da validade do certificado
     *
     * @param mixed $cert
     * @return string|null
     */
    private function getValidadeInicio(mixed $cert): ?string
    {
        $validadeInicio = null;
        // Validade Início
        // Verifica se os campos de validade estão presentes
        if (isset($cert['tbsCertificate']['validity'])) {
            // Recupera as datas de início e fim da validade
            $validadeInicio = $this->formatDateTime($cert['tbsCertificate']['validity']['notBefore']['utcTime']);
        }

        return $validadeInicio;
    }

    /**
     * Recupera o fim da validade do certificado
     *
     * @param mixed $cert
     * @return string|null
     */
    private function getValidadeFim(mixed $cert): ?string
    {
        $validadeFim = null;
        // Validade Fim
        // Verifica se os campos de validade estão presentes
        if (isset($cert['tbsCertificate']['validity'])) {
            // Recupera as datas de início e fim da validade
            $validadeFim = $this->formatDateTime($cert['tbsCertificate']['validity']['notAfter']['utcTime']);
        }

        return $validadeFim;
    }

    /**
     * Verificar se um CNPJ ou CN é institucional
     *
     * @param $cnpj
     * @param $cn
     * @return bool
     */
    private function isInstitucional($cnpj, $cn): bool
    {
        // Recupera a configuração do módulo Assinatura
        $assinaturaConfig = $this->getAssinaturaConfig();

        // Se o CNPJ passado por parâmetro estiver contido na lista de CNPJ institucionais
        if (isset($assinaturaConfig['cnpj'])) {
            if (in_array($cnpj, $assinaturaConfig['cnpj'])) {
                return true;
            }
        }

        // Ou se o CN passado por parâmetro estiver contido na lista de CN institucionais
        if (isset($assinaturaConfig['cn'])) {
            return in_array($cn, $assinaturaConfig['cn']);
        }

        return false;
    }

    /**
     * Recupera a configuração de módulo referente a assinaturas
     *
     * @return array
     */
    public function getAssinaturaConfig(): array
    {
        // Ambiente (prod,dev)
        if ($this->parameterBag->has('kernel.environment')) {
            $ambiente = mb_strtolower($this->parameterBag->get('kernel.environment'));
        } else {
            $ambiente = 'prod';
        }

        // Configuração do módulo Assinatura
        $assinaturaConfig = null;
        if ($this->suppParameterBag->has('supp_core.administrativo_backend.assinatura.config')) {
            $assinaturaConfig = $this->suppParameterBag->get('supp_core.administrativo_backend.assinatura.config');
        }

        // se não encontrou a configuração do módulo Assinatura
        if (empty($assinaturaConfig)) {
            $assinaturaConfig = <<<EOD
            {
                "ambiente": {
                    "dev": {
                        "cnpj": [
                            "26994558008027",
                            "26994558000123"                        
                        ],
                        "cn": [
                            "*.agu.gov.br",
                            "SAPIENS",
                            "*.AGU.GOV.BR"                        
                        ],
                        "CAdES": {
                            "active": true,
                            "test": true
                        },
                        "PAdES": {
                            "active": true,
                            "orientation": "VR-TD",
                            "visible": true,
                            "test": true,
                            "convertToHtmlAfterRemove": true,
                            "imageBase64": ""
                        }
                    },
                    "prod": {
                        "cnpj": [
                            "26994558008027",
                            "26994558000123"
                        ],
                        "cn": [
                            "*.agu.gov.br",
                            "SAPIENS",
                            "*.AGU.GOV.BR"
                        ],
                        "CAdES": {
                            "active": true,
                            "test": false
                        },
                        "PAdES": {
                            "active": true,
                            "orientation": "HB-LR",
                            "visible": true,
                            "convertToHtmlAfterRemove": true,
                            "test": false,
                            "imageBase64": ""
                        }
                    }
                }
            }        
            EOD;

            $assinaturaConfig = json_decode($assinaturaConfig, true);
        }

        return $assinaturaConfig['ambiente'][$ambiente];
    }

    /**
     * Verificar se é um certificado no formato PEM válido
     *
     * @param string $pem
     * @return bool
     */
    public function isCertificateValid(string $pem): bool
    {
        try {
            $x509 = new X509();
            if (false === $x509->loadX509($pem)) {
                return false;
            } else {
                return true;
            }
        } catch (Throwable){
            return false;
        }
    }
}

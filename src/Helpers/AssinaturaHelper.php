<?php

declare(strict_types=1);

/**
 * /src/Helpers/AssinaturaHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers;

use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * AssinaturaHelper
 */
class AssinaturaHelper
{
    /**
     * @param SuppParameterBag          $parameterBag
     * @param AssinaturaLogHelper       $logger
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param AssinaturaResource        $assinaturaResource
     * @param TransactionManager        $transactionManager
     */
    public function __construct(
        protected SuppParameterBag $parameterBag,
        protected AssinaturaLogHelper $logger,
        protected readonly ComponenteDigitalResource $componenteDigitalResource,
        protected readonly AssinaturaResource $assinaturaResource,
        protected readonly TransactionManager $transactionManager,
    ) {
    }

    /**
     * Retorna o tipo e a pessoa do certificado utilizado na assinatura.
     * Ex: A1_PF, A1_PJ, A3_PF, A3_PJ.
     *
     * @param string $signatureBase64
     *
     * @return string|null
     */
    public function certificateDetails(string $signatureBase64): ?string
    {
        // Assinatura em modo teste
        if ('YXNzaW5hdHVyYV90ZXN0ZQ==' === trim($signatureBase64)) {
            return null;
        }

        $signerProxyParams = [];
        $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

        if ($signerProxy) {
            $signerProxyParams = explode(' ', $signerProxy);
        }

        $params = [
            'java',
            '-jar',
            '/usr/local/bin/supp-signer.jar',
            '--mode=signature-details',
            '--signatureBase64='.$signatureBase64,
        ];

        $process = new Process(array_merge($params, $signerProxyParams));

        try {
            $process->run();
        } catch (Throwable $t) {
            $this->logger->error('SUPP-Signer: '.$t->getMessage());

            return null;
        }

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $outSuccessPos = strpos($output, 'OutSuccess:');
            if (false !== $outSuccessPos) {
                return trim(substr($output, $outSuccessPos + 11), " \n\r");
            }

            return trim($output, " \n\r");
        } else {
            $output = $process->getOutput();
            $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

            return null;
        }
    }

    /**
     * Retorna contadores referentes às assinaturas contidas no PDF
     *
     * $signaturesCounters['isValidPdf'] = se o arquivo é um PDF válido<br/>
     * $signaturesCounters['counters']['total'] = Quantidade Total de assinaturas<br/>
     * $signaturesCounters['counters']['valid'] = Quantidade de assinaturas válidas<br/>
     * $signaturesCounters['counters']['invalid'] = Quantidade de assinaturas inválidas<br/>
     * $signaturesCounters['counters']['pf'] = Quantidade de assinaturas válidas com certificado de pessoa física<br/>
     * $signaturesCounters['counters']['pj'] = Quantidade de assinaturas válidas com certificado de pessoa jurídica<br/>
     *
     * Obs: Quando a assinatura é inválida, não é possível determinar a natureza da pessoa (física/jurídica).
     *
     * @param int $componenteDigitalID
     *
     * @return array|null
     *
     * <pre>
     * Ex de retorno, array associativo:
     * stdClass Object ( [isValidPdf] => true [counters] =>
     * stdClass Object ( [total] => 3 [valid] => 3 [invalid] => 0 [pf] => 2 [pj] => 1 ) )
     *
     * Array associativo do objeto baseado no JSON schema:
     *
     * {
     *   "$schema": "http://json-schema.org/draft-04/schema#",
     *   "type": "object",
     *   "properties": {
     *      "isValidPdf": {
     *          "type": "boolean"
     *      },
     *      "counters": {
     *          "type": "object",
     *          "properties": {
     *              "total": {
     *                  "type": "integer"
     *              },
     *              "valid": {
     *                  "type": "integer"
     *              },
     *              "invalid": {
     *                  "type": "integer"
     *              },
     *              "pf": {
     *                   "type": "integer"
     *               },
     *              "pj": {
     *                   "type": "integer"
     *               }
     *          },
     *          "required": [
     *              "total",
     *              "valid",
     *            "invalid",
     *                 "pf",
     *                 "pj"
     *          ]
     *      }
     *   },
     *   "required": [
     *      "isValidPdf",
     *      "counters"
     *   ]
     * }
     * </pre>
     */
    public function signaturesCounters(int $componenteDigitalID): ?array
    {
        try {
            $transactionId = $this->transactionManager->getCurrentTransactionId() ?: $this->transactionManager->begin();
            // Recupera o conteúdo do PDF
            $pdfContent = $this->componenteDigitalResource->download(
                $componenteDigitalID,
                $transactionId
            )?->getConteudo();

            if (empty($pdfContent)) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $hash = hash('SHA256', $pdfContent);
            $filePdf = '/tmp/'.$hash.'.pdf';
            file_put_contents($filePdf, $pdfContent);

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=pdf-signatures-counters',
                '--hash='.$hash,
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
                unlink($filePdf);
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());
                if (is_file($filePdf)) {
                    unlink($filePdf);
                }

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return json_decode(
                        trim(substr($output, $outSuccessPos + 11), " \n\r"),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );
                } else {
                    return json_decode(
                        trim($output, " \n\r"),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );
                }
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * <pre>
     * Converte uma cadeia de certificados no formato PEM para o formato PkiPath em Base64.
     * Caso receba por parâmetro uma cadeia de certificados no formato PkiPath, retornará a mesma cadeia.
     *
     * Obs:
     * Existem casos no BD que dados da coluna cadeia_certificado, que deveriam estar no formato PEM, estão em PkiPath.
     * </pre>
     *
     * @param string|null $cadeiaPem Cadeia PEM (-----BEGIN CERTIFICATE-----[Base64]-----END CERTIFICATE-----)
     *
     * @return string|null null = ocorreu algum erro
     */
    public function convertPemToPkiPath(?string $cadeiaPem): ?string
    {
        try {
            if (empty($cadeiaPem)) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=convert-pem-pkipath',
                '--pem='.trim($cadeiaPem, " \n\r"),
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return trim(substr($output, $outSuccessPos + 11), " \n\r");
                }

                return trim($output, " \n\r");
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * <pre>
     * Converte uma cadeia de certificados no formato PkiPath, em Base64, para o formato PEM.
     * Caso receba por parâmetro uma cadeia de certificados no formato PEM, retornará a mesma cadeia.
     *
     * Obs:
     * Existem casos no BD que dados da coluna cadeia_certificado, que deveriam estar no formato PEM, estão em PkiPath.
     * </pre>
     * @param string|null $cadeiaPkiPath Cadeia PkiPath
     * @return string|null null = ocorreu algum erro
     */
    public function convertPkiPathToPem(?string $cadeiaPkiPath): ?string
    {
        try {
            if (empty($cadeiaPkiPath)) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=convert-pkipath-pem',
                '--pkipath='.trim($cadeiaPkiPath, " \n\r"),
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return trim(substr($output, $outSuccessPos + 11), " \n\r");
                } else {
                    return trim($output, " \n\r");
                }
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Retorna a quantidade de assinaturas contidas num PDF, sem verificar a validade das mesmas
     *
     * @param string|null $pdfContent
     *
     * @return int -1 = erro ao abrir PDF, 0 = zero assinaturas
     */
    public function getCountSignature(?string $pdfContent): int
    {
        try {
            if (empty($pdfContent)) {
                return 0;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $hash = hash('SHA256', $pdfContent);
            $filePdf = '/tmp/'.$hash.'.pdf';
            file_put_contents($filePdf, $pdfContent);

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=pdf-count-signatures',
                '--hash='.$hash,
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
                unlink($filePdf);
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());
                if (is_file($filePdf)) {
                    unlink($filePdf);
                }

                return -1;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return (int) trim(substr($output, $outSuccessPos + 11), " \n\r");
                } else {
                    return (int) trim($output, " \n\r");
                }
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return -1;
            }
        } catch (Throwable) {
            return -1;
        }
    }

    /**
     * Retorna a cadeia de certificados PEM, caso exista e seja válida.<br/>
     * Tenta efetuar as devidas conversões dos conteúdos CadeiaCertificadoPkiPath e CadeiaCertificadoPEM.
     *
     * @param Assinatura|null $assinatura   Entidade Assinatura
     * @param string          $transactionId
     *
     * @return string|null null = ocorreu algum erro
     */
    public function getOrGeneratePem(
        ?Assinatura $assinatura,
        string $transactionId,
    ): ?string {
        if (null === $assinatura) {
            return null;
        }

        // PkiPath pode não existir no BD
        $pkiPath = $assinatura->getCadeiaCertificadoPkiPath() ;
        // PEM tem que existir no BD, pois é not null
        $pem = $assinatura->getCadeiaCertificadoPEM();

        $pkiPathEncoding = $this->getCertPathEncoding($pkiPath);
        $pemEncoding = $this->getCertPathEncoding($pem);

        if (empty($pkiPathEncoding) && empty($pemEncoding)) {
            // Se é um PkiPath inválido e um PEM inválido
            return null;
        }

        if ("PEM" === $pemEncoding) {
            // PkiPath válido com encoding correto
            if (empty($pkiPathEncoding) || "PkiPath" !== $pkiPathEncoding) {
                // Caso o PkiPath não seja válido, transformo o PEM em PkiPath tento atualizar o BD
                $pkiPath = $this->convertPemToPkiPath($pem);
                if (!empty($pkiPath)) {
                    // se $pkiPath tiver algum valor é porque conseguiu realizar conversão
                    $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                }
            }

            // caso exista PkiPath e seja válido, retorna ele mesmo
            return $pem;
        } elseif (empty($pemEncoding)) {
            // Neste ponto o PEM é inválido, então o PkiPath tem que ser válido,
            // quando ambos são inválidos, há um return null no início da função
            // Gerar PEM a partir do PkiPath
            // convertPkiPathToPem() gera log de erro
            $pem = $this->convertPkiPathToPem($pkiPath);
            if (!empty($pem)) {
                // se $pem tiver algum valor é porque conseguiu realizar conversão
                if ("PEM" === $pkiPathEncoding) {
                    $pkiPath = $this->convertPemToPkiPath($pem);
                }
                if (!empty($pkiPath)) {
                    // se $pkiPath tiver algum valor é porque conseguiu realizar conversão
                    $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                }

                return $pem;
            } else {
                $this->logger->error('Erro ao converter PkiPath para PEM da assinatura ID:'.$assinatura->getId());

                return null;
            }
        } elseif ("PkiPath" === $pemEncoding) {
            // Se na coluna PEM tem um pkipath
            // convertPkiPathToPem() gera log de erro
            $pkiPath = $pem;
            $pem = $this->convertPkiPathToPem($pkiPath);
            if (!empty($pem)) {
                // se $pem tiver algum valor é porque conseguiu realizar conversão
                $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                return $pem;
            } else {
                $this->logger->error('Erro ao converter PkiPath para PEM da assinatura ID:'.$assinatura->getId());
                return null;
            }
        }
        return $pem;
    }

    /**
     * Retorna a cadeia de certificados PkiPath, caso exista e seja válida.<br/>
     * Tenta efetuar as devidas conversões dos conteúdos CadeiaCertificadoPkiPath e CadeiaCertificadoPEM.
     *
     * @param Assinatura|null $assinatura   Entidade Assinatura
     * @param string          $transactionId
     *
     * @return string|null null = ocorreu algum erro
     */
    public function getOrGeneratePkiPath(
        ?Assinatura $assinatura,
        string $transactionId,
    ): ?string {
        if (null === $assinatura) {
            return null;
        }

        // PkiPath pode não existir no BD
        $pkiPath = $assinatura->getCadeiaCertificadoPkiPath() ;
        // PEM tem que existir no BD, pois é not null
        $pem = $assinatura->getCadeiaCertificadoPEM();

        $pkiPathEncoding = $this->getCertPathEncoding($pkiPath);
        $pemEncoding = $this->getCertPathEncoding($pem);

        if (empty($pkiPathEncoding) && empty($pemEncoding)) {
            // Se é um PkiPath inválido e um PEM inválido
            return null;
        }

        if ("PkiPath" === $pkiPathEncoding) {
            // PkiPath válido com encoding correto
            if (empty($pemEncoding) || "PEM" !== $pemEncoding) {
                // Caso o PEM não seja válido, transformo o PkiPath em PEM e tento atualizar o BD
                $pem = $this->convertPkiPathToPem($pkiPath);
                if (!empty($pem)) {
                    // se $pem tiver algum valor é porque conseguiu realizar conversão
                    $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                }
            }

            // caso exista PkiPath e seja válido, retorna ele mesmo
            return $pkiPath;
        } elseif (empty($pkiPathEncoding)) {
            // Neste ponto o PkiPath é inválido, então o PEM tem que ser válido,
            // quando ambos são inválidos, há um return null no início da função
            // Gerar PkiPath a partir do PEM
            // convertPemToPkiPath() gera log de erro
            $pkiPath = $this->convertPemToPkiPath($pem);
            if (!empty($pkiPath)) {
                // se $pkiPath tiver algum valor é porque conseguiu realizar conversão
                if ("PkiPath" === $pemEncoding) {
                    $pem = $this->convertPkiPathToPem($pkiPath);
                }
                if (!empty($pem)) {
                    // se $pem tiver algum valor é porque conseguiu realizar conversão
                    $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                }
                return $pkiPath;
            } else {
                $this->logger->error('Erro ao converter PEM para PkiPath da assinatura ID:'.$assinatura->getId());
                return null;
            }
        } elseif ("PEM" === $pkiPathEncoding) {
            // Se na coluna pkipath tem um PEM
            // convertPemToPkiPath() gera log de erro
            $pem = $pkiPath;
            $pkiPath = $this->convertPemToPkiPath($pem);
            if (!empty($pkiPath)) {
                // se $pkiPath tiver algum valor é porque conseguiu realizar conversão
                $this->updateAssinatura($assinatura, $transactionId, $pkiPath, $pem);
                return $pkiPath;
            } else {
                $this->logger->error('Erro ao converter PEM para PkiPath da assinatura ID:'.$assinatura->getId());
                return null;
            }
        }

        return $pkiPath;
    }

    /**
     * @param Assinatura  $assinatura
     * @param string      $transactionId
     * @param string|null $pkiPath
     * @param string|null $pem
     *
     * @return bool
     */
    public function updateAssinatura(
        Assinatura $assinatura,
        string $transactionId,
        ?string $pkiPath,
        ?string $pem
    ): bool {
        try {
            if (empty($pem) && empty($pkiPath)) {
                return false;
            }
            /** @var AssinaturaDTO $assinaturaDTO */
            $assinaturaDTO = $this->assinaturaResource->getDtoForEntity(
                $assinatura->getId(),
                AssinaturaDTO::class,
                null,
                $assinatura
            );
            if (!empty($pkiPath)) {
                // Atualizar CadeiaCertificadoPkiPath (DTO) / certificados_pki_path (BD)
                $assinaturaDTO->setCadeiaCertificadoPkiPath($pkiPath);
            }
            if (!empty($pem)) {
                // Atualizar CadeiaCertificadoPEM (DTO) / certificados_pem (BD)
                $assinaturaDTO->setCadeiaCertificadoPEM($pem);
            }

            $this->assinaturaResource->update(
                $assinatura->getId(),
                $assinaturaDTO,
                $transactionId,
                true
            );
            // Se chegou aqui é porque o PEM válido

            return true;
        } catch (Throwable $t) {
            $this->logger->error(
                'Erro ao atualizar a assinatura ID:'.$assinatura->getId().'  - '.$t->getMessage()
            );
            return false;
        }
    }

    /**
     * Verifica se um PkiPath é válido
     *
     * @param string|null $pkiPathBase64
     *
     * @return bool|null true = válido, false = inválido, null = ocorreu algum erro
     */
    public function isValidPkiPath(?string $pkiPathBase64) : ?bool
    {
        try {
            if (empty($pkiPathBase64)) {
                return false;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=is-valid-pkipath',
                '--pkipath='.$pkiPathBase64,
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return (bool) filter_var(
                        trim(substr($output, $outSuccessPos + 11), " \n\r"),
                        FILTER_VALIDATE_BOOLEAN
                    );
                }

                return (bool) filter_var(trim($output, " \n\r"), FILTER_VALIDATE_BOOLEAN);
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Verifica se um PEM é válido
     *
     * @param string|null $pem
     *
     * @return bool|null true = válido, false = inválido, null = ocorreu algum erro
     */
    public function isValidPem(?string $pem) : ?bool
    {
        try {
            if (empty($pem)) {
                return false;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=is-valid-pem',
                '--pem='.$pem,
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    return (bool) filter_var(
                        trim(substr($output, $outSuccessPos + 11), " \n\r"),
                        FILTER_VALIDATE_BOOLEAN
                    );
                }

                return (bool) filter_var(trim($output, " \n\r"), FILTER_VALIDATE_BOOLEAN);
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }


    /**
     * Tenta recuperar o cert path encoding do conteúdo / cadeia de certificados
     *
     * @param string|null $content cadeia de certificados
     *
     * @return string|null PEM | PkiPath | null
     */
    public function getCertPathEncoding(?string $content) : ?string
    {
        try {
            if (empty($content)) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=cert-path-encoding',
                '--certChain='.$content,
            ];

            $process = new Process(array_merge($params, $signerProxyParams));

            try {
                $process->run();
            } catch (Throwable $t) {
                $this->logger->error('SUPP-Signer: '.$t->getMessage());

                return null;
            }

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $outSuccessPos = strpos($output, 'OutSuccess:');
                if (false !== $outSuccessPos) {
                    $certPathEncoding = trim(substr($output, $outSuccessPos + 11), " \n\r");
                } else {
                    $certPathEncoding = trim($output, " \n\r");
                }

                if ("erro" === $certPathEncoding) {
                    return null;
                }

                return $certPathEncoding;
            } else {
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable) {
            return null;
        }
    }


    /**
     * Verifica a validade das assinaturas PAdES via inputStream e outputStream do supp-signer
     *
     * @param string|null $pdfBase64 Conteúdo em Base64 do PDF
     * @return bool|null true = assinaturas OK, false = alguma assinatura inválida, null = ocorreu algum erro
     */
    public function isValidPAdES(?string $pdfBase64) : ?bool
    {
        try {
            if (empty($pdfBase64)) {
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            // PAdES é uma pilha de assinaturas.
            // Se pelo menos uma estiver inválida, retornará erro
            // Se existir assinatura corrompida feita anteriormente, dará erro
            $params = [
                'java',
                '-Duser.language=pt',
                '-Duser.country=BR',
                '-Dfile.encoding=UTF8',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
            ];

            // Preparar os parâmetros para enviar via inputstream STDIO
            // '--mode=pdf-verify --pdfBase64='.$pdfBase64
            $paramsInput = json_encode(
                [
                    'mode' => 'pdf-verify',
                    'pdfBase64' => $pdfBase64,
                ]
            );

            try {
                // Criar o processo
                $process = new Process(
                    command: array_merge($params, $signerProxyParams),
                    input:$paramsInput,
                    timeout:30
                );
                // Executar
                $process->run();
            } catch (Throwable $throwable) {
                // Erro logo no run() do processo
                $this->logger->critical(
                    mensagem: 'Erro ao executar o validador',
                    exception: $throwable->getMessage(),
                    stackTrace: $throwable->getTraceAsString()
                );

                return null;
            }

            // se o processo terminou com system exit diferente de 0 (erro)
            if (!$process->isSuccessful()) {
                $output = $process->getOutput();
                $this->logger->error('Command: '.$process->getCommandLine()."\n\nError: ".$output);
                // Assinatura inválida ou erro interno do supp-signer
                return false;
            }
        } catch (Throwable $throwable) {
            $this->logger->critical(
                mensagem: 'Erro ao executar o validador',
                exception: $throwable->getMessage(),
                stackTrace: $throwable->getTraceAsString()
            );
            return null;
        }

        return true;
    }

    /**
     * Verifica a validade das assinaturas CAdES de um arquivo via inputStream e outputStream do supp-signer
     *
     * @param string|null $signatureBase64 Conteúdo da assinatura em Base64
     * @return bool|null true = assinaturas OK, false = alguma assinatura inválida, null = ocorreu algum erro
     */
    public function isValidCAdES(string $hash, ?string $signatureBase64) : ?bool
    {
        try {
            if (empty($signatureBase64)) {
                $this->logger->error("");
                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            // PAdES é uma pilha de assinaturas.
            // Se pelo menos uma estiver inválida, retornará erro
            // Se existir assinatura corrompida feita anteriormente, dará erro
            $params = [
                'java',
                '-Duser.language=pt',
                '-Duser.country=BR',
                '-Dfile.encoding=UTF8',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
            ];

            // Preparar os parâmetros para enviar via inputstream STDIO
            //'--mode=verify --hash='.$hash.' --signatureBase64='.$signatureBase64
            $paramsInput = json_encode(
                [
                'mode' => 'verify',
                'hash' => $hash,
                'signatureBase64' => $signatureBase64,
                ]
            );

            try {
                // Criar o processo
                $process = new Process(
                    command: array_merge($params, $signerProxyParams),
                    input:$paramsInput,
                    timeout:30
                );
                // Executar
                $process->run();
            } catch (Throwable $throwable) {
                // Erro logo no run() do processo
                $this->logger->critical(
                    mensagem: 'Erro ao executar o validador',
                    exception: $throwable->getMessage(),
                    stackTrace: $throwable->getTraceAsString()
                );

                return null;
            }

            // se o processo terminou com system exit diferente de 0
            if (!$process->isSuccessful()) {
                $output = $process->getOutput();
                $this->logger->error('Command: '.$process->getCommandLine()."\n\nError: ".$output);
                // Assinatura inválida ou erro interno do supp-signer
                return false;
            }
        } catch (Throwable $throwable) {
            $this->logger->critical(
                mensagem: 'Erro ao executar o validador',
                exception: $throwable->getMessage(),
                stackTrace: $throwable->getTraceAsString()
            );
            return null;
        }

        return true;
    }


    /**
     * Remeve os metadados do PDF, formulários, links, botões, assinaturas digitais...
     *
     * @param string|null $pdfBase64 Conteúdo do PDF Base64
     * @return string|null pdfBase64 transformado, null = ocorreu algum erro
     */
    public function fattenPdf(?string $pdfBase64) : ?string
    {
        try {
            if (empty($pdfBase64)) {
                $this->logger->error("");

                return null;
            }

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }

            // PAdES é uma pilha de assinaturas.
            // Se pelo menos uma estiver inválida, retornará erro
            // Se existir assinatura corrompida feita anteriormente, dará erro
            $params = [
                'java',
                '-Duser.language=pt',
                '-Duser.country=BR',
                '-Dfile.encoding=UTF8',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
            ];

            // Preparar os parâmetros para enviar via inputstream STDIO
            // "--mode=pdf-flatten --pdfBase64=".$pdfBase64
            $paramsInput = json_encode(
                [
                    'mode' => 'pdf-flatten',
                    'pdfBase64' => $pdfBase64,
                ]
            );

            try {
                // Criar o processo
                $process = new Process(
                    command: array_merge($params, $signerProxyParams),
                    input: $paramsInput,
                    timeout: 30
                );
                // Executar
                $process->run();
            } catch (Throwable $throwable) {
                $this->logger->critical(
                    mensagem: 'Erro ao executar o SUPP-Signer.',
                    exception: $throwable->getMessage(),
                    stackTrace: $throwable->getTraceAsString()
                );

                return null;
            }

            // se o processo terminou com system exit 0 (sucesso)
            if ($process->isSuccessful()) {
                $output = $process->getOutput();

                // Vazio
                if (empty($output)) {
                    $this->logger->error(
                        'SUPP-Signer Command: '
                        .$process->getCommandLine()
                        ."\n\nError: Sem retorno ao efetuar o flatten no PDF."
                    );

                    return null;
                }

                // decodificar o retorno
                $retornoJson = json_decode($output, true);

                // Erro na decodificação, retorna
                if (null === $retornoJson) {
                    $this->logger->error(
                        'SUPP-Signer Command: '
                        .$process->getCommandLine()
                        ."\n\nError: Não foi possível decodificar o retorno após efetuar o flatten no PDF."
                    );

                    return null;
                }

                // Se existem os atributos fileBase64 e checksum
                if (isset($retornoJson['pdfBase64'], $retornoJson['checksum'])) {
                    $checksumMD5 = $retornoJson['checksum'];
                    $fileBase64ChecksumMD5 = md5($retornoJson['pdfBase64']);

                    if (hash_equals($checksumMD5, $fileBase64ChecksumMD5)) {
                        // A integridade está OK, retorna o fileBase64
                        return trim($retornoJson['pdfBase64'], " \n\r");
                    } else {
                        // Erro na integridade do arquivo retornado
                        $this->logger->error(
                            'SUPP-Signer Command: '
                            .$process->getCommandLine()
                            ."\n\nError: Falha na integridade do pdfBase64 retornado após flatten do PDF."
                        );

                        return null;
                    }
                } else {
                    $this->logger->error(
                        'SUPP-Signer Command: '
                        .$process->getCommandLine()
                        ."\n\nError: Campos obrigatório no retorno da operação flatten não encontrados:"
                        ." pdfBase64 e checksum."
                    );

                    return null;
                }
            } else {
                // se o processo terminou com system exit diferente de 0 (erro)
                $output = $process->getOutput();
                $this->logger->error('SUPP-Signer Command: '.$process->getCommandLine()."\n\nError: ".$output);

                return null;
            }
        } catch (Throwable $throwable) {
            $this->logger->critical(
                mensagem: 'Erro ao executar o SUPP-Signer.',
                exception: $throwable->getMessage(),
                stackTrace: $throwable->getTraceAsString()
            );

            return null;
        }
    }
}

<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Extratores;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ExtracaoMetadadosErrorException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ResourceUnavailableException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores\ExtratorMetadadosDocumentosInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InvalidCompletionsJsonResponseException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use Throwable;

/**
 * ExtratorDadosCumprimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ExtratorDadosCumprimento implements ExtratorMetadadosDocumentosInterface
{
    /**
     * Constructor.
     *
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param DocumentoHelper               $documentoHelper
     * @param LoggerInterface               $logger
     */
    public function __construct(
        private readonly InteligenciaArtificialService $inteligenciaArtificialService,
        private readonly DocumentoHelper $documentoHelper,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Verifica se o extrator é suportado.
     *
     * @param string   $type
     * @param Dossie[] $dossies
     * @param mixed    $context
     *
     * @return bool
     */
    public function supports(string $type, array $dossies = [], mixed $context = null): bool
    {
        return $this::class === $type && $context instanceof DadosTipoSolicitacaoSalarioMaternidadeRural;
    }

    /**
     * Extrai os metadados dos dossies fornecidos.
     *
     * @param Dossie[]                                    $dossies
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $context
     *
     * @return array
     */
    public function extrairMetadados(array $dossies, mixed $context = null): array
    {
        try {
            $documentos = array_map(
                fn(Dossie $dossie) => $dossie->getDocumento(),
                array_filter(
                    $dossies,
                    fn (Dossie $dossie) => $dossie->getTipoDossie()->getSigla() === 'DOSPREV'
                        && $dossie->getNumeroDocumentoPrincipal() === $context->getCpfBeneficiario()
                )
            );
            if (empty($documentos)) {
                return [];
            }
            // @codingStandardsIgnoreStart
            $prompt = new SimplePrompt(
                <<<EOT
                Você é um especialista em localizar dados solicitados em formulários. 
                Instrução geral: Verifique se o documento entre <documento></documento> é um EXTRATO DE DOSSIÊ PREVIDENCIÁRIO. Se for, você deve percorrê-lo 
                para encontrar os dados indicados nos passos a seguir e devolvê-los no formato de resposta indicado.
                Passo 1: Localize no documento o campo indicado como NIT e guarde o valor lá indicado para fornecer na resposta. O valor 
                estará no seguinte formato ddddddddddd, onde d é um dígito de 0 a 9.
                Passo 2: Localize no documento o campo RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS e, nos dados lá indicados, busque 
                aqueles relativos ao NB {$context->getNb()}. Encontrados esses dados, localize especificamente a data DER do NB {$context->getNb()} e e guarde o valor 
                lá indicado para fornecer na resposta. Essa data estará no formato dd/mm/aaaa, sendo dd o dia, mm o mês e aaaa o ano.
                Resposta a ser fornecida: devolva um JSON válido contendo os seguintes 
                campos:
                  {
                      "nit": "preencha com o número do NIT encontrado no documento no formato 'ddddddddddd'",
                      "der": "preencha com a data da DER encontrada no documento, no formato 'aaaa-mm-dd'"
                  }

                
                {$this->getTextoDocumentos($documentos)}
                EOT,
            );
            // @codingStandardsIgnoreEnd
            $client = $this->inteligenciaArtificialService
                ->getClient()
                ->setClientContext(
                    new ClientContext(
                        self::class,
                        [
                            'documentos' => array_map(
                                fn (Documento $documento) => $documento->getId(),
                                $documentos
                            ),
                        ]
                    )
                );
            $dadosCumprimento = $client->getCompletions($prompt)->getJsonResponse();
            if (!array_key_exists('nit', $dadosCumprimento) || !array_key_exists('der', $dadosCumprimento)) {
                throw new Exception(
                    'O resultado da inteligência artificial não retornou os campos esperados'
                    .' \'nit\' e/ou \'der\'.',
                    500
                );
            }

            return $dadosCumprimento;
        } catch (ClientRateLimitExeededException $e) {
            throw new ResourceUnavailableException(
                'Extração de dados de cumprimento',
                $e
            );
        } catch (Throwable $e) {
            $this->logger->error(
                'Falha oa extrair metadados de dados de cumprimento.',
                [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                    'extrator' => $this::class,
                    ...(
                    $e instanceof InvalidCompletionsJsonResponseException ?
                        [
                            'resposta' => $e->getResponse()->getShortResponse(),
                        ] : []
                    ),
                ]
            );
            throw new ExtracaoMetadadosErrorException($e);
        }
    }

    /**
     * Retorna o texto dos documentos.
     *
     * @param Documento[] $documentos
     *
     * @return string
     *
     * @throws InteligenciaArtificalException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws Exception
     */
    protected function getTextoDocumentos(
        array $documentos,
    ): string {
        $textoDocumento = '';
        foreach ($documentos as $documento) {
            $texto = $this->documentoHelper->extractTextFromDocumento($documento, false);
            if ($texto) {
                $textoDocumento .= sprintf(
                    '<documento>%s</documento>'.PHP_EOL,
                    $texto
                );
            }
        }
        if (empty($textoDocumento)) {
            throw new Exception('Os documentos não retornaram texto para extração', 500);
        }

        return $textoDocumento;
    }
}

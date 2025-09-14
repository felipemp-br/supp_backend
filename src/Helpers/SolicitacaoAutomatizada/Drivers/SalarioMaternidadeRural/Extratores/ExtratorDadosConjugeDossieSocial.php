<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Extratores;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
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
 * ExtratorDadosConjugeDossieSocial.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ExtratorDadosConjugeDossieSocial implements ExtratorMetadadosDocumentosInterface
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
                    fn (Dossie $dossie) => $dossie->getTipoDossie()->getSigla() === 'DOSOC'
                )
            );
            if (empty($documentos)) {
                return [];
            }
            // @codingStandardsIgnoreStart
            $prompt = new SimplePrompt(
                <<<EOT
                Você deve verificar se o documento é um 'DOSSIÊ SOCIAL CADUNICO'. Se for, verificar se o cpf da 
                beneficiária {$context->getCpfBeneficiario()} aparece no campo 'Pessoa Responsável pela Unidade Familiar - RF' no campo 
                'MEMBROS DO GRUPO FAMILIAR'. Se constar, procure, nesse mesmo campo, se consta indicação de 'Cônjuge ou 
                companheiro(a)'. Se houver, verifique se há CPF indicado para o 'Cônjuge ou companheiro(a)'. Havendo, pode 
                preencher o json abaixo e retornar a resposta.
                Se o cpf beneficiária {$context->getCpfBeneficiario()} for diferente do que consta no campo 'Pessoa Responsável pela Unidade 
                Familiar - RF' no campo 'MEMBROS DO GRUPO FAMILIAR', precure se o {$context->getCpfBeneficiario()} aparece como 'Cônjuge ou 
                companheiro(a)'. Nesse caso, retorne o json abaixo preencheno-o com o cpf da pessoa que aparece como 'Pessoa 
                Responsável pela Unidade Familiar - RF' no campo 'MEMBROS DO GRUPO FAMILIAR'.
                Retorne um json com os dois campos a seguir:
                    {
                        "conjuge": true,
                        "cpf_conjuge": "xxxxxxxxxxx"
                    }
                Se não encontrado cônjuge no dossiê Cadunico, coloque false no campo conjuge e deixe o campo do cpf_conjuge vazio.
                Responda com um JSON válido sem usar a expressão ```json e as aspas triplas no inicio ou final da resposta.
                
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
            $dadosConjuge = $client->getCompletions($prompt)->getJsonResponse();
            if (!array_key_exists('conjuge', $dadosConjuge) || !array_key_exists('cpf_conjuge', $dadosConjuge)) {
                throw new Exception(
                    'O resultado da inteligência artificial não retornou os campos esperados'
                    .' \'conjuge\' e \'cpf_conjuge\'.',
                    500
                );
            }

            return [
                'conjuge' => $dadosConjuge['conjuge'],
                'cpf_conjuge' => $dadosConjuge['cpf_conjuge'] ? str_pad(
                    SalarioMaternidadeRuralDriver::somenteNumeros($dadosConjuge['cpf_conjuge']),
                    11,
                    '0',
                    STR_PAD_LEFT
                ) : '',
            ];
        } catch (ClientRateLimitExeededException $e) {
            throw new ResourceUnavailableException(
                'Extração de dados de cônjuge',
                $e
            );
        } catch (Throwable $e) {
            $this->logger->error(
                'Falha oa extrair metadados de conjuge.',
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

<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\AnalisaDossieException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ResourceUnavailableException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InvalidCompletionsJsonResponseException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\PromptInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use SuppCore\AdministrativoBackend\Repository\DossieRepository;
use Throwable;

/**
 * AbstractAnaliseInicialIA.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractAnaliseIA
{
    protected const EXPECTED_RESPONSE_FIELDS = [
        'passou_analise',
        'cpf_analisado',
        'resultado_analise',
        'observacao',
        'nome_analise',
    ];

    /**
     * Constructor.
     *
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param DocumentoHelper               $documentoHelper
     * @param LoggerInterface               $logger
     * @param DossieRepository              $dossieRepository
     */
    public function __construct(
        protected readonly InteligenciaArtificialService $inteligenciaArtificialService,
        protected readonly DocumentoHelper $documentoHelper,
        protected readonly LoggerInterface $logger,
        protected readonly DossieRepository $dossieRepository,
    ) {
    }

    /**
     * Retorna o prompt a ser utilizado para análise.
     *
     * @param string   $cpfAnalisado
     * @param Dossie[] $dossies
     * @param array    $dadosTipoSolicitacao
     *
     * @return PromptInterface
     *
     * @throws InteligenciaArtificalException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     */
    protected function getPrompt(
        string $cpfAnalisado,
        array $dossies,
        array $dadosTipoSolicitacao,
    ): PromptInterface {
        return new SimplePrompt(
            $this->getInstrucaoAnalise($cpfAnalisado, $dadosTipoSolicitacao)
            .PHP_EOL.$this->getTextoDocumentoDossies($dossies),
            persona: $this->getPersona()
        );
    }

    /**
     * Retorna a instrução da análise.
     *
     * @param string $cpfAnalisado
     * @param array  $dadosTipoSolicitacao
     *
     * @return string
     */
    abstract protected function getInstrucaoAnalise(
        string $cpfAnalisado,
        array $dadosTipoSolicitacao,
    ): string;

    /**
     * Realiza a analise dos dossies.
     *
     * @param AnaliseDossies              $analiseDossies
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     * @param array                       $dadosTipoSolicitacao
     *
     * @return AnaliseDossies
     *
     * @throws AnalisaDossieException
     */
    public function analisar(
        AnaliseDossies $analiseDossies,
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        array $dadosTipoSolicitacao = [],
    ): AnaliseDossies {
        try {
            $dossies = array_filter(
                $this->dossieRepository->findBy([
                    'id' => $analiseDossies->getDossies(),
                ]),
                fn (Dossie $dossie) => in_array(
                    $dossie->getTipoDossie()->getSigla(),
                    $this->getSiglasTipoDossiesSuportados()
                ),
            );
            $prompt = $this->getPrompt(
                $analiseDossies->getCpfAnalisado(),
                $dossies,
                $dadosTipoSolicitacao,
            );
            $client = $this->inteligenciaArtificialService
                ->getClient(
                    new ClientContext(
                        $this::class,
                        [
                            'analise' => $this::class,
                            'cpf_analisado' => $analiseDossies->getCpfAnalisado(),
                            'dossies' => array_map(
                                fn (Dossie $dossie) => $dossie->getId(),
                                $dossies
                            ),
                        ]
                    )
                );
            $result = $client->getCompletions($prompt);
            $data = $result->getJsonResponse();
            foreach (self::EXPECTED_RESPONSE_FIELDS as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new Exception(
                        sprintf(
                            'A inteligência artificial não retornou o campo %s como resultado da análise dos dossies.',
                            $field
                        ),
                        500
                    );
                }
            }

            return $analiseDossies
                ->setNomeAnalise($data['nome_analise'])
                ->setPassouAnalise($data['passou_analise'])
                ->setResultadoAnalise($data['resultado_analise'])
                ->setObservacao($data['observacao'])
                ->setDossies(
                    array_map(
                        fn (Dossie $dossie) => $dossie->getId(),
                        $dossies
                    )
                );
                
        } catch (ClientRateLimitExeededException $e) {
            throw new ResourceUnavailableException(
                'Análise de dossies',
                $e
            );
        } catch (Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Erro ao analisar o(s) dossie(s) para o CPF %s.',
                    $analiseDossies->getCpfAnalisado()
                ),
                [
                    'analise' => $this::class,
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                    ...(
                    $e instanceof InvalidCompletionsJsonResponseException ?
                        [
                            'resposta' => $e->getResponse()->getShortResponse(),
                        ] : []
                    ),
                ]
            );
            throw new AnalisaDossieException($e);
        }
    }

    /**
     * Retorna o a persona default para a realização das análises.
     *
     * @return string|null
     */
    protected function getPersona(): ?string
    {
        return <<<EOT
            Você é um Procurador Federal especializado na análise de requisitos para 
            concessão ou não do benefício previdenciário Salário Maternidade Rural. Você recebe documentos no formato html ou pdf convertidos em texto, demarcados por <documento></documento> faz a análise cuidadosa dos requisitos solicitados e, depois, responde um json contendo cinco valores: 
                { 
                  "cpf_analisado": "preencha com o cpf indicado no documento analisado.", 
                  "nome_analise": "preencha com o nome do campo examinado.", 
                  "passou_analise": "preencha com booleano true se não encontrou nenhum impeditivo após seguir todos os passos indicados e responda com booleano false se encontrou algum impeditivo.",
                  "observacao": "preencha com a observação indicada para o caso, se houver, se não houver preencha com null. Observe que esse campo só será preenchido quando o valor do campo passou_analise mudar de booleano false para booleano true em razão de alguma análise de exceção apta a remover um imeditivo já identificado.",
                  "resultado_analise": "preencha com a justificativa da resposta acima. No caso de booleano false, indique de forma detalhada, em até 100 palavras, os impeditivos encontrados. Se houver mais de um item com informação que configure impeditivo, indique e referencie todos eles."
                } 
            Você deve, primeiro, verificar nos documentos as informações relevantes para a análise solicitada e, depois, seguir os passos indicados para a análise para, somente ao final, dar a resposta. 
            A resposta final deve ser um JSON válido da análise feita, sem usar a palavra JSON e sem colocar ```json ou marcadores similares no inicio ou final da resposta.
        EOT;
    }

    /**
     * Retorna o texto dos documentos dos dossies.
     *
     * @param Dossie[] $dossies
     *
     * @return string
     *
     * @throws InteligenciaArtificalException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws Exception
     */
    protected function getTextoDocumentoDossies(
        array $dossies,
    ): string {
        $textoDocumento = '';
        foreach ($dossies as $dossie) {
            if (in_array($dossie->getTipoDossie()->getSigla(), $this->getSiglasTipoDossiesSuportados())) {
                $texto = $this->documentoHelper->extractTextFromDocumento($dossie->getDocumento(), false);
                if ($texto) {
                    $textoDocumento .= sprintf(
                        '<documento>%s</documento>'.PHP_EOL,
                        $texto
                    );
                }
            }
        }
        if (empty($textoDocumento)) {
            throw new Exception('Os dossies não retornaram texto para análise', 500);
        }

        return $textoDocumento;
    }

    /**
     * Verifica se o handler suporta o tipo de analise de dossie.
     *
     * @param AnaliseDossies              $analiseDossies
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     * @param array                       $dadosTipoSolicitacao
     *
     * @return bool
     */
    public function supports(
        AnaliseDossies $analiseDossies,
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        array $dadosTipoSolicitacao = [],
    ): bool {
        $tipoSolicitacao = SalarioMaternidadeRuralDriver::getSiglaTipoSolicitacaoAutomatizada();

        return $analiseDossies->getAnalise() === $this::class
            && $tipoSolicitacaoAutomatizada->getSigla() === $tipoSolicitacao;
    }

    /**
     * Retorna a sigla dos tipos de dossies suportados.
     *
     * @return string[]
     */
    abstract protected function getSiglasTipoDossiesSuportados(): array;
}

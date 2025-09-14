<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid as Ruuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\TrilhaTriagemPromptInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions\InvalidSchemaPropertyPathException;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Helpers\JsonSchemaHelper;
use SuppCore\AdministrativoBackend\Repository\FormularioRepository;
use Throwable;

/**
 * BaseTrilhaTriagem.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class BaseTrilhaTriagem
{
    private string $nomeTrilha;
    /**
     * @var TrilhaTriagemPromptInterface[]
     */
    private array $prompts;
    /**
     * @var string[]
     */
    private array $dependsOn;

    /**
     * Constructor.
     *
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param FormularioRepository          $formularioRepository
     * @param DadosFormularioResource       $dadosFormularioResource
     * @param LoggerInterface               $logger
     * @param DocumentoHelper               $documentoHelper
     * @param JsonSchemaHelper              $jsonSchemaHelper
     * @param SuppParameterBag              $suppParameterBag
     *
     * @throws UnsupportedUriException
     */
    public function __construct(
        protected readonly InteligenciaArtificialService $inteligenciaArtificialService,
        protected readonly FormularioRepository $formularioRepository,
        protected readonly DadosFormularioResource $dadosFormularioResource,
        protected readonly LoggerInterface $logger,
        protected readonly DocumentoHelper $documentoHelper,
        protected readonly JsonSchemaHelper $jsonSchemaHelper,
        protected readonly SuppParameterBag $suppParameterBag
    ) {
        $this->initialize();
    }

    /**
     * Indica se a trilha deve manter o contexto entre os prompts.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    abstract protected function keepContext(TrilhaTriagemInput $input): bool;

    /**
     * Retorna a sigla do formulário da trilha de triagem.
     *
     * @return string
     */
    abstract public static function getSiglaFormulario(): string;

    /**
     * @param array $dependsOn
     *
     * @return $this
     */
    public function setDependsOn(array $dependsOn): self
    {
        $this->dependsOn = $dependsOn;

        return $this;
    }

    /**
     * @param string $nomeTrilha
     *
     * @return $this
     */
    public function setNomeTrilha(string $nomeTrilha): self
    {
        $this->nomeTrilha = $nomeTrilha;

        return $this;
    }

    /**
     * @param array $promptTree
     * @param array $prompts
     *
     * @return $this
     */
    public function setPrompts(array $promptTree, array $prompts): self
    {
        $this->prompts = $this->orderPrompts(
            $promptTree,
            $prompts
        );

        return $this;
    }

    /**
     * @return Formulario
     */
    public function getFormulario(): Formulario
    {
        return $this->formularioRepository->findOneBy(['sigla' => $this::getSiglaFormulario()]);
    }

    /**
     * Método que retorna os dados formulário devidamente encapsulados para serem salvos.
     *
     * @param TrilhaTriagemInput $input
     * @param array              $trilhaData
     *
     * @return DadosFormularioDTO
     *
     * @throws InvalidSchemaPropertyPathException
     */
    public function getDadosFormulario(TrilhaTriagemInput $input, array $trilhaData): DadosFormularioDTO
    {
        $formulario = $this->getFormulario();
        $jsonValue = json_encode(
            $this->jsonSchemaHelper->dataToValidJsonSchemaValueData(
                $trilhaData[$this::getSiglaFormulario()],
                $formulario->getDataSchema(),
                'formulario_'.$formulario->getId()
            ),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        return (new DadosFormularioDTO())
            ->setFormulario($formulario)
            ->setDocumento($input->documento)
            ->setDataValue($jsonValue)
            ->setInvalido(empty($jsonValue) || !json_validate($jsonValue));
    }

    /**
     * Função que é chamada após o constructor.
     * Serve para ser sobrescrita e realizar operações iniciais.
     *
     * @return void
     */
    protected function initialize(): void
    {
    }

    /**
     * Retorna os prompts na ordem correta.
     *
     * @param array $promptTree
     * @param array $prompts
     *
     * @return array
     */
    private function orderPrompts(array $promptTree, array $prompts): array
    {
        $orderedPrompts = [];
        foreach ($promptTree as $index => $item) {
            if (is_string($index)) {
                $prompt = array_filter(
                    $prompts,
                    fn (TrilhaTriagemPromptInterface $prompt) => get_class($prompt) === $index
                );
                if ($prompt) {
                    $orderedPrompts[] = array_shift($prompt);
                }
            }
            if (is_array($item)) {
                $orderedPrompts = [
                    ...$orderedPrompts,
                    ...$this->orderPrompts($item, $prompts),
                ];
            }
            if (is_string($item)) {
                $prompt = array_filter(
                    $prompts,
                    fn (TrilhaTriagemPromptInterface $prompt) => get_class($prompt) === $item
                );
                if ($prompt) {
                    $orderedPrompts[] = array_shift($prompt);
                }
            }
        }

        return $orderedPrompts;
    }

    /**
     * Retorna o nome da trilha.
     *
     * @return string
     */
    public function getNomeTrilha(): string
    {
        return $this->nomeTrilha;
    }

    /**
     * retorna os prompts da trilha.
     *
     * @return TrilhaTriagemPromptInterface[]
     */
    protected function getPrompts(): array
    {
        return $this->prompts;
    }

    /**
     * Retorna os dados do formulario.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return array
     */
    protected function getTrilhaData(TrilhaTriagemInput $input): array
    {
        $data = [
            $this::getSiglaFormulario() => [],
        ];
        foreach ($this->getDeppendsOn() as $trilha) {
            if (!class_exists($trilha)) {
                continue;
            }
            $data[$trilha::getSiglaFormulario()] = json_decode(
                $this->dadosFormularioResource
                    ->getRepository()
                    ->findOneBySiglaFormularioDocumento(
                        $trilha::getSiglaFormulario(),
                        $input->documento
                    )?->getDataValue() ?? '{}',
                true
            );
        }

        return $data;
    }

    /**
     * Retorna a persona da trilha.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return string
     *
     * @throws InteligenciaArtificalException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     */
    protected function getPersona(TrilhaTriagemInput $input): string
    {
        $documento = $this->documentoHelper->extractTextFromDocumento($input->documento);
        if ($documento) {
            $documento = sprintf(
                '###%s###',
                $documento
            );
        }

        return <<<EOT
            Faça a triagem e a classificação de documentos judiciais como um advogado público federal com mais de trinta anos
             de experiência profissional. Siga então as seguintes instruções:
               1) realize uma leitura detalhada do texto e preencha o 'valor' associado a cada uma das 'chaves' contidas no modelo abaixo;
               2) gere uma 'completion' em linguagem clara, objetiva e dentro dos padrões da norma culta da língua portuguesa;
               3) armazene as 'strings' resultantes da 'completion' como 'valor' de uma 'chave' estruturada, exclusivamente, em JavaScript Object Notation - JSON;
               4) o texto do documento judicial a ser triado e classificado está delimitado por '###';
               5) se o 'valor' não existir ou se houver dúvida sobre a correta triagem e classificação da peça processual, não retorne a 'chave' correspondente nem preencha o 'valor';
               6) use como modelo de preenchimento dos dados estruturados a estrutura JSON proposta;
               7) Retorne única e exclusivamente TODAS as respostas dentro do JSON proposto sem nenhuma marcação adicional; 
               7) Não coloque marcações do tipo ```json...; 
               
               $documento
        EOT;
    }

    /**
     * Verifica se a trilha pode ser executada.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    public function shouldExecuteTrilha(TrilhaTriagemInput $input): bool
    {
        $trilha = get_class($this);

        return $input->force || !$this->dadosFormularioResource
                ->getRepository()
                ->findOneBySiglaFormularioDocumento(
                    $trilha::getSiglaFormulario(),
                    $input->documento
                );
    }

    /**
     * Executa os prompts da trilha e retorna os dados da trilha.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return array
     *
     * @throws ClientRateLimitExeededException
     * @throws EmptyDocumentContentException
     * @throws InteligenciaArtificalException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnsupportedUriException
     */
    public function executePrompts(TrilhaTriagemInput $input): array
    {
        $client = $this->inteligenciaArtificialService->getClient();
        $client->setPersona($this->getPersona($input));
        $trilhaData = $this->getTrilhaData($input);
        $context = null;
        foreach ($this->getPrompts() as $prompt) {
            if ($prompt->suppports($input, $trilhaData)) {
                $this->logger->info(
                    'Executando prompt de trilha de triagem.',
                    [
                        'documento_uuid' => $input->documento->getUuid(),
                        'trilha' => get_class($this),
                        'prompt' => get_class($prompt),
                    ]
                );
                $prompt
                    ->setDadosFormulario($trilhaData)
                    ->setTrilhaTriagemInput($input);

                $client->setClientContext(
                    new ClientContext(
                        self::class,
                        [
                            'trilha' => get_class($this),
                            'prompt' => get_class($prompt),
                            'documento_uuid' => $input->documento->getUuid(),
                            'tipo_documento_id' => $input->documento->getTipoDocumento()->getId(),
                            'tipo_documento_predito_id' => $input->documento
                                ?->getDocumentoIAMetadata()
                                ?->getTipoDocumentoPredito()
                                ?->getId(),
                            'force' => $input->force,
                            ...$input->context,
                        ]
                    )
                );
                $completionsResponse = $client
                    ->getCompletions(
                        $prompt,
                        context: $context
                    );
                if ($this->keepContext($input)) {
                    $context ??= $completionsResponse->getContext();
                }
                $data = $completionsResponse->getJsonResponse(false);
                if (!$data) {
                    $this->logger->warning(
                        'A inteligência artificial não retornou um json válido.',
                        [
                            'trilha' => get_class($this),
                            'prompt' => get_class($prompt),
                            'documento_uuid' => $input->documento->getUuid(),
                            'resposta' => $completionsResponse->getShortResponse(),
                        ]
                    );
                    continue;
                }
                $trilhaData[$this::getSiglaFormulario()] = array_merge(
                    $trilhaData[$this::getSiglaFormulario()],
                    $data
                );
            }
        }

        return $trilhaData;
    }

    /**
     * Executa a trilha.
     *
     * @param TrilhaTriagemInput $input
     * @param string             $transactionId
     *
     * @return void
     *
     * @throws ClientRateLimitExeededException
     * @throws EmptyDocumentContentException
     * @throws InteligenciaArtificalException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws InvalidSchemaPropertyPathException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnsupportedUriException
     */
    public function handle(TrilhaTriagemInput $input, string $transactionId): void
    {
        $trilha = get_class($this);
        try {
            if ($this->shouldExecuteTrilha($input)) {
                $this->logger->info(
                    'Executando trilha de triagem.',
                    [
                        'documento_uuid' => $input->documento->getUuid(),
                        'trilha' => $trilha,
                    ]
                );
                $trilhaData = $this->executePrompts($input);
                $dadosFormulario = $this->dadosFormularioResource
                    ->getRepository()
                    ->findOneBySiglaFormularioDocumento(
                        $trilha::getSiglaFormulario(),
                        $input->documento
                    );
                $dadosFormularioDTO = $this->getDadosFormulario($input, $trilhaData);
                if ($dadosFormulario) {
                    /** @var DadosFormularioDTO $dadosFormularioDTO */
                    $dadosFormularioDTO = $this->dadosFormularioResource->getDtoForEntity(
                        $dadosFormulario->getId(),
                        DadosFormularioDTO::class,
                        $dadosFormularioDTO,
                        $dadosFormulario
                    );
                    $this->dadosFormularioResource->update(
                        $dadosFormulario->getId(),
                        $dadosFormularioDTO,
                        $transactionId
                    );
                } else {
                    $this->dadosFormularioResource->create(
                        $dadosFormularioDTO,
                        $transactionId
                    );
                }
            } else {
                $this->logger->info(
                    'Trilha de triagem não executada pois o formulário já existe.',
                    [
                        'documento_uuid' => $input->documento->getUuid(),
                        'trilha' => $trilha,
                        'sigla_formulario' => $trilha::getSiglaFormulario(),
                    ]
                );
            }
        } catch (Throwable $e) {
            $this->logger->error(
                'Erro duarante a execução da trilha.',
                [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                    'documento_uuid' => $input->documento->getUuid(),
                    'trilha' => $trilha,
                ]
            );
            throw $e;
        }
    }

    /**
     * Retorna a lista de trilhas de triagem que precisam ser executadas antes da trilha atual.
     *
     * @return string[]
     */
    public function getDeppendsOn(): array
    {
        return $this->dependsOn;
    }
}

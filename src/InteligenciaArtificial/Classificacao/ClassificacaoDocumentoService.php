<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientMissingConfigException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use Throwable;

/**
 * ClassificacaoDocumentoService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClassificacaoDocumentoService
{
    /**
     * Constructor.
     *
     * @param TipoDocumentoRepository       $tipoDocumentoRepository
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     * @param LoggerInterface               $logger
     */
    public function __construct(
        private readonly TipoDocumentoRepository $tipoDocumentoRepository,
        private readonly InteligenciaArtificialService $inteligenciaArtificialService,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param Documento $documento
     *
     * @return TipoDocumento
     *
     * @throws Throwable
     * @throws ClientMissingConfigException
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
    public function classificar(Documento $documento): TipoDocumento
    {
        try {
            $client = $this->inteligenciaArtificialService
                ->getClient(
                    new ClientContext(
                        self::class,
                        [
                            'documento_id' => $documento->getId()
                        ]
                    )
                )
                ->setPersona($this->getPesona());

            $completionsResponse = $client->getCompletions(
                new SimplePrompt(
                    <<<EOT
                        {
                            "tipo_documento": "insira aqui o nome correto do documento judicial"
                        }
                    EOT,
                    $documento
                )
            );
            $response = $completionsResponse->getResponse();
            if (!json_validate($response)) {
                $response =  preg_replace('/```json\s*(\{.*?\})\s*```/s', '$1', $response);
            }
            $data = json_decode($response, true);
            if (!json_validate($response) || !is_array($data) || !isset($data['tipo_documento'])) {
                throw new Exception(
                    'A inteligência artificial não retornou um json válido na classificação do documento.'
                );
            }
            return $this->tipoDocumentoRepository->findTipoDocumentoFromDocumentoClassificado(
                $data['tipo_documento']
            );
        } catch (Throwable $e) {
            $this->logger->warning(
                $e->getMessage(),
                [
                    'documento_id' => $documento->getId()
                ]
            );
            throw $e;
        }
    }

    /**
     * @return string
     */
    protected function getPesona(): string
    {
        return <<<EOT
            Faça a classificação de documentos judiciais como um advogado público federal com mais de trinta anos
             de experiência profissional. Siga então as seguintes instruções:
               1) realize uma leitura detalhada do texto e preencha o 'valor' associado a cada uma das 'chaves' contidas no modelo de JSON a ser apresentado;
               2) gere uma 'completion' em linguagem clara, objetiva e dentro dos padrões da norma culta da língua portuguesa;
               3) armazene as 'strings' resultantes da 'completion' como 'valor' de uma 'chave' estruturada, exclusivamente, em JavaScript Object Notation - JSON;
               4) o texto do documento judicial a ser classificado está delimitado por '###';
               5) use como modelo de preenchimento dos dados estruturados a estrutura JSON proposta;
               6) Retorne única e exclusivamente TODAS as respostas dentro do JSON proposto sem nenhuma marcação adicional; 
               7) Não coloque marcações do tipo ```json...; 
               
               Instruções para classificação:
               O valor do atributo tipo_documento deve retornar o nome corredo do documento judicial conforme as seguintes definições e instruções abaixo:
               'PETIÇÃO INICIAL': documento que inicia o processo e contém o nome da ação judicial, os nomes do autor e do réu, a qualificação das partes, os fatos, os fundamentos jurídicos do pedido, os pedidos, as provas e o valor da causa;
               'AGRAVO DE INSTRUMENTO': recurso contra decisão interlocutória de juiz federal ou de juiz de direito de primeiro grau;
               'APELAÇÃO': recurso contra sentença judicial proferida por juiz federal ou por juiz estadual de primeiro grau;
               'CONTESTAÇÃO': peça processual apresentada pelo réu para se defender dos fatos e fundamentos jurídicos indicados pela petição inicial;
               'CONTRARRAZÕES': peça processual apresentada pelo recorrido;
               'EMBARGOS DE DECLARAÇÃO': recurso contra obscuridade, contradição, omissão ou erro material em decisão judicial;
               'LAUDO': documento com análise técnica ou científica elaborado por médico, engenheiro, biólogo ou qualquer outro perito;
               'RECURSO INOMINADO': recurso contra sentença proferida por juiz federal no juizado especial federal ou por juiz de direito no juizado especial estadual;
               'SENTENÇA': documento com o julgamento do processo pelo juiz federal ou juiz de direito no primeiro grau;
               
               Se o documento judicial não for classificado entre os nomes dos tipos da lista acima, insira o nome do documento mais adequado ao caso. Por exemplo: 
               'CARTEIRA DE IDENTIDADE', 
               'EMENDA À INICIAL', 
               'DESPACHO', 
               'DECISÃO', 
               'CADUNICO', 
               'INTIMAÇÃO', 
               'PROCURAÇÃO', 
               'CNIS', 
               'CITAÇÃO', 
               'COMPROVANTE DE RESIDÊNCIA', 
               'DOSSIÊ SOCIAL', 
               'ATESTADO MÉDICO', 
               'RECEITA', 
               'PROCESSO ADMINISTRATIVO', 
               'CERTIDÃO DE NASCIMENTO', 
               'CERTIDÃO', 
               'PROTOCOLO'.
               
               Caso não consiga determinar o tipo de documento de acordo com as instruções acima, defina o valor como 'OUTRO'. 
        EOT;
    }

}

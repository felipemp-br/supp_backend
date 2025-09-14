<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Datalake\Conectors;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Integracao\Datalake\KafkaConectorInterface;

/**
 * src/Integracao/Datalake/Conectors/KafkaConectorCurl.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class KafkaConectorCurl implements KafkaConectorInterface
{

    public function __construct(
        private readonly SuppParameterBag $parameterBag,
        private readonly DossieResource $dossieResource
    ) {
    }

    /**
     * @throws Exception
     */
    public function consumeTopics(array $names): array
    {
        if (!$this->parameterBag->get('supp_core.administrativo_backend.datalake.kafka.enabled')) {
            throw new Exception('Serviço de tópicos Kafka desabilitado neste ambiente.');
        }

        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @[
            'server'    => $server,
            'username'  => $username,
            'password'  => $password,
            'url'       => $url,
            'ativo'     => $ativo
        ] = $this->parameterBag->get('supp_core.administrativo_backend.datalake.kafka.config');

        if (isset($ativo) && $ativo) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://$server/$url");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["accept: application/vnd.kafka.json.v2+json"]);
            curl_close($curl);
            $topics = json_decode(curl_exec($curl) ?: '[]', true);
        } else {
            $topics = json_decode($this->getMockup(), true);
        }

        if (!is_array($topics) || !isset($topics['dossies'])) {
            return [];
        }

        $result = [];
        foreach ($topics['dossies'] as $topic) {
            if (in_array($topic['topic'] ?? false, $names, true)) {
                $t = ['topic'=> $topic['topic']];
                foreach ($topic as $key => $value) {
                    if ($key !== 'topic') {
                        $t['data'][$key] = $value;
                    }
                }
                $result[] = $t;
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function consumeTopic(string $name): ?array
    {
        /** @noinspection PhpAssignmentInConditionInspection */
        return ($topic = $this->consumeTopics([$name])) ?
            $topic['data'] : null;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function getMockup(): string
    {
        $dossie = $this->dossieResource->getRepository()->findLastIdByTipoDossie("DOSSIÊ PATRIMONIAL");
        return
        <<<EOF
{
  "dossies": [
    {
      "dossie_id": $dossie,
      "topic": "dossie.patrimonial",
      "dossie_conteudo": {
        "dados_cadastrais": {
          "tipo_pessoa": "pessoa_fisica",
          "nome": "FULADO DE TAL JUNIOR",
          "ocupacao_natureza": "",
          "sexo": "M",
          "residente_exterior": false,
          "nome_mae": "FULANA DE TAL",
          "nome_social": "",
          "naturalidade": "TAIO",
          "naturalidade_uf": "SC",
          "ocupacao_pessoal": "",
          "nome_fantasia": "",
          "razao_social": "",
          "nome_pai": "FULANO DE TAL",
          "data_obito": "",
          "data_nascimento": "1967-02-02T03:00:00.0Z",
          "nacionalidade": "BRASILEIRO",
          "cpf_cnpj": "56934157915"
        },
        "documentos": [
          {
            "tipo_documento": "INSCRICAO ELEITORAL",
            "numero": "000679140930",
            "uf": "",
            "data_expedicao": "",
            "fonte": "BD_TSE.FILIADOS_PARTIDO",
            "fonte_atualizacao": "2022-09-20T03:00:00.0Z",
            "complemento": ""
          },
          {
            "tipo_documento": "PIS",
            "numero": "12026774856",
            "uf": "",
            "data_expedicao": "",
            "fonte": "BD_RAIS.EMPREGADOS",
            "fonte_atualizacao": "2022-10-27T03:00:00.0Z",
            "complemento": ""
          },
          {
            "tipo_documento": "CTPS",
            "numero": "00045301/0020",
            "uf": "",
            "data_expedicao": "",
            "fonte": "BD_RAIS.EMPREGADOS",
            "fonte_atualizacao": "2022-10-27T03:00:00.0Z",
            "complemento": ""
          },
          {
            "tipo_documento": "CARTEIRA IDENTIDADE",
            "numero": "1897080",
            "uf": "SC",
            "data_expedicao": "",
            "fonte": "BD_RENACH.RENACH",
            "fonte_atualizacao": "2022-07-13T03:00:00.0Z",
            "complemento": ""
          }
        ],
        "enderecos": [
          {
            "logradouro": "RUA VENERANDA BELLI",
            "numero": "152",
            "bairro": "",
            "municipio": "TAIO",
            "municipio_uf": "SC",
            "complemento": "CASA",
            "cep": "89190000",
            "fonte": "BD_RENACH.RENACH",
            "fonte_atualizacao": "2022-07-13T00:00:00.0Z"
          },
          {
            "logradouro": "RUA VENERANDA BELLI",
            "numero": "152",
            "bairro": "VICTOR KONDER",
            "municipio": "TAIO",
            "municipio_uf": "SC",
            "complemento": "",
            "cep": "89190000",
            "fonte": "BD_CNE.PESSOA",
            "fonte_atualizacao": "2022-10-27T00:00:00.0Z"
          }
        ],
        "imoveis_cidade_sao_paulo": {},
        "doacoes_eleitorais_realizadas": [],
        "doacoes_eleitorais_recebidas": [],
        "bens_declarados_tse": [],
        "veiculos": [],
        "relacionamentos": [],
        "precatorios": [],
        "pessoa_policitacamente_exposta": [],
        "participacao_societaria": [],
        "embarcacoes": [],
        "imoveis_rurais": [],
        "vinculos_empregaticios": [
          {
            "cnpj_cpf": "27183837000170",
            "nome": "",
            "nome_fantasia": "",
            "razao_social": "BRASIL SUL FABRICACAO DE PAPEIS LTDA",
            "data_inicio": "2021-02-08T03:00:00.0Z",
            "data_fim": "",
            "cargo": "MOTORISTA DE CAMINHÃO",
            "ocupacao": "MOTORISTA DE CAMINHÃO(782510)",
            "salario_medio_anual": 3778.46,
            "salario_contrato": 3655.74,
            "fonte": "DB_RAIS.EMPREGADOS",
            "fonte_atualizacao": "2022-10-27T03:00:00.0Z"
          }
        ],
        "informacoes_gerais": {},
        "mapeamento_nup_sapiens": [],
        "requisicoes_pequeno_valor": [],
        "aeronaves": []
      }
    }
  ]
}
EOF;
    }
}

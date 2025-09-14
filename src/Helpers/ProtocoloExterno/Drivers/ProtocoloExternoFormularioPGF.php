<?php

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/Drivers/ProtocoloExternoFormularioPGF.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\Drivers;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoAdministrativoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EnderecoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EstadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeQualificacaoPessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\MunicipioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\DadosProtocoloExterno;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Validator\Constraints\Cnj;
use SuppCore\AdministrativoBackend\Validator\Constraints\CpfCnpj;
use SuppCore\AdministrativoBackend\Validator\Constraints\Nup;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProtocoloExternoFormularioPGF.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProtocoloExternoFormularioPGF extends ProtocoloExternoBasico
{
    /**
     * @param ClassificacaoResource $classificacaoResource
     * @param SuppParameterBag $suppParameterBag
     * @param EspecieTarefaResource $especieTarefaResource
     * @param EspecieProcessoResource $especieProcessoResource
     * @param AssuntoAdministrativoResource $assuntoAdministrativoResource
     * @param EtiquetaResource $etiquetaResource
     * @param ModalidadeMeioResource $modalidadeMeioResource
     * @param ModalidadeEtiquetaResource $modalidadeEtiquetaResource
     * @param SetorResource $setorResource
     * @param EstadoResource $estadoResource
     * @param ValidatorInterface $validator
     * @param PessoaResource $pessoaResource
     * @param ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource
     * @param MunicipioResource $municipioResource
     * @param TransactionManager $transactionManager
     * @param EnderecoResource $enderecoResource
     */
    public function __construct(
        private readonly ClassificacaoResource $classificacaoResource,
        private readonly SuppParameterBag $suppParameterBag,
        private readonly EspecieTarefaResource $especieTarefaResource,
        private readonly EspecieProcessoResource $especieProcessoResource,
        private readonly AssuntoAdministrativoResource $assuntoAdministrativoResource,
        private readonly EtiquetaResource $etiquetaResource,
        private readonly ModalidadeMeioResource $modalidadeMeioResource,
        private readonly ModalidadeEtiquetaResource $modalidadeEtiquetaResource,
        private readonly SetorResource $setorResource,
        private readonly EstadoResource $estadoResource,
        private readonly ValidatorInterface $validator,
        private readonly PessoaResource $pessoaResource,
        private readonly ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource,
        private readonly MunicipioResource $municipioResource,
        private readonly TransactionManager $transactionManager,
        private readonly EnderecoResource $enderecoResource
    ) {
        parent::__construct(
            $this->classificacaoResource,
            $this->suppParameterBag,
            $this->especieTarefaResource,
            $this->especieProcessoResource,
            $this->assuntoAdministrativoResource,
            $this->etiquetaResource,
            $this->modalidadeMeioResource,
            $this->modalidadeEtiquetaResource,
            $this->setorResource
        );
    }

    /**
     * @param array $dadosRequerimento
     * @return bool
     * @noinspection PhpMissingParentCallCommonInspection
     * @throws Exception
     */
    public function validate(array $dadosRequerimento): bool
    {
        $errors = $this->validator->validate(
            preg_replace('/\D/', '', $dadosRequerimento['cpfCnpj']),
            [new CpfCnpj()]
        );

        if ($errors->count()) {
            throw new Exception("CPF/CNPJ do devedor inválido! ".$errors->get(0)->getMessage());
        }

        $errors = $this->validator->validate(
            preg_replace('/\D/', '', $dadosRequerimento['cpfRepresentante'] ?? ''),
            [new CpfCnpj()]
        );

        if ($errors->count()) {
            throw new Exception("CPF representante ".$errors->get(0)->getMessage());
        }

        $errors = $this->validator->validate(
            $dadosRequerimento['numeroAcao'] ?? '',
            [new Cnj()]
        );

        if ($errors->count()) {
            throw new Exception("Ação Judicial: ".$errors->get(0)->getMessage());
        }

        if ($dadosRequerimento['email'] !== $dadosRequerimento['confirmeEmail']) {
            throw new Exception("E-mail's informados não conferem!");
        }

        return true;
    }

    /**
     * @param FormularioEntity $formulario
     * @param ProcessoEntity $processoEntity
     * @param ProcessoDTO $processoDTO
     * @return DadosProtocoloExterno|null
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function getDadosProtocoloExterno(
        FormularioEntity $formulario,
        ProcessoEntity $processoEntity,
        ProcessoDTO $processoDTO
    ): ?DadosProtocoloExterno {
        $dadosProtocoloExterno = parent::getDadosProtocoloExterno($formulario, $processoEntity, $processoDTO);
        if (!$dadosProtocoloExterno) {
            throw new Exception(
                "Não foi possível protocolar este documento pois não foram encontradas as configurações".
                " de protocolo externo para este tipo de formulário."
            );
        }

        if (!$this->suppParameterBag->has('supp_core.administrativo_backend.formularios.pgf')) {
            return $dadosProtocoloExterno;
        }

        $dadosFormulario = json_decode($processoDTO->getDadosRequerimento(), true);
        $dadosProtocoloExternoPGF = $this->suppParameterBag->get(
            'supp_core.administrativo_backend.formularios.pgf'
        );

        return match ($formulario->getSigla()) {
            'requerimento_pgf_cobranca_parcelamento' => $this->getDadosProtocoloExternoFormularioParcelamento(
                $dadosProtocoloExterno,
                $dadosFormulario,
                $dadosProtocoloExternoPGF
            ),
            'requerimento_pgf_cobranca_atendimento' => $this->getDadosProtocoloExternoFormularioAtendimento(
                $dadosProtocoloExterno,
                $dadosFormulario,
                $dadosProtocoloExternoPGF
            ),
            default => throw new Exception('Configuração não encontrada para este formulário.')
        };
    }

    /**
     * @param DadosProtocoloExterno $dadosProtocoloExterno
     * @param $dadosFormulario
     * @param $dadosProtocoloExternoPGF
     * @return DadosProtocoloExterno|null
     * @throws Exception
     * @throws NonUniqueResultException
     */
    private function getDadosProtocoloExternoFormularioAtendimento(
        DadosProtocoloExterno $dadosProtocoloExterno,
        $dadosFormulario,
        $dadosProtocoloExternoPGF
    ): ?DadosProtocoloExterno {
        if (!$this->suppParameterBag->has('supp_core.administrativo_backend.formularios.pgf')) {
            throw new Exception('Configuração não encontrada para este formulário.');
        }

        $estadoEntity = $this->estadoResource->getRepository()->find($dadosFormulario['estado']);

        $configSetorUnidadePorUFUsuario = current(
            array_filter(
                $dadosProtocoloExternoPGF['distribuicao_atendimento_geral'],
                fn($c) => in_array($estadoEntity->getUf(), $c['uf_domicilio_devedor'])
            )
        );

        $unidadeEntity = $this->setorResource->getRepository()->findOneBy(
            ['sigla' => $configSetorUnidadePorUFUsuario['sigla_unidade']]
        );

        if (!$unidadeEntity) {
            return null;
        }

        $setorEntity = $this->setorResource->getRepository()->findOneBy(
            [
                'nome' => $configSetorUnidadePorUFUsuario['nome_setor'],
                'unidade' => $unidadeEntity,
            ]
        );

        if (!$setorEntity) {
            return null;
        }

        $pessoaEntity = $this->pessoaResource->getRepository()->findOneBy([
            'numeroDocumentoPrincipal' => preg_replace("/[^0-9]/", "", $dadosFormulario['cpfCnpj']),
        ]);

        $pessoaMemoria = current(array_filter(
            $this->transactionManager->getToPersistEntities(
                $this->transactionManager->getCurrentTransactionId()
            ),
            fn($p) => $p instanceof PessoaEntity &&
                $p->getNumeroDocumentoPrincipal() === preg_replace("/[^0-9]/", "", $dadosFormulario['cpfCnpj'])
        ));

        if (!$pessoaEntity && !$pessoaMemoria) {
            $pessoaEntity = $this->criaPessoa($dadosFormulario);
        }

        $credor = current(
            array_filter(
                $dadosProtocoloExternoPGF['polo_passivo'],
                fn($c) => mb_strtoupper($dadosFormulario['credor']) === $c['nome']
            )
        );

        if (!$credor) {
            $credor = current(
                array_filter(
                    $dadosProtocoloExternoPGF['polo_passivo'],
                    fn($c) => '*' === $c['nome']
                )
            );
        }

        $pessoaCredorEntity = $this->pessoaResource->getRepository()->find($credor['id']);

        return $dadosProtocoloExterno
            ->setLembretesProcesso([$dadosFormulario['email']])
            ->setUnidade($unidadeEntity)
            ->setSetor($setorEntity)
            ->setRequerente($pessoaEntity ?? $pessoaMemoria)
            ->setRequerido($pessoaCredorEntity);
    }

    /**
     * @param DadosProtocoloExterno $dadosProtocoloExterno
     * @param $dadosFormulario
     * @param $dadosProtocoloExternoPGF
     * @return DadosProtocoloExterno|null
     * @throws Exception
     * @throws NonUniqueResultException
     */
    private function getDadosProtocoloExternoFormularioParcelamento(
        DadosProtocoloExterno $dadosProtocoloExterno,
        $dadosFormulario,
        $dadosProtocoloExternoPGF
    ): ?DadosProtocoloExterno {
        if (!$this->suppParameterBag->has('supp_core.administrativo_backend.formularios.pgf')) {
            throw new Exception('Configuração não encontrada para este formulário.');
        }

        $estadoEntity = $this->estadoResource->getRepository()->find($dadosFormulario['estado']);

        $configSetorUnidadePorUFUsuario = current(
            array_filter(
                $dadosProtocoloExternoPGF['distribuicao_parcelamento'],
                fn($c) => in_array($estadoEntity->getUf(), $c['uf_domicilio_devedor'])
            )
        );

        $unidadeEntity = $this->setorResource->getRepository()->findOneBy(
            ['sigla' => $configSetorUnidadePorUFUsuario['sigla_unidade']]
        );

        if (!$unidadeEntity) {
            return null;
        }

        $setorEntity = $this->setorResource->getRepository()->findOneBy(
            [
                'nome' => $configSetorUnidadePorUFUsuario['nome_setor'],
                'unidade' => $unidadeEntity,
            ]
        );

        if (!$setorEntity) {
            return null;
        }

        $pessoaEntity = $this->pessoaResource->getRepository()->findOneBy([
            'numeroDocumentoPrincipal' => preg_replace("/[^0-9]/", "", $dadosFormulario['cpfCnpj']),
        ]);

        $pessoaMemoria = current(array_filter(
            $this->transactionManager->getToPersistEntities(
                $this->transactionManager->getCurrentTransactionId()
            ),
            fn($p) => $p instanceof PessoaEntity &&
                $p->getNumeroDocumentoPrincipal() === preg_replace("/[^0-9]/", "", $dadosFormulario['cpfCnpj'])
        ));

        if (!$pessoaEntity && !$pessoaMemoria) {
            $pessoaEntity = $this->criaPessoa($dadosFormulario);
        }

        $credor = current(
            array_filter(
                $dadosProtocoloExternoPGF['polo_passivo'],
                fn($c) => mb_strtoupper($dadosFormulario['credor']) === $c['nome']
            )
        );

        if (!$credor) {
            $credor = current(
                array_filter(
                    $dadosProtocoloExternoPGF['polo_passivo'],
                    fn($c) => '*' === $c['nome']
                )
            );
        }

        $pessoaCredorEntity = $this->pessoaResource->getRepository()->find($credor['id']);

        return $dadosProtocoloExterno
            ->setLembretesProcesso([$dadosFormulario['email']])
            ->setUnidade($unidadeEntity)
            ->setSetor($setorEntity)
            ->setRequerente($pessoaEntity ?? $pessoaMemoria)
            ->setRequerido($pessoaCredorEntity);
    }

    /**
     * @param $dadosFormulario
     * @return PessoaEntity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function criaPessoa($dadosFormulario): PessoaEntity
    {
        $pessoaDTO = new Pessoa();
        $pessoaDTO->setNumeroDocumentoPrincipal(preg_replace("/[^0-9]/", "", $dadosFormulario['cpfCnpj']));
        $pessoaDTO->setNome($dadosFormulario['nomeRazaoSocial']);

        $contato = '';
        $telefone = $dadosFormulario['telefone'];
        $tipoTelefone = $dadosFormulario['telefone'] === 'celular' ? 'Celular' : 'Telefone';
        if ($telefone) {
            $contato .= "$tipoTelefone: $telefone\n";
        }

        $email = $dadosFormulario['email'];
        if ($email) {
            $contato .= "Email: $email";
        }
        $pessoaDTO->setContato($contato);

        $tipoPessoa = $dadosFormulario['tipoPessoa'] === 'fisica' ? 'PESSOA FÍSICA' : 'PESSOA JURÍDICA';
        $pessoaDTO->setModalidadeQualificacaoPessoa(
            $this->modalidadeQualificacaoPessoaResource->findOneBy(['valor' => $tipoPessoa])
        );

        $municipioEntity = null;
        if ($dadosFormulario['municipio']) {
            /** @var Municipio $municipioEntity */
            $municipioEntity = $this->municipioResource->getRepository()->find($dadosFormulario['municipio']);
            $pessoaDTO->setNaturalidade($municipioEntity);
        }

        if ($dadosFormulario['estado']) {
            $estadoEntity = $this->estadoResource->getRepository()->find($dadosFormulario['estado']);
            $pessoaDTO->setNacionalidade($estadoEntity?->getPais());
        }

        $transactionId = $this->transactionManager->getCurrentTransactionId();
        $pessoaEntity = $this->pessoaResource->create($pessoaDTO, $transactionId);

        $endereco = $dadosFormulario['complemento'] ?? $dadosFormulario['endereco'];
        $enderecoDTO = new Endereco();
        $enderecoDTO->setComplemento($endereco);
        $enderecoDTO->setCep(preg_replace("/[^0-9]/", "", $dadosFormulario['cep']));
        $enderecoDTO->setPrincipal(true);
        $enderecoDTO->setPessoa($pessoaEntity);
        if ($municipioEntity) {
            $enderecoDTO->setMunicipio($municipioEntity);
            $enderecoDTO->setPais($municipioEntity->getEstado()?->getPais());
        }
        $this->enderecoResource->create($enderecoDTO, $transactionId);

        return $pessoaEntity;
    }

    /**
     * @param FormularioEntity $formulario
     * @return bool
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function supports(FormularioEntity $formulario): bool
    {
        return in_array(
            $formulario->getSigla(),
            ['requerimento_pgf_cobranca_parcelamento', 'requerimento_pgf_cobranca_atendimento']
        );
    }

    /**
     * @param FormularioEntity $formulario
     * @return int
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getOrder(FormularioEntity $formulario): int
    {
        return 10;
    }
}

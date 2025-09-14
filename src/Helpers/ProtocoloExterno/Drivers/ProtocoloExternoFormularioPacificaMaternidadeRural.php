<?php

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/Drivers/ProtocoloExternoFormularioMaternidadeRural.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\Drivers;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoAdministrativoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\DadosProtocoloExterno;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Validator\Constraints\CpfCnpj;
use SuppCore\AdministrativoBackend\Validator\Constraints\NumeroBeneficio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProtocoloExternoFormularioMaternidadeRural.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProtocoloExternoFormularioPacificaMaternidadeRural extends ProtocoloExternoBasico
{
    /**
     * @param ClassificacaoResource         $classificacaoResource
     * @param SuppParameterBag              $suppParameterBag
     * @param EspecieTarefaResource         $especieTarefaResource
     * @param EspecieProcessoResource       $especieProcessoResource
     * @param AssuntoAdministrativoResource $assuntoAdministrativoResource
     * @param EtiquetaResource              $etiquetaResource
     * @param ModalidadeMeioResource        $modalidadeMeioResource
     * @param ModalidadeEtiquetaResource    $modalidadeEtiquetaResource
     * @param SetorResource                 $setorResource
     * @param ValidatorInterface            $validator
     * @param PessoaResource                $pessoaResource
     * @param TransactionManager            $transactionManager
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
        private readonly ValidatorInterface $validator,
        private readonly PessoaResource $pessoaResource,
        private readonly TransactionManager $transactionManager,
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
            $this->setorResource,
        );
    }

    /**
     * @param array $dadosRequerimento
     *
     * @return bool
     *
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @throws Exception
     */
    public function validate(array $dadosRequerimento): bool
    {
        if (!isset($dadosRequerimento['cpfBeneficiario'])) {
            throw new Exception('CPF do beneficiário é campo obrigatório!');
        }

        $errors = $this->validator->validate(
            preg_replace("/\D/", '', $dadosRequerimento['cpfBeneficiario']),
            [new CpfCnpj()]
        );

        if ($errors->count()) {
            throw new Exception('CPF do beneficiário é inválido!');
        }

        if (!isset($dadosRequerimento['cpfCrianca'])) {
            throw new Exception('CPF da criança é campo obrigatório!');
        }

        $errors = $this->validator->validate(
            preg_replace("/\D/", '', $dadosRequerimento['cpfCrianca']),
            [new CpfCnpj()]
        );

        if ($errors->count()) {
            throw new Exception('CPF da criança é inválido!');
        }

        if (!empty($dadosRequerimento['cpfConjuge'])) {
            $errors = $this->validator->validate(
                preg_replace("/\D/", '', $dadosRequerimento['cpfConjuge']),
                [new CpfCnpj()]
            );

            if ($errors->count()) {
                throw new Exception('CPF do conjuge é inválido!');
            }
        }

        $cpfCrianca = preg_replace('/[^0-9]+/', '', $dadosRequerimento['cpfCrianca']);
        $cpfBeneficiario = preg_replace('/[^0-9]+/', '', $dadosRequerimento['cpfBeneficiario']);
        if ($cpfCrianca === $cpfBeneficiario) {
            throw new Exception('CPF da criança não deve ser igual ao do beneficiário!');
        }

        $errors = $this->validator->validate(
            preg_replace("/\D/", '', $dadosRequerimento['numeroBeneficioNegado']),
            [new NumeroBeneficio()]
        );

        if ($errors->count()) {
            throw new Exception('Número do benefício é inválido!');
        }

        if (!isset($dadosRequerimento['dataRequerimentoAdministrativo'])) {
            throw new Exception('Data do requerimento administrativo é campo obrigatório!');
        }

        $hoje = (new DateTime())->setTime(0, 0);
        $dataRequerimentoAdministrativo =
            (new DateTime($dadosRequerimento['dataRequerimentoAdministrativo']))->setTime(0, 0);

        if ($dataRequerimentoAdministrativo >= $hoje) {
            throw new Exception('Data do requerimento administrativo deve ser anterior ou igual a hoje.');
        }

        if (!empty($dadosRequerimento['cpfConjuge'])) {
            $errors = $this->validator->validate(
                preg_replace("/\D/", '', $dadosRequerimento['cpfConjuge']),
                [new CpfCnpj()]
            );

            if ($errors->count()) {
                throw new Exception('CPF do cônjuge é inválido!');
            }
        }

        return true;
    }

    /**
     * @param FormularioEntity $formulario
     * @param ProcessoEntity   $processoEntity
     * @param ProcessoDTO      $processoDTO
     *
     * @return DadosProtocoloExterno|null
     *
     * @throws Exception
     */
    public function getDadosProtocoloExterno(
        FormularioEntity $formulario,
        ProcessoEntity $processoEntity,
        ProcessoDTO $processoDTO
    ): ?DadosProtocoloExterno {
        $dadosProtocoloExterno = parent::getDadosProtocoloExterno($formulario, $processoEntity, $processoDTO);
        if (!$dadosProtocoloExterno) {
            throw new Exception('Não foi possível protocolar este documento pois não foram encontradas as configurações de protocolo externo para este tipo de formulário.');
        }

        // No protocolo externo de pacifica maternidade rural as tarefa são abertas a depender das análises da IA.
        $dadosProtocoloExterno->setAbrirTarefa(false);

        $dadosFormulario = json_decode($processoDTO->getDadosRequerimento(), true);
        $cpfBeneficiario = preg_replace('/[^0-9]/', '', $dadosFormulario['cpfBeneficiario']);
        $pessoaEntity = $this->pessoaResource->findPessoaAdvanced(
            $cpfBeneficiario,
            $this->transactionManager->getCurrentTransactionId()
        );
        if (!$pessoaEntity) {
            throw new Exception(
                'Não foi possível criar o registro do beneficiário(a). Favor verificar se o CPF está correto.'
            );
        }
        $cpfCrianca = preg_replace('/[^0-9]/', '', $dadosFormulario['cpfCrianca']);
        $criancaEntity = $this->pessoaResource->findPessoaAdvanced(
            $cpfCrianca,
            $this->transactionManager->getCurrentTransactionId()
        );
        if (!$criancaEntity) {
            throw new Exception(
                'Não foi possível criar o registro da criança. Favor verificar se o CPF está correto.'
            );
        }

        if (!empty($dadosFormulario['cpfConjuge'])) {
            $cpfConjuge = preg_replace('/[^0-9]/', '', $dadosFormulario['cpfConjuge']);
            $conjugeEntity = $this->pessoaResource->findPessoaAdvanced(
                $cpfConjuge,
                $this->transactionManager->getCurrentTransactionId()
            );
            if (!$conjugeEntity) {
                throw new Exception(
                    'Não foi possível criar o registro do conjuge. Favor verificar se o CPF está correto.'
                );
            }
        }

        return $dadosProtocoloExterno
            ->setRequerente($pessoaEntity);
    }

    /**
     * @param FormularioEntity $formulario
     *
     * @return bool
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function supports(FormularioEntity $formulario): bool
    {
        return in_array(
            $formulario->getSigla(),
            ['requerimento.pacifica.salario_maternidade_rural']
        );
    }

    /**
     * @param FormularioEntity $formulario
     *
     * @return int
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getOrder(FormularioEntity $formulario): int
    {
        return 10;
    }
}

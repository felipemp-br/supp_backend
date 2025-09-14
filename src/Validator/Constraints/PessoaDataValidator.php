<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/PessoaDataValidador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use SuppCore\AdministrativoBackend\Validator\Constraints\PessoaData as ConstraintsPessoaData;

/**
 * Class PessoaDataValidator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PessoaDataValidator
{
    /**
     * {@inheritdoc}
     */
    public static function validate($object, ExecutionContextInterface $context, $payload): void
    {
        $dataNascimentoENula = false;
        $dataObitoENula = false;
        $pessoaData = new ConstraintsPessoaData();

        if (null === $object->getDataNascimento() || '' === $object->getDataNascimento()) {
            $dataNascimentoENula = true;
        }

        if (null === $object->getDataObito() || '' === $object->getDataNascimento()) {
            $dataObitoENula = true;
        }
        // Esse está sendo o mesmo teste que o 
        // https://github.com/supergovbr/supp-administrativo-frontend/blob/develop/src/%40cdk/components/pessoa/cdk-pessoa-form/cdk-pessoa-form.component.ts#L178
        // Mas podem alterar isso no banco e o teste vai falhar
        $pessoaFisica = true;
        if ("PESSOA FÍSICA" !== $object->getModalidadeQualificacaoPessoa()->getDescricao()) {
            $pessoaFisica = false;
        }
        if (
            !$dataNascimentoENula
            && $object->getDataNascimento() > date_create("today")
        ) {
            $mensagem = $pessoaData->messagePessoaFisicaNascimentoHoje;
            if (!$pessoaFisica) {
                $mensagem = $pessoaData->messagePessoaJuridicaCriacaoHoje;
            }
            $context->buildViolation($mensagem)
                ->atPath('dataNascimento')
                ->addViolation();
        }
        if (
            !$dataObitoENula
            && $object->getDataObito() > date_create("today")
        ) {
            $mensagem = $pessoaData->messagePessoaFisicaObitoHoje;
            if (!$pessoaFisica) {
                $mensagem = $pessoaData->messagePessoaJuridicaExtincaoHoje;
            }
            $context->buildViolation($mensagem)
                ->atPath('dataObito')
                ->addViolation();
        }
        if (
            !$dataObitoENula && !$dataNascimentoENula
            && ($object->getDataObito() < $object->getDataNascimento())
        ) {
            $mensagem = $pessoaData->messagePessoaFisicaNascimentoObito;
            if (!$pessoaFisica) {
                $mensagem = $pessoaData->messagePessoaJuridicaCriacaoExtincao;
            }
            $context->buildViolation($mensagem)
                ->atPath('dataObito')
                ->addViolation();
        }
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Validator/Constraints/PessoaData.php.
 *
 */

namespace SuppCore\AdministrativoBackend\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class PessoaData.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 */
class PessoaData extends Constraint
{
    public string $messagePessoaFisicaObitoHoje = 'A data de óbito não pode ser maior que a data de hoje!';
    public string $messagePessoaFisicaNascimentoHoje = 'A data de nascimento não pode ser maior que a data de hoje!';
    public string $messagePessoaFisicaNascimentoObito = 'A data de óbito não pode ser menor que a data de nascimento!';
    
    public string $messagePessoaJuridicaExtincaoHoje = 'A data de extinção não pode ser maior que a data de hoje!';
    public string $messagePessoaJuridicaCriacaoHoje = 'A data de criação não pode ser maior que a data de hoje!';
    public string $messagePessoaJuridicaCriacaoExtincao = 'A data de extinção não pode ser menor que a data de criação!';
}

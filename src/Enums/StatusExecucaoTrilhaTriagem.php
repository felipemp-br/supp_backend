<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * StatusExecucaoTrilhaTriagem.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum StatusExecucaoTrilhaTriagem: int
{
    case PENDENTE = 0;
    case INICIADA = 2;
    case SUCESSO = 1;
    case ERRO = 3;
    case SUPRIMIDA = 4;
}

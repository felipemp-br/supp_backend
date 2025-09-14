<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums;

/**
 * FinishReasonEnum.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum FinishReasonEnum: string
{
    case FINISH_REASON_UNSPECIFIED = 'FINISH_REASON_UNSPECIFIED';
    case STOP = 'STOP';
    case MAX_TOKENS = 'MAX_TOKENS';
    case SAFETY = 'SAFETY';
    case RECITATION = 'RECITATION';
    case OTHER = 'OTHER';
}

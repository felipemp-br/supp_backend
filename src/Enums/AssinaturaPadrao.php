<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 *
 */
enum AssinaturaPadrao: string
{
    case CAdES = 'CAdES'; // CMS Advanced Electronic Signature
    case PAdES = 'PAdES'; // PDF Advanced Electronic Signature
    case XAdES = 'XAdES'; // XML Advanced Electronic Signatures
}

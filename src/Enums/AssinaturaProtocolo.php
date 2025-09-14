<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * O protocolo corresponde a primeira parte da credencial (a1, a3 ou neoid) <br/>
 * Obs: não é o tipo de certificado!
 *
 * a1:govBr//[JWT] <br/>
 * a1:ldap//base64([senha]) <br/>
 * a1:interno//base64([senha]) <br/>
 * a3:[UUID Assinador] <br/>
 * neoid:[UUID - cód.Autorização] <br/>
 *
 */
enum AssinaturaProtocolo: string
{
    case A1 = 'signer';             // Assinador Interno (SUPP Signer)
    case A3 = 'externo';            // Assinador Externo (SUPP Assinador Digital)
    case NeoID = 'nuvem-serpro';    // Nuvem SERPRO (QR Code no site)
}

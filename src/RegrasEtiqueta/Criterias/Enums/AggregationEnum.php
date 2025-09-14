<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums;

/**
 * AggregationEnum.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum AggregationEnum: string
{
    case AND = 'andX';
    case OR = 'orX';
}

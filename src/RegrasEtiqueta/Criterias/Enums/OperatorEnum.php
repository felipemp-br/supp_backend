<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Enums;

/**
 * OperatorEnum.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum OperatorEnum: string
{
    case EQUAL = 'eq';
    case ALL_ELEMENTS_EQUAL = '=eq';
    case NO_ELEMENTS_EQUAL = '!eq';
    case AT_LEAST_ONE_ELEMENT_EQUAL = '>eq';
    case NOT_EQUAL = 'neq';
    case ALL_ELEMENTS_NOT_EQUAL = '=neq';
    case NO_ELEMENTS_NOT_EQUAL = '!neq';
    case AT_LEAST_ONE_ELEMENT_NOT_EQUAL = '>neq';
    case IN = 'in';
    case NOT_IN = 'notIn';
    case IS_NULL = 'isNull';
    case IS_NOT_NULL = 'isNotNull';
    case CONTAINS = 'like';
    case ALL_ELEMENTS_CONTAINS = '=like';
    case NO_ELEMENTS_CONTAINS = '!like';
    case AT_LEAST_ONE_ELEMENT_CONTAINS = '>like';
    case NOT_CONTAINS = 'notLike';
    case ALL_ELEMENTS_NOT_CONTAINS = '=notLike';
    case NO_ELEMENTS_NOT_CONTAINS = '!notLike';
    case AT_LEAST_ONE_ELEMENT_NOT_CONTAINS = '>notLike';
    case GREATER_THAN = 'gt';
    case GREATER_THAN_EQUAL = 'gte';
    case LOWER_THAN = 'lt';
    case LOWER_THAN_EQUAL = 'lte';
    case BETWEEN = 'between';
}

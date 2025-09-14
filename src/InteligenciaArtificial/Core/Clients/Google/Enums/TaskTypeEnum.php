<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Google\Enums;

/**
 * TaskTypeEnum.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum TaskTypeEnum: string
{
    case TASK_TYPE_UNSPECIFIED = 'TASK_TYPE_UNSPECIFIED';
    case RETRIEVAL_QUERY = 'RETRIEVAL_QUERY';
    case RETRIEVAL_DOCUMENT = 'RETRIEVAL_DOCUMENT';
    case SEMANTIC_SIMILARITY = 'SEMANTIC_SIMILARITY';
    case CLASSIFICATION = 'CLASSIFICATION';
    case CLUSTERING = 'CLUSTERING';
}

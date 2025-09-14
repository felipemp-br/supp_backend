<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Resource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits;

/**
 * Trait Resource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait RestResourceLifeCycles
{
    // Traits
    use RestResourceFind;
    use RestResourceFindOne;
    use RestResourceFindOneBy;
    use RestResourceCount;
    use RestResourceIds;
    use RestResourceCreate;
    use RestResourceUpdate;
    use RestResourceDelete;
    use RestResourceUndelete;
    use RestResourceSave;
}

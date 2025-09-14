<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger  =Hidrata o DTO com o gênero do setor e a mod_orgao_central
 * @classeSwagger=Trigger0005
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{

    public function supports(): array
    {
        return [
            Setor::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto): void
    {
        if ($restDto->getParent()) {
            $restDto->setGeneroSetor($restDto->getEspecieSetor()->getGeneroSetor());
            $restDto->setModalidadeOrgaoCentral($restDto->getUnidade()->getModalidadeOrgaoCentral());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

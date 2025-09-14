<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0007.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use function md5;
use function rand;
use function substr;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use function uniqid;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Os documentos novos tem uma chave de acesso gerada automaticamente!
 * @classeSwagger=Trigger0007
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0007 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            DocumentoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DocumentoDTO|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));
    }

    public function getOrder(): int
    {
        return 1;
    }
}

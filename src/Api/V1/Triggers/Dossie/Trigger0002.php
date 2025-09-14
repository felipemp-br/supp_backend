<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Dossie;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Atualiza o número de versão, pessoa e numero de documento principal.
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        private PessoaResource $pessoaResource,
    ) {
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            DossieDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DossieDTO $restDto
     * @param EntityInterface|DossieEntity $entity
     * @param string $transactionId
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(RestDtoInterface | DossieDTO $restDto, EntityInterface | DossieEntity $entity, string $transactionId): void
    {
        // ver \SuppCore\AdministrativoBackend\Api\V1\Rules\Dossie\Rule0001.php
        if (!$restDto->getPessoa()) {
            $restDto->setPessoa(
                $this
                    ->pessoaResource
                    ->findOneBy(['numeroDocumentoPrincipal' => $restDto->getNumeroDocumentoPrincipal()])
            );
        }

        if (!$restDto->getNumeroDocumentoPrincipal()) {
            $restDto->setNumeroDocumentoPrincipal($restDto->getPessoa()->getNumeroDocumentoPrincipal());
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}

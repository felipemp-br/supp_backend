<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ModeloRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0010.
 *
 * @descSwagger=Caso seja informado documento de origem, será incluído no dto o código do modelo especíco!
 * @classeSwagger=Trigger0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0010 implements TriggerInterface
{
    /**
     * Trigger0010 constructor.
     *
     * @param ModeloRepository $modeloRepository
     */
    public function __construct(
        private ModeloRepository $modeloRepository,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeAprovar',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getDocumentoOrigem()) {
            $restDto->setModelo(
                $this->modeloRepository->findOneBy(
                    ['nome' => $this->parameterBag->get('constantes.entidades.modelo.const_1')]
                )
            );
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}

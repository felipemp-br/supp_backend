<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0018.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Utils\lib\simple_html_dom;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0018.
 *
 * @descSwagger=Salva apenas o conteudo do body quando se tratar da edição de um modelo.
 * @classeSwagger=Trigger0018
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0018 implements TriggerInterface
{

    /**
     */
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ComponenteDigitalDTO|null $restDto
     * @param EntityInterface|ComponenteDigitalEntity $entity
     * @param string $transactionId
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        RestDtoInterface|ComponenteDigitalDTO|null $restDto,
        EntityInterface|ComponenteDigitalEntity $entity,
        string $transactionId
    ): void {
        if ($entity->getDocumento()?->getModelo()) {
            $parser = (new simple_html_dom())->load($restDto->getConteudo());
            $body = $parser->find('body');
            $body = is_array($body) ? end($body): $body;

            if ($body) {
                $restDto->setConteudo($body->innertext);
                $restDto->setHash(
                    hash($this->parameterBag->get('algoritmo_hash_componente_digital'), $restDto->getConteudo())
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 18;
    }
}

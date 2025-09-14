<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/ComponenteDigital/Pipe0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0008.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0008 implements PipeInterface
{
    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private RequestStack $requestStack
    ) {
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null &$restDto,
        ComponenteDigitalEntity|EntityInterface $entity
    ): void {
        // não tem request
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        if ((null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->asPdf)) {
                $restDto->setHash(
                    hash($this->parameterBag->get('algoritmo_hash_componente_digital'), $entity->getConteudo())
                );
                $restDto->setTamanho(strlen($entity->getConteudo()));
                $restDto->setMimetype('application/pdf');
                $restDto->setExtensao('pdf');
                $restDto->setFileName(str_replace(
                    ['.html', '.HTML'],
                    '.pdf',
                    $entity->getFileName()
                ));
            }
            if (isset($context->asXls)) {
                $restDto->setHash(
                    hash($this->parameterBag->get('algoritmo_hash_componente_digital'), $entity->getConteudo())
                );
                $restDto->setTamanho(strlen($entity->getConteudo()));
                $restDto->setMimetype('application/xls');
                $restDto->setExtensao('xls');
                $restDto->setFileName(str_replace(
                    ['.html', '.HTML'],
                    '.xls',
                    $entity->getFileName()
                ));
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}

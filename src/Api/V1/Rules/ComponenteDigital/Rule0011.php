<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0011.
 *
 * @descSwagger  =Usuário não possui poderes para editar o documento!
 * @classeSwagger=Rule0011
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private AuthorizationCheckerInterface $authorizationChecker;

    private ComponenteDigitalRepository $componenteDigitalRepository;

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker,
        ComponenteDigitalRepository $componenteDigitalRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
        $this->componenteDigitalRepository = $componenteDigitalRepository;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getId() && $restDto->getHash()) {
            // encontrar os componentes com esse hash
            $componentesDigitais = $this
                ->componenteDigitalRepository
                ->findBy(
                    ['hash' => $restDto->getHashAntigo()?:$restDto->getHash()]
                );

            if (!count($componentesDigitais)) {
                $this->rulesTranslate->throwException('componenteDigital', '0011a');
            }

            foreach ($componentesDigitais as $componenteDigital) {
                if (false === $this->authorizationChecker->isGranted(
                        'VIEW',
                        $componenteDigital->getDocumento()
                    )
                ) {
                    $this->rulesTranslate->throwException('componenteDigital', '0011b');
                }

                $processo = $componenteDigital->getDocumento()->getJuntadaAtual()?->getVolume()->getProcesso();
                if ($componenteDigital->getDocumento()->getJuntadaAtual() &&
                    (
                        (false === $this->authorizationChecker->isGranted('VIEW', $processo)) ||
                        ($processo->getClassificacao() &&
                            $processo->getClassificacao()->getId() &&
                            (false === $this->authorizationChecker->isGranted(
                                    'VIEW',
                                    $processo->getClassificacao()
                                )
                            )
                        ))) {
                    $this->rulesTranslate->throwException('componenteDigital', '0011b');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

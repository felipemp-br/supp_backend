<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0018.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use function in_array;
use function ord;
use function pathinfo;
use function sprintf;
use function str_split;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0018.
 *
 * @descSwagger=O componente digital não parece ser da extensão constante no nome do arquivo!
 * @classeSwagger=Rule0018
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0018 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ParameterBagInterface $parameterBag;

    /**
     * Rule0018 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->parameterBag = $parameterBag;
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
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $componenteDigitalConfig = $this->parameterBag->get(
            'supp_core.administrativo_backend.componente_digital_extensions'
        );

        $extensao = strtolower(pathinfo($restDto->getFileName(), PATHINFO_EXTENSION));

        if (!isset($componenteDigitalConfig[$extensao])) {
            return true;
        }

        $typeSignature = $componenteDigitalConfig[$extensao]['typeSignature'];

        if (!$typeSignature || !count($typeSignature)) {
            return true;
        }

        $hex_ary = [];
        $ok = false;
        if($restDto->getConteudo()){
            foreach (str_split(substr($restDto->getConteudo(), 0, 24)) as $chr) {
                $hex_ary[] = sprintf('%02X', ord($chr));
            }
            $s = '';
            foreach ($hex_ary as $item) {
                $s .= $item;
                if (in_array($s, $typeSignature)) {
                    $ok = true;
                    break;
                }
                $s .= ' ';
            }
        }

        if (!$ok) {
            $this->rulesTranslate->throwException('componenteDigital', '0018');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}

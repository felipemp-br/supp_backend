<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ModalidadeAcaoEtiqueta/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ModalidadeAcaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAcaoEtiqueta as ModalidadeAcaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta as ModalidadeAcaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeAcaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica unicidade de nome
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate                          $rulesTranslate;
    private ModalidadeAcaoEtiquetaRepository $modalidadeAcaoEtiquetaRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ModalidadeAcaoEtiquetaRepository $modalidadeAcaoEtiquetaRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->modalidadeAcaoEtiquetaRepository = $modalidadeAcaoEtiquetaRepository;
    }

    public function supports(): array
    {
        return [
            ModalidadeAcaoEtiquetaDTO::class => [
                'beforeCreate',
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param ModalidadeAcaoEtiquetaDTO|RestDtoInterface|null $restDto
     * @param ModalidadeAcaoEtiquetaEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $modalidadeAcaoEtiqueta = $this->modalidadeAcaoEtiquetaRepository->findOneBy(
            [
                'valor' => $restDto->getValor(),
                'modalidadeEtiqueta' => $restDto->getModalidadeEtiqueta(),
            ]
        );

        if ($modalidadeAcaoEtiqueta && $modalidadeAcaoEtiqueta->getId() != $restDto->getId()) {
            $this->rulesTranslate->throwException('modalidadeAcaoEtiqueta', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

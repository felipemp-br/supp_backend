<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/RegraEtiqueta/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\RegraEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\RegraEtiqueta as RegraEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta as RegraEtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Não pode ser criada uma regra com um momento de disparo não disponível para a vinculação da etiqueta.
 *
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{

    /**
     * Constructor.
     *
     * @param RulesTranslate              $rulesTranslate
     * @param VinculacaoUsuarioRepository $vinculacaoEtiquetaRepository
     *
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository
    ) {
    }

    public function supports(): array
    {
        return [
            RegraEtiquetaDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RegraEtiquetaDTO|RestDtoInterface|null $restDto
     * @param RegraEtiquetaEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $vinculacaoEtiqueta = $this->vinculacaoEtiquetaRepository
            ->findVinculacaoEtiqueta($restDto->getEtiqueta()->getId());

        $permissao = false;
        if (!$permissao && $restDto->getMomentoDisparoRegraEtiqueta()->getDisponivelUsuario()
            && $vinculacaoEtiqueta->getUsuario()) {
            $permissao = true;
        }
        if (!$permissao && $restDto->getMomentoDisparoRegraEtiqueta()->getDisponivelSetor()
            && $vinculacaoEtiqueta->getSetor()) {
            $permissao = true;
        }
        if (!$permissao && $restDto->getMomentoDisparoRegraEtiqueta()->getDisponivelUnidade()
            && $vinculacaoEtiqueta->getUnidade()) {
            $permissao = true;
        }
        if (!$permissao && $restDto->getMomentoDisparoRegraEtiqueta()->getDisponivelOrgaoCentral()
            && $vinculacaoEtiqueta->getModalidadeOrgaoCentral()) {
            $permissao = true;
        }

        if (!$permissao) {
            $this->rulesTranslate
                ->throwException('regraEtiqueta', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

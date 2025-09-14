<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Avaliacao;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao as AvaliacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Avaliacao;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\AvaliacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=A mesma avaliação só pode re-avaliada a cada 30 dias pelo mesmo usuário que a criou.
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RulesTranslate $rulesTranslate,
        private AvaliacaoRepository $avaliacaoRespository
    ) {
    }

    public function supports(): array
    {
        return [
            AvaliacaoDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param EntityInterface|Avaliacao $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $usuarioRequisicao = $this->tokenStorage->getToken()?->getUser();
        if ($usuarioRequisicao) {
            // recupera todas as avaliacoes do usuário sobre o mesmo objeto
            $avaliacoes = $this
                ->avaliacaoRespository
                ->findBy(
                    ['objetoAvaliado' => $restDto->getObjetoAvaliado(), 'criadoPor' => $usuarioRequisicao]
                );

            if ($avaliacoes) {
                // ordena as avaliações por data
                uasort(
                    $avaliacoes,
                    fn ($a, $b) => $b->getCriadoEm() <=> $a->getCriadoEm()
                );

                if (reset($avaliacoes)->getCriadoEm()->diff(new DateTime())->days <= 30) {
                    $this->rulesTranslate->throwException('avaliacao', '0001');
                }
            }

            return true;
        }
        // caso o token não possua usuário
        $this->rulesTranslate->throwException('avaliacao', '0002');
    }

    public function getOrder(): int
    {
        return 0;
    }
}

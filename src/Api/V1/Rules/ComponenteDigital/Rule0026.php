<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0026.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoAssinaturaExternaRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0026.
 *
 * @descSwagger=Usuário não possui poderes para ver/editar a minuta!
 * @classeSwagger=Rule0026
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0026 implements RuleInterface
{
    /**
     * Rule0026 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private TokenStorageInterface $tokenStorage,
        private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        private CompartilhamentoRepository $compartilhamentoRepository,
        private CoordenadorService $coordenadorService,
        private AuthorizationCheckerInterface $authorizationChecker,
        private VinculacaoDocumentoAssinaturaExternaRepository $vinculacaoDocumentoAssinaturaExternaRepository,
        private ComponenteDigitalRepository $componenteDigitalRepository
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforeDownload',
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
        // somente podem acessar a minuta o usuario responsável pela tarefa, o seu assessor, e quem tem compartilhamento
        if ($entity->getDocumento()->getTarefaOrigem() &&
            !$entity->getDocumento()->getJuntadaAtual()) {
            $tarefa = $entity->getDocumento()->getTarefaOrigem();
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            // usuario responsavel
            if ($usuario->getId() === $tarefa->getUsuarioResponsavel()->getId()) {
                return true;
            }
            // assessor
            if ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $tarefa->getUsuarioResponsavel()->getId(),
                $usuario->getId()
            )) {
                return true;
            }
            // compartilhamento
            if ($this->compartilhamentoRepository->findByTarefaAndUsuario(
                $tarefa->getId(),
                $usuario->getId()
            )) {
                return true;
            }

            // é um coordenador responsável? Pode.
            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$tarefa->getSetorResponsavel()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$tarefa->getSetorResponsavel()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$tarefa->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
                );
            if ($isCoordenador) {
                return true;
            }

            //é usuario externo e recebeu solicitacao de assinatura
            if ($this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')) {
                $assinaturaExterna = $this->vinculacaoDocumentoAssinaturaExternaRepository->findByDocumentoNumeroDocumentoPrincipal($entity->getDocumento()->getId(), $this->tokenStorage->getToken()->getUser()->getUsername());
                if($assinaturaExterna) {
                    if(new DateTime() < $assinaturaExterna->getExpiraEm()) {
                        return true;
                    }
                    if($this->componenteDigitalRepository->isAssinadoUser($entity->getId(), $this->tokenStorage->getToken()->getUser()->getId())) {
                        return true;
                    }
                }
            }

            $this->rulesTranslate->throwException('componenteDigital', '0026');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}

<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Handlers;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AbstractAcoesEtiquetaHandler;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AcoesEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\CompartilharTarefaModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CompartilhamentoResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Contato;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\GrupoContatoRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;

/**
 * CompartilharTarefaHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompartilharTarefaHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param CompartilhamentoResource $compartilhamentoResource
     * @param UsuarioRepository        $usuarioRepository
     * @param GrupoContatoRepository   $grupoContatoRepository
     */
    public function __construct(
        private readonly CompartilhamentoResource $compartilhamentoResource,
        private readonly UsuarioRepository $usuarioRepository,
        private readonly GrupoContatoRepository $grupoContatoRepository,
    ) {
    }

    /**
     * Verifica se a ação é suportada pelo handler.
     *
     * @param Acao                        $acao
     * @param VinculacaoEtiquetaInterface $vinculacaoEtiqueta
     *
     * @return bool
     */
    public function support(
        Acao $acao,
        VinculacaoEtiquetaInterface $vinculacaoEtiqueta
    ): bool {
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_COMPARTILHAR_TAREFA
                ->isEqual($acao->getModalidadeAcaoEtiqueta()->getIdentificador())
                && $vinculacaoEtiqueta instanceof VinculacaoEtiqueta
                && $vinculacaoEtiqueta->getTarefa();
    }

    /**
     * Executa a ação.
     *
     * @param Acao                        $acao
     * @param VinculacaoEtiquetaInterface $vinculacaoEtiqueta
     * @param string                      $transactionId
     *
     * @return void
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function handle(
        Acao $acao,
        VinculacaoEtiquetaInterface $vinculacaoEtiqueta,
        string $transactionId
    ): void {
        $model = $this->getModel(
            $acao->getContexto(),
            CompartilharTarefaModel::class,
        );
        $compartilhamentoDTO = (new Compartilhamento())
            ->setTarefa(
                $vinculacaoEtiqueta->getTarefa()
            );
        $usuarioResponsavelId = $vinculacaoEtiqueta->getTarefa()->getUsuarioResponsavel()->getId();
        switch (true) {
            case $model->getUsuarioId() !== null:
                $compartilhamentoDTO->setUsuario(
                    $this->usuarioRepository->find($model->getUsuarioId())
                );
                $this->compartilhamentoResource->create($compartilhamentoDTO, $transactionId);
                break;
            case $model->getSetorId() !== null:
                $usuarios = array_filter(
                    $this->usuarioRepository->findUsuariosBySetor($model->getSetorId()),
                    fn (Usuario $usuario) => $usuario->getId() !== $usuarioResponsavelId
                );
                foreach ($usuarios as $usuario) {
                    $compartilhamentoDTOSetor = clone $compartilhamentoDTO;
                    $compartilhamentoDTOSetor->setUsuario($usuario);
                    $this->compartilhamentoResource->create($compartilhamentoDTOSetor, $transactionId);
                }
                break;
            case $model->getGrupoContatoId() !== null:
                $grupoContato = $this->grupoContatoRepository->find($model->getGrupoContatoId());
                /** @var Contato $contato */
                foreach ($grupoContato->getContatos() as $contato) {
                    $compartilhamentoDTOUsuario = clone $compartilhamentoDTO;
                    switch (true) {
                        case $contato->getUsuario() !== null:
                            $compartilhamentoDTOUsuario->setUsuario($contato->getUsuario());
                            $this->compartilhamentoResource->create($compartilhamentoDTOUsuario, $transactionId);
                            break;
                        case $contato->getSetor() !== null:
                            $usuarios = array_filter(
                                $this->usuarioRepository->findUsuariosBySetor($model->getSetorId()),
                                fn (Usuario $usuario) => $usuario->getId() !== $usuarioResponsavelId
                            );
                            foreach ($usuarios as $usuario) {
                                $compartilhamentoDTOSetor = clone $compartilhamentoDTO;
                                $compartilhamentoDTOSetor->setUsuario($usuario);
                                $this->compartilhamentoResource->create($compartilhamentoDTOSetor, $transactionId);
                            }
                            break;
                        case $contato->getUnidade() !== null:
                            $usuarios = array_filter(
                                $this->usuarioRepository->findUsuariosByUnidade($model->getSetorId()),
                                fn (Usuario $usuario) => $usuario->getId() !== $usuarioResponsavelId
                            );
                            foreach ($usuarios as $usuario) {
                                $compartilhamentoDTOUnidade = clone $compartilhamentoDTO;
                                $compartilhamentoDTOUnidade->setUsuario($usuario);
                                $this->compartilhamentoResource->create($compartilhamentoDTOUnidade, $transactionId);
                            }
                            break;
                    }
                }
                break;
        }
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/TarefaResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use IntlDateFormatter;
use SuppCore\AdministrativoBackend\Utils\TrataDistribuicaoServiceInterface;
use function get_class;
use Redis;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Tarefa as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TarefaResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class TarefaResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /** Todos usuários aptos a receber a tarefa (com peso na lotação acima de 0) */
    public const REGRA_DISTRIBUICAO_INDIFERENTE = 0;
    /** Usuários distribuidores aptos a receber a tarefa (com peso na lotação acima de 0) */
    public const REGRA_DISTRIBUICAO_APENAS_DISTRIBUIDORES = 1;
    /** Usuários comuns (não distribuidores) aptos a receber a tarefa (com peso na lotação acima de 0) */
    public const REGRA_DISTRIBUICAO_EXCLUI_DISTRIBUIDORES = 2;
    /** Apenas coordenadores do setor recebem a tarefa */
    public const REGRA_DISTRIBUICAO_APENAS_COORDENADORES = 3;

    public function __construct(
        protected Repository $repository,
        protected ValidatorInterface $validator,
        protected TokenStorageInterface $tokenStorage,
        protected Redis $redisClient,
        protected AfastamentoRepository $afastamentoRepository,
        protected UsuarioRepository $usuarioRepository,
        protected LotacaoRepository $lotacaoRepository,
        protected VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        protected AfastamentoResource $afastamentoResource,
        protected SetorRepository $setorRepository,
        protected TransicaoWorkflowRepository $transicaoWorkflowRepository,
        protected TrataDistribuicaoServiceInterface $trataDistribuicaoService
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Tarefa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function toggleLida(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        if ($entity->getDataHoraLeitura()) {
            $dto->setDataHoraLeitura(null);
        } else {
            $dto->setDataHoraLeitura(new DateTime());
        }

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeToggleLida($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterToggleLida($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertToggleLida');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
    }

    public function afterToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterToggleLida');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function ciencia(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeCiencia($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterCiencia($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeCiencia(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertCiencia');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeCiencia');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeCiencia');
    }

    public function afterCiencia(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterCiencia');
    }

    /**
     * Distribui automaticamente as tarefas do usuário para o coordenador do setor ou para o protocolo do setor.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function distribuirTarefasUsuario(
        int $usuarioId,
        string $transactionId,
        ?bool $skipValidation = null
    ): RestDtoInterface {
        $skipValidation ??= false;

        $usuariosSetor = [];
        $usuarioTarefasEntity = $this->usuarioRepository->getReference($usuarioId);
        $usuarioTarefasDTO = $this->getDtoForEntity(
            $usuarioId,
            UsuarioDTO::class,
            null,
            $usuarioTarefasEntity
        );

        // Before callback method call
        $this->beforeDistribuirTarefasUsuario($usuarioId, $usuarioTarefasDTO, $usuarioTarefasEntity, $transactionId);

        $tarefas = $this->getRepository()->findTarefasAbertasUsuario($usuarioTarefasEntity);

        foreach ($tarefas as $tarefaEntity) {
            $tarefaId = $tarefaEntity->getId();

            $tarefaDTO = $this->getDtoForEntity($tarefaId, Tarefa::class, null, $tarefaEntity);
            $this->validateDto($tarefaDTO, $skipValidation);

            $setor = $tarefaDTO->getSetorResponsavel();

            if (!isset($usuariosSetor[$setor->getId()])) {
                $usuariosSetor[$setor->getId()] = array_filter(
                    $this->retornaUsuariosRegraDistribuicao(
                        $tarefaDTO,
                        self::REGRA_DISTRIBUICAO_APENAS_COORDENADORES
                    ),
                    fn ($usuarioSetor) => $usuarioSetor->getId() !== $usuarioId
                );
            }

            if (empty($usuariosSetor[$setor->getId()])
                || empty(
                    $this->afastamentoResource->limpaListaUsuariosAfastados(
                        $usuariosSetor[$setor->getId()],
                        $tarefaDTO->getDataHoraFinalPrazo()
                    )
                )
            ) {
                $setor = $this->setorRepository->findProtocoloInUnidade(
                    $tarefaDTO->getSetorResponsavel()->getUnidade()
                );
                $tarefaDTO->setSetorResponsavel($setor);

                $usuariosSetor[$setor->getId()] = $this->retornaUsuariosRegraDistribuicao(
                    $tarefaDTO,
                    $this->retornaRegraDistribuicao($tarefaDTO)
                );
            }

            if (empty($usuariosSetor[$setor->getId()])) {
                $setor = $this->setorRepository->findProtocoloInUnidade(
                    $tarefaDTO->getSetorResponsavel()->getUnidade()
                );
                throw new \InvalidArgumentException(
                    'Não há usuários disponíveis para serem lotados. Setor: '.$setor->getNome()
                );
            }

            $this->trataDistribuicaoService->tratarDistribuicaoAutomatica($tarefaDTO, $usuariosSetor[$setor->getId()]);

            $this->update($tarefaDTO->getId(), $tarefaDTO, $transactionId);
        }

        // After callback method call
        $this->afterDistribuirTarefasUsuario($usuarioId, $usuarioTarefasDTO, $usuarioTarefasEntity, $transactionId);

        return $usuarioTarefasDTO;
    }

    public function beforeDistribuirTarefasUsuario(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertDistribuirTarefaUsuario');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertUpdate');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeDistribuirTarefasUsuario');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeDistribuirTarefasUsuario');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeUpdate');
    }

    public function afterDistribuirTarefasUsuario(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterDistribuirTarefasUsuario');
    }

    /**
     * Método que retorna os usuários disponíveis para distribuicao.
     *
     * @param RestDtoInterface|Tarefa $tarefaDTO
     */
    public function retornaUsuariosRegraDistribuicao(RestDtoInterface|Tarefa $tarefaDTO, int $regraDistribuicao): array
    {
        return match ($regraDistribuicao) {
            self::REGRA_DISTRIBUICAO_INDIFERENTE => $this->usuarioRepository->findUsuariosDisponiveisSetor(
                $tarefaDTO->getSetorResponsavel()->getId()
            ),

            self::REGRA_DISTRIBUICAO_APENAS_DISTRIBUIDORES => $this->usuarioRepository->findUsuariosDistribuidoresDisponiveisSetor(
                $tarefaDTO->getSetorResponsavel()->getId()
            ),

            self::REGRA_DISTRIBUICAO_EXCLUI_DISTRIBUIDORES => $this->usuarioRepository->findUsuariosNaoDistribuidoresDisponiveisSetor(
                $tarefaDTO->getSetorResponsavel()->getId()
            ),

            self::REGRA_DISTRIBUICAO_APENAS_COORDENADORES => $this->usuarioRepository->findUsuariosCoordenadoresDisponiveisSetor(
                $tarefaDTO->getSetorResponsavel()->getId()
            ),

            default => throw new \InvalidArgumentException('Regra de distribuição inexistente.')
        };
    }

    /**
     * Método que retorna o código da regra de distribuição.
     *
     * @param RestDtoInterface|Tarefa $tarefaDto
     *
     * @throws Exception
     */
    public function retornaRegraDistribuicao(
        RestDtoInterface|Tarefa $tarefaDto,
        EntityInterface|Entity $tarefaEntity = null
    ): int {
        $regraDistribuicao = self::REGRA_DISTRIBUICAO_INDIFERENTE;

        if (!$this->tokenStorage->getToken() ||
            (false === $this->tokenStorage->getToken()->getUser() instanceof Usuario)) {
            return $regraDistribuicao;
        }

        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()->getUser();

        if (!$usuario->getColaborador()) {
            foreach ($tarefaDto->getSetorResponsavel()->getLotacoes() as $lotacao) {
                if ($lotacao->getDistribuidor() &&
                    !$this->afastamentoRepository->findAfastamento(
                        $lotacao->getColaborador()->getId(),
                        $tarefaDto->getDataHoraFinalPrazo()
                    )
                ) {
                    $regraDistribuicao = self::REGRA_DISTRIBUICAO_APENAS_DISTRIBUIDORES;

                    return $regraDistribuicao;
                }
            }

            foreach ($tarefaDto->getSetorResponsavel()->getLotacoes() as $lotacao) {
                if (!$this->afastamentoRepository->findAfastamento(
                    $lotacao->getColaborador()->getId(),
                    $tarefaDto->getDataHoraFinalPrazo()
                )
                ) {
                    $regraDistribuicao = self::REGRA_DISTRIBUICAO_INDIFERENTE;

                    return $regraDistribuicao;
                }
            }
            throw new \RuntimeException('Não há usuário apto a receber tarefa neste setor!');
        }

        if ($tarefaDto->getSetorResponsavel()->getApenasDistribuidor()) {
            $temDistribuidor = false;

            foreach ($tarefaDto->getSetorResponsavel()->getLotacoes() as $lotacao) {
                if ($lotacao->getDistribuidor() &&
                    !$this->afastamentoRepository->findAfastamento(
                        $lotacao->getColaborador()->getId(),
                        $tarefaDto->getDataHoraFinalPrazo()
                    )
                ) {
                    $temDistribuidor = true;
                }
            }

            $estaLotado = false;

            /** @var Lotacao $lotacao */
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getId() === $tarefaDto->getSetorResponsavel()->getId()) {
                    $estaLotado = true;
                }
            }

            if ($temDistribuidor && !$estaLotado) {
                $regraDistribuicao = self::REGRA_DISTRIBUICAO_APENAS_DISTRIBUIDORES;
            }

            if ($temDistribuidor && $estaLotado &&
                $tarefaEntity->getSetorResponsavel() &&
                ($tarefaEntity->getSetorResponsavel()->getId() === $tarefaDto->getSetorResponsavel()->getId())) {
                $regraDistribuicao = self::REGRA_DISTRIBUICAO_EXCLUI_DISTRIBUIDORES;
            }
        }

        return $regraDistribuicao;
    }

    /**
     * @param $expressao
     * @return array
     */
    private function processaDigitosDistribuicao($expressao): array
    {
        $digitos = [];
        if (!$expressao) {
            return $digitos;
        }
        $intervalos = explode(',', $expressao);
        foreach ($intervalos as $intervalo) {
            $inicioFim = explode('-', $intervalo);
            if (count($inicioFim) > 1) {
                $max = max($inicioFim);
                for ($j = min($inicioFim); $j <= $max; ++$j) {
                    $digitos[] = (int) $j;
                }
            } else {
                $digitos[] = (int) $inicioFim[0];
            }
        }

        return $digitos;
    }

    /**
     * @param Usuario $usuarioResponsavel
     * @return array
     */
    public function obterDadosGraficoTarefas(Usuario $usuarioResponsavel): array
    {
        $quantidadeSemanas = 4;
        $quantidades = [];
        for ($i = $quantidadeSemanas; $i > 0; --$i) {
            $sub1 = $i * -1;
            $sub2 = ($i - 1) * -1;
            $dataInicial = date('Y-m-d', strtotime("sunday {$sub1} week"));
            $dataFinal = date('Y-m-d', strtotime("saturday {$sub2} week"));

            $quantidade = $this->repository->findCountByUserIdAndDate(
                $usuarioResponsavel->getId(),
                $dataInicial,
                $dataFinal
            );

            $periodo = date('d/m', strtotime($dataFinal));

            $quantidades[] = [
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'periodo' => $periodo,
                'quantidade' => $quantidade,
            ];
        }

        return $quantidades;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function beforeCreate(RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        parent::beforeCreate($dto, $entity, $transactionId);

        // Verifica se há contexto de tarefa origem no parâmetro context
        if (isset($_REQUEST['context'])) {
            $context = json_decode($_REQUEST['context'], true);
            
            if (is_array($context) && isset($context['tarefaOrigemId']) && $context['tarefaOrigemId']) {
                $tarefaOrigemId = (int) $context['tarefaOrigemId'];
                $tarefaOrigem = $this->getRepository()->find($tarefaOrigemId);
                
                if ($tarefaOrigem) {
                    $dto->setTarefaOrigem($tarefaOrigem);
                }
            }
        }
    }

    /**
     * @param Usuario $usuarioResponsavel
     * @param int $periodo
     * @return array
     */
    public function obterDadosGraficoTarefasMeses(Usuario $usuarioResponsavel, $periodo): array
    {
        $quantidadeMeses = $periodo -1;
        $quantidades = [];
        for ($i = $quantidadeMeses; $i >= 0; --$i) {
            $sub1 = $i * -1;
            $dataInicial = date('Y-m-d 00:00:00', strtotime("first day of {$sub1} month"));
            $dataFinal = date('Y-m-d 23:59:59', strtotime("last day of {$sub1} month"));

            $quantidade = $this->repository->findCountByUserIdAndDate(
                $usuarioResponsavel->getId(),
                $dataInicial,
                $dataFinal
            );

            $formatter = new IntlDateFormatter(
                'pt_BR',
                IntlDateFormatter::FULL,
                IntlDateFormatter::NONE,
                'America/Sao_Paulo',
                IntlDateFormatter::GREGORIAN,
                'MMM'
            );

            $periodo = $formatter->format(strtotime("first day of {$sub1} month"));

            $quantidades[] = [
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'periodo' => $periodo,
                'quantidade' => $quantidade,
            ];
        }

        return $quantidades;
    }
}

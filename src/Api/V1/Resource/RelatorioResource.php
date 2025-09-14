<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/RelatorioResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relatorio as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\RelatorioRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RelatorioResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository()                                                                                                                          : Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null) : array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null)                                                                                  : ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null)                                                 : ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null)                                                        : EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null)                                               : EntityInterface
 * @method Entity      delete(int $id, string $transactionId)                                                                                                   : EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null)                                                        : EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class RelatorioResource extends RestResource
{
    private TipoRelatorioResource $tipoRelatorioResource;

    private TokenStorageInterface $tokenStorage;

    /**
     * RelatorioResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
        TipoRelatorioResource $tipoRelatorioResource
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Relatorio::class);
        $this->tokenStorage = $tokenStorage;
        $this->tipoRelatorioResource = $tipoRelatorioResource;
    }

    /**
     * @param $transactionId
     *
     * @return Entity
     *
     * @throws Exception
     */
    public function gerarRelatorioMinhasTarefas($transactionId, $tarefas): Entity
    {
        if (empty($tarefas->idTarefasSelecionadas)) {
            $nomeRelat = ['nome' => 'TAREFAS EM ABERTO PARA UM USUÁRIO ATUALMENTE (DETALHADO)'];
        }
        else {
            $nomeRelat = ['nome' => 'TAREFAS SELECIONADAS PARA UM USUÁRIO ATUALMENTE (DETALHADO)'];
        }

        $tipoRelatorio = $this->tipoRelatorioResource->findOneBy($nomeRelat);
        $usuario = $this->tokenStorage->getToken()->getUser();
        $params['usuario'] = [
            'name' => 'usuario',
            'value' => $usuario->getId(),
            'type' => 'entity',
            'class' => Usuario::class,
            'getter' => 'getId',
        ];
        if (!empty($tarefas->idTarefasSelecionadas)){
            $params['tarefas'] = [
                'name' => 'tarefas',
                'value' => $tarefas->idTarefasSelecionadas,
                'type' => 'Connection::PARAM_INT_ARRAY',
        ];
        };
        $relatorioDTO = new Relatorio();
        $relatorioDTO->setTipoRelatorio($tipoRelatorio);
        $relatorioDTO->setFormato('xlsx');
        $relatorioDTO->setParametros(json_encode($params));

        return $this->create($relatorioDTO, $transactionId);
    }
}

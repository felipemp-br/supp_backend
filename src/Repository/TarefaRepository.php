<?php

declare(strict_types=1);
/**
 * /src/Repository/TarefaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use function count;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class TarefaRepository.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br> *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, ?array $populate = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class TarefaRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    public function hasAbertaByUsuarioResponsavelId(int $usuarioId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
            INNER JOIN t.usuarioResponsavel u 
            WHERE u.id = :usuarioId
            AND t.dataHoraConclusaoPrazo IS NULL'
        );
        $query->setParameter('usuarioId', $usuarioId);
        $query->setMaxResults(1);

        return (bool) count($query->getArrayResult());
    }

    public function hasAbertaByFolderId(int $folderId): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
            INNER JOIN t.folder f 
            WHERE f.id = :folderId
            AND t.dataHoraConclusaoPrazo IS NULL'
        );
        $query->setParameter('folderId', $folderId);
        $query->setMaxResults(1);

        return (bool) count($query->getArrayResult());
    }

    /**
     * @param $processo_id
     *
     * @return mixed[]
     */
    public function findAclUsuarioResponsavel($processo_id)
    {
        // TODO Criar pesquisa sem utilizar hql
        $sql = "select * from acl_security_identities where id in (select security_identity_id from acl_entries where mask != 0 and object_identity_id = (select id from acl_object_identities where class_id = (select id from acl_classes where class_type = 'SuppCore\AdministrativoBackend\Entity\Processo') and object_identifier = '".$processo_id."'))";
        $em = $this->getEntityManager();
        $conn = $em->getConnection();

        return $conn->fetchAllAssociative($sql);
    }

    /**
     * @param $usuarios
     * @param $processo
     * @param $setor
     *
     * @return Usuario|bool
     */
    public function findPreferenciaAbsoluta($usuarios, $processo, $setor)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t, u 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
            INNER JOIN t.usuarioResponsavel u 
            INNER JOIN t.processo p 
            INNER JOIN t.setorResponsavel s
            WHERE p.id = :processo
            AND s.id = :setor
            AND t.dataHoraConclusaoPrazo IS NULL
            AND u.id IN (:usuarios)
            ORDER BY t.criadoEm DESC'
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('setor', $setor);
        $query->setParameter('usuarios', $usuarios);
        $query->setMaxResults(1);
        /** @var Tarefa[] $result */
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0]->getUsuarioResponsavel();
        }

        return false;
    }

    /**
     * @param $usuario
     * @param $setor
     * @param $apenasDistribuicoesAutomaticas
     * @param $prazoEqualizacao
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findQuantidadeDistribuicoes($usuario, $setor, $apenasDistribuicoesAutomaticas, $prazoEqualizacao)
    {
        $fim = new DateTime();
        $inicio = clone $fim;
        $inicio->modify('-'.$prazoEqualizacao.' days');

        $em = $this->getEntityManager();

        $sqlApenasAutomaticas = '';

        if ($apenasDistribuicoesAutomaticas) {
            $sqlApenasAutomaticas = 'd.distribuicaoAutomatica = true AND';
        }

        $query = $em->createQuery(
            '
            SELECT COUNT (d) 
            FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d
            INNER JOIN d.setorPosterior s 
            INNER JOIN d.usuarioPosterior u 
            WHERE u.id = :usuario AND 
            s.id = :setor AND
            '.$sqlApenasAutomaticas.'
            d.dataHoraDistribuicao >= :inicio AND 
            d.dataHoraDistribuicao <= :fim'
        );

        $query->setParameter('usuario', $usuario);
        $query->setParameter('setor', $setor);
        $query->setParameter('inicio', $inicio);
        $query->setParameter('fim', $fim);

        return $query->getResult()[0][1];
    }

    /**
     * @param $usuario
     * @param $setor
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findQuantidadeDistribuicoesLivresHoje($usuario, $setor)
    {
        $fim = new DateTime();
        $inicio = DateTime::createFromFormat('YmdHis', $fim->format('Ymd').'000000');

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT COUNT (d) 
                FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d
                INNER JOIN d.setorPosterior s 
                INNER JOIN d.usuarioPosterior u 
                WHERE d.distribuicaoAutomatica = true AND
                d.livreBalanceamento = true AND
                u.id = :usuario AND 
                s.id = :setor AND
                d.dataHoraDistribuicao >= :inicio AND 
                d.dataHoraDistribuicao <= :fim'
        );

        $query->setParameter('usuario', $usuario);
        $query->setParameter('setor', $setor);
        $query->setParameter('inicio', $inicio);
        $query->setParameter('fim', $fim);

        return $query->getResult()[0][1];
    }

    /**
     * @param $usuarios
     * @param $processo
     *
     * @return Usuario|bool
     */
    public function findPreferencia($usuarios, $processo)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t, u 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
            LEFT JOIN t.usuarioResponsavel u
            LEFT JOIN t.processo p 
            WHERE p.id = :processo
            AND t.dataHoraConclusaoPrazo IS NOT NULL
            AND u.id IN (:usuarios)
            ORDER BY t.criadoEm DESC'
        );
        $query->setParameter('processo', $processo);
        $query->setParameter('usuarios', $usuarios);
        $query->setMaxResults(1);
        /** @var Tarefa[] $result */
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0]->getUsuarioResponsavel();
        }

        return false;
    }

    /**
     * @param $processoId
     *
     * @return mixed
     */
    public function findAbertaByProcessoId($processoId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
            INNER JOIN t.processo p 
            WHERE p.id = :processoId
            AND t.dataHoraConclusaoPrazo IS NULL'
        );
        $query->setParameter('processoId', $processoId);

        return $query->getResult();
    }

    /**
     * @return bool|Tarefa[]
     */
    public function findTarefaSemDistribuicao(int $batch)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t
                    FROM SuppCore\AdministrativoBackend\Entity\Tarefa t
                    WHERE t.id > 36511549 
                    AND NOT EXISTS (
                      SELECT d FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d
                      INNER JOIN d.tarefa td
                      WHERE td.id = t.id
                    ) ORDER BY t.id DESC'
        );

        $query->setMaxResults($batch);

        /** @var Tarefa[] $result */
        $result = $query->getResult();

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Retorna as tarefas em aberto do usuário.
     *
     * @return Tarefa[]
     */
    public function findTarefasAbertasUsuario(Usuario $usuario): array
    {
        $qb = $this->createQueryBuilder('t');

        $qb->join('t.usuarioResponsavel', 'u')
            ->join('t.especieTarefa', 'et')
            ->andWhere($qb->expr()->eq('u.id', ':usuario'))
            ->andWhere($qb->expr()->eq('et.evento', ':evento'))
            ->andWhere($qb->expr()->isNull('t.dataHoraConclusaoPrazo'))
            ->setParameter('usuario', $usuario->getId())
            ->setParameter('evento', false);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $userName
     * @param $idGeneroTarefa
     *
     * @return mixed
     */
    public function findByUsernameAndIdGeneroTarefa($userName, $idGeneroTarefa): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT t
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t
            INNER JOIN t.folder f
            INNER JOIN t.especieTarefa et
            INNER JOIN et.generoTarefa gt WITH gt.id = :idGeneroTarefa
            INNER JOIN t.usuarioResponsavel u WITH u.username = :username
            WHERE f.modalidadeFolder = true
        "
        );
        $query->setParameter('username', $userName);
        $query->setParameter('idGeneroTarefa', $idGeneroTarefa);

        return $query->getResult();
    }

    /**
     * Retorna a ultima tarefa finalizada do processo para realizar o saneamento via command.
     *
     * @param $processoId
     */
    public function findTarefasFechadasLimboByProcesso($processoId): mixed
    {
        $qb = $this->createQueryBuilder('t');
        $qb->join('t.processo', 'p')
            ->andWhere($qb->expr()->eq('p.id', ':processoId'))
            ->setParameter('processoId', $processoId)
            ->orderBy('t.dataHoraConclusaoPrazo', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int    $idUsuario
     * @param string $dataInicial
     * @param string $dataFinal
     *
     * @return int
     */
    public function findCountByUserIdAndDate(int $idUsuario, string $dataInicial, string $dataFinal): int
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            SELECT COUNT(t) 
            FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d 
            INNER JOIN d.usuarioPosterior u
            INNER JOIN d.tarefa t
            WHERE u.id = :idUsuario
              AND d.dataHoraDistribuicao BETWEEN :dataInicial AND :dataFinal'
        );
        $query->setParameter('idUsuario', $idUsuario);
        $query->setParameter('dataInicial', $dataInicial);
        $query->setParameter('dataFinal', $dataFinal);
        $result = $query->getResult();

        return (int) $result[0][1];
    }


    /**
     * @param $documentoAvulsoId
     *
     * @return mixed
     */
    public function findTarefaByDocumentoAvulso(int $documentoAvulsoId): bool|Entity
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t 
            FROM SuppCore\AdministrativoBackend\Entity\Tarefa t
            INNER JOIN t.documentosAvulsos da WITH da.id = :documentoAvulsoId'
        );
        $query->setParameter('documentoAvulsoId', $documentoAvulsoId);
        return count($query->getResult()) > 0 ? $query->getResult()[0] : false;
    }
}

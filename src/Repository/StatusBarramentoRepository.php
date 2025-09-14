<?php

declare(strict_types=1);
/**
 * /src/Repository/StatusBarramentoRepository.php.
 *
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as Entity;

/**
 * Class StatusBarramentoRepository.
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, string $lockMode = null, string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class StatusBarramentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @return array
     */
    public function findPendentesBarramentoComErro(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT vt
            FROM SuppCore\AdministrativoBackend\Entity\StatusBarramento vt
            INNER JOIN vt.tramitacao t
            INNER JOIN t.processo p
            INNER JOIN p.origemDados od
            WHERE od.fonteDados = 'BARRAMENTO_PEN'
            AND vt.codSituacaoTramitacao = false"
        );

        $retorno = [];
        /** @var StatusBarramento $result */
        foreach ($query->getResult()  as $result) {
            $retorno[] = $result->getTramitacao();
        }

        return $retorno;
    }

    /**
     * @return array
     */
    public function findUltimoTramite($processo): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT vt
            FROM SuppCore\AdministrativoBackend\Entity\StatusBarramento vt
            WHERE 
                vt.idtComponenteDigital IS NULL 
                and vt.idt != 0 
                and vt.codSituacaoTramitacao > 3
                and vt.processo = :processo
            order by vt.id desc "
        );
        $query->setParameter('processo', $processo);

        if (count($query->getResult()) > 0) {
            return $query->getResult()[0];
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function findUltimoTramiteEnvio($processo): mixed
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "
            SELECT vt
            FROM SuppCore\AdministrativoBackend\Entity\StatusBarramento vt
            WHERE 
                vt.idtComponenteDigital IS NOT NULL 
                and vt.processo = :processo
            order by vt.id desc "
        );
        $query->setParameter('processo', $processo);

        if (count($query->getResult()) > 0) {
            return $query->getResult()[0];
        } else {
            return false;
        }
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Repository/AfastamentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use function count;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Afastamento as Entity;

/**
 * Class AfastamentoRepository.
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
class AfastamentoRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * @param $colaborador
     * @param DateTime|null $dataFinalPrazo
     *
     * @return Entity|bool
     *
     * @throws Exception
     */
    public function findAfastamento($colaborador, ?DateTime $dataFinalPrazo = null)
    {
        // usuário está afastado se hoje estiver entre a data de inicio e fim de bloqueio
        // caso fornecida a data de final do prazo, o usuário está afastado caso a data de
        // final de prazo estiver entre o final do bloqueio e o final do efetivo afastamento
        $hoje = new DateTime();

        // temos que zerar as horas e minutos, pois o afastamento é em dias
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Afastamento a
            INNER JOIN a.colaborador c
            WHERE c.id = :colaborador 
            AND a.dataInicioBloqueio <= :dia AND a.dataFimBloqueio >= :dia'
        );
        $query->setParameter('dia', DateTime::createFromFormat('YmdHis', $hoje->format('Ymd').'000000'));
        $query->setParameter('colaborador', $colaborador);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        }

        if ($dataFinalPrazo) {
            $query = $em->createQuery(
                'SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Afastamento a
            INNER JOIN a.colaborador c
            WHERE c.id = :colaborador
            AND a.dataInicio <= :hoje AND a.dataFim >= :hoje 
            AND a.dataFimBloqueio <= :dia AND a.dataFim >= :dia'
            );
            $query->setParameter('hoje', DateTime::createFromFormat('YmdHis', $hoje->format('Ymd').'000000'));
            $query->setParameter('dia', DateTime::createFromFormat('YmdHis', $dataFinalPrazo->format('Ymd').'000000'));
            $query->setParameter('colaborador', $colaborador);
            $query->setMaxResults(1);
            $result = $query->getResult();
            if (count($result) > 0) {
                return $result[0];
            }
        }

        return false;
    }

    /**
     * @param $colaborador
     * @param $prazo
     *
     * @return int
     *
     * @throws Exception
     */
    public function findDiasAfastamento($colaborador, $prazo): int
    {
        $totalDiasAfastado = 0;

        $em = $this->getEntityManager();

        $agora = new DateTime();
        $dataFim = DateTime::createFromFormat('YmdHis', $agora->format('Ymd').'000000');
        $dataFim->modify('-1 days');
        $passado = $agora->modify('-'.$prazo.' days');
        $dataInicio = DateTime::createFromFormat('YmdHis', $passado->format('Ymd').'000000');

        // pega todos os afastamentos de interesse
        $query = $em->createQuery(
            '
            SELECT a 
            FROM SuppCore\AdministrativoBackend\Entity\Afastamento a
            INNER JOIN a.colaborador c
            WHERE c.id = :colaborador AND
            ((a.dataInicioBloqueio >= :dataInicio 
            AND a.dataInicioBloqueio <= :dataFim) 
            OR (a.dataFimBloqueio >= :dataInicio 
            AND a.dataFimBloqueio <= :dataFim)
            OR 
            (a.dataInicioBloqueio < :dataInicio AND a.dataFimBloqueio > :dataFim))'
        );
        $query->setParameter('dataInicio', $dataInicio);
        $query->setParameter('dataFim', $dataFim);
        $query->setParameter('colaborador', $colaborador);

        // itera sobre os dias e testa se há afastamento para ele
        $diaAfastado = $dataInicio;

        for ($i = 1; $i <= $prazo; ++$i) {
            /** @var Entity $afastamento */
            foreach ($query->getResult() as $afastamento) {
                if ($diaAfastado >= $afastamento->getDataInicioBloqueio() &&
                    $diaAfastado <= $afastamento->getDataFimBloqueio()) {
                    ++$totalDiasAfastado;
                    $diaAfastado->modify('+1 day');
                    continue 2;
                }
            }
            $diaAfastado->modify('+1 day');
        }

        return $totalDiasAfastado;
    }
}

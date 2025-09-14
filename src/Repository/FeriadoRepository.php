<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Repository/FeriadoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use DateInterval;
use DateTime;
use SuppCore\AdministrativoBackend\Entity\Feriado;
use SuppCore\AdministrativoBackend\Entity\Feriado as Entity;
use SuppCore\AdministrativoBackend\Entity\Municipio;

/**
 * Class FeriadoRepository.
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
class FeriadoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param DateTime       $data
     * @param Municipio|null $municipio
     *
     * @return Entity|null
     * @noinspection DuplicatedCode
     */
    public function findByDiaMunicipio(DateTime $data, Municipio $municipio = null): ?Feriado
    {
        $em = $this->getEntityManager();

        if (null === $municipio) {
            $query = $em->createQuery(
                'SELECT f
                FROM SuppCore\AdministrativoBackend\Entity\Feriado f
                WHERE f.dataFeriado >= :dataInicio 
                AND f.dataFeriado <= :dataFim 
                AND f.municipio IS NULL 
                AND f.estado IS NULL'
            );
        } else {
            $query = $em->createQuery(
                'SELECT f
                FROM SuppCore\AdministrativoBackend\Entity\Feriado f
                WHERE f.dataFeriado >= :dataInicio AND f.dataFeriado <= :dataFim AND 
                      ((f.municipio = :municipio) OR 
                       (f.municipio IS NULL AND f.estado = :estado) OR 
                       (f.municipio IS NULL AND f.estado IS NULL))'
            );
            $query->setParameter('municipio', $municipio->getId());
            $query->setParameter('estado', $municipio->getEstado()->getId());
        }

        $dataInicio = DateTime::createFromFormat('YmdHis', $data->format('Ymd').'000000');
        $dataFim = DateTime::createFromFormat('YmdHis', $data->format('Ymd').'235959');
        $query->setParameter('dataInicio', $dataInicio);
        $query->setParameter('dataFim', $dataFim);

        $result = $query->getResult();

        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    /**
     * @param DateTime       $data
     * @param Municipio|null $municipio
     *
     * @return bool
     */
    public function isDiaUtil(DateTime $data, Municipio $municipio = null): bool
    {
        // sabados e domingos. exige PHP >= 5.1
        if ($data->format('N') >= 6) {
            return false;
        } else {
            // feriados
            $feriado = $this->findByDiaMunicipio($data, $municipio);
            if ($feriado) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param DateTime $dataDesejada
     * @param null     $municipioDevedor
     *
     * @return DateTime
     */
    public function getDataVencimentoUtil(DateTime $dataDesejada, $municipioDevedor = null): DateTime
    {
        $dataVencimentoUtil = clone $dataDesejada;
        while (!$this->isDiaUtil($dataVencimentoUtil, $municipioDevedor)) {
            $dataVencimentoUtil->sub(new DateInterval('P1D'));
        }

        return $dataVencimentoUtil;
    }

    /**
     * @param $numeroDias
     * @param DateTime|null $dataInicio
     * @param null          $municipioDevedor
     *
     * @return DateTime
     * @noinspection PhpUnused
     */
    public function getProximoDiaUtil($numeroDias, DateTime $dataInicio = null, $municipioDevedor = null): DateTime
    {
        $dataUtil = $dataInicio ? clone $dataInicio : new DateTime();
        while ($numeroDias) {
            $dataUtil->add(new DateInterval('P1D'));
            if ($this->isDiaUtil($dataUtil, $municipioDevedor)) {
                --$numeroDias;
            }
        }

        return $dataUtil;
    }

    /**
     * @param DateTime $mesVigente
     * @param $diasUteis
     * @return DateTime
     */
    public function calculaDiaDoMesEmDiasUteis(DateTime $mesVigente, $diasUteis): DateTime
    {
        $data = (clone $mesVigente)->modify('first day of this month');

        $i = 0;
        while ($i < $diasUteis) {
            if ($this->isDiaUtil($data)) {
                ++$i;
            }
            $data->add(new DateInterval('P1D'));
        }

        return $data;
    }
}

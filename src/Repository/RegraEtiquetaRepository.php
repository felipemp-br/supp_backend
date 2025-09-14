<?php

declare(strict_types=1);
/**
 * /src/Repository/RegraEtiquetaRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta as Entity;

/**
 * Class RegraEtiquetaRepository.
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
class RegraEtiquetaRepository extends BaseRepository
{
    protected static string $entityName = Entity::class;

    /**
     * Retorna as regras aplicaveis ao momento disparo conforme contextos impactados.
     *
     * @param string              $siglaMomentoDisparoRegraEtiqueta
     * @param array|int|bool|null $usuario
     * @param array|int|bool|null $setor
     * @param array|int|bool|null $unidade
     * @param array|int|bool|null $modalidadeOrgaoCentral
     * @param int|null            $limit
     * @param int|null            $offset
     *
     * @return Paginator
     */
    public function findRegrasAplicaveisMomentoDisparoRegraEtiqueta(
        string $siglaMomentoDisparoRegraEtiqueta,
        array|int|bool|null $usuario = null,
        array|int|bool|null $setor = null,
        array|int|bool|null $unidade = null,
        array|int|bool|null $modalidadeOrgaoCentral = null,
        ?int $limit = 100,
        ?int $offset = null,
    ): Paginator {
        $limit ??= 100;

        $qb = $this->createQueryBuilder('r');
        $qb
            ->select('r')
            ->join('r.etiqueta', 'e')
            ->join('r.momentoDisparoRegraEtiqueta', 'mdre')
            ->join('e.vinculacoesEtiquetas', 've')
            ->andWhere($qb->expr()->eq('e.ativo', ':ativo'))
            ->andWhere($qb->expr()->eq('mdre.sigla', ':sigla'))
            ->setParameter('ativo', true)
            ->setParameter('sigla', $siglaMomentoDisparoRegraEtiqueta)
            ->setMaxResults($limit * ($offset + 1))
            ->setFirstResult($offset)
            ->orderBy('r.id', 'ASC');

        $orX = $qb->expr()->orX();

        if ($usuario) {
            if (is_bool($usuario)) {
                $expr = $qb->expr()->isNotNull('ve.usuario');
            } else {
                $qb
                    ->leftJoin('ve.usuario', 'us')
                    ->setParameter('usuarioIds', is_array($usuario) ? $usuario : [$usuario]);

                $expr = $qb->expr()->in('us.id', ':usuarioIds');
            }
            $orX->add($expr);
        }

        if ($setor) {
            if (is_bool($setor)) {
                $expr = $qb->expr()->isNotNull('ve.setor');
            } else {
                $qb
                    ->leftJoin('ve.setor', 'se')
                    ->setParameter('setorIds', is_array($setor) ? $setor : [$setor]);

                $expr = $qb->expr()->in('se.id', ':setorIds');
            }
            $orX->add($expr);
        }

        if ($unidade) {
            if (is_bool($unidade)) {
                $expr = $qb->expr()->isNotNull('ve.unidade');
            } else {
                $qb
                    ->leftJoin('ve.unidade', 'un')
                    ->setParameter('unidadeIds', is_array($unidade) ? $unidade : [$unidade]);

                $expr = $qb->expr()->in('un.id', ':unidadeIds');
            }
            $orX->add($expr);
        }

        if ($modalidadeOrgaoCentral) {
            if (is_bool($modalidadeOrgaoCentral)) {
                $expr = $qb->expr()->isNotNull('ve.modalidadeOrgaoCentral');
            } else {
                $qb
                    ->leftJoin('ve.modalidadeOrgaoCentral', 'moc')
                    ->setParameter(
                        'modalidadeOrgaoCentralIds',
                        is_array($modalidadeOrgaoCentral) ? $modalidadeOrgaoCentral : [$modalidadeOrgaoCentral]
                    );

                $expr = $qb->expr()->in('moc.id', ':modalidadeOrgaoCentralIds');
            }
            $orX->add($expr);
        }

        $qb->andWhere($orX);

        return new Paginator($qb);
    }
}

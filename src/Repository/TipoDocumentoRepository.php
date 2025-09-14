<?php

declare(strict_types=1);
/**
 * /src/Repository/TipoDocumentoRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\TipoDocumento as Entity;

/**
 * Class TipoDocumentoRepository.
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
class TipoDocumentoRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * @param string $nome
     * @param string $especie
     *
     * @return Entity|bool
     */
    public function findByNomeAndEspecie(string $nome, string $especie)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT t
            FROM SuppCore\AdministrativoBackend\Entity\TipoDocumento t
            INNER JOIN t.especieDocumento e WITH e.nome = :especie
            WHERE t.nome = :nome'
        );
        $query->setParameter('nome', $nome);
        $query->setParameter('especie', $especie);
        $result = $query->getResult();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param string $nomeTipoDocumento
     * @return Entity
     */
    public function findTipoDocumentoFromDocumentoClassificado(string $nomeTipoDocumento): Entity
    {
        $tipoDocumento = $this
            ->findOneBy(
                ['nome' => $nomeTipoDocumento],
            );

        if (!$tipoDocumento) {
            $tipoDocumento = $this
                ->findByNomeAndEspecie(
                    'OUTROS',
                    'ADMINISTRATIVO'
                );
        }

        return $tipoDocumento;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Repository/DocumentoIdentificadorRepository.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as Entity;

/**
 * Class DocumentoIdentificadorRepository.
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
class DocumentoIdentificadorRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;

    /**
     * Faz uma busca por documentos relacionados a Pessoa.
     *
     * @param int $pessoa
     * @param string $fonteDados
     * @return Entity[]
     */
    public function findByPessoaAndFonteDados($pessoa, $fonteDados): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT d, m
            FROM SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador d
            LEFT JOIN d.modalidadeDocumentoIdentificador m
            INNER JOIN d.pessoa p WITH p.id = :pessoa
            INNER JOIN d.origemDados o WITH o.fonteDados = :fonteDados'
        );
        $query->setParameter('pessoa', $pessoa);
        $query->setParameter('fonteDados', $fonteDados);
        return $query->getResult();
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/TipoDocumentoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento as Entity;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\StringService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TipoDocumentoResource.
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
class TipoDocumentoResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * TipoDocumentoResource constructor.
     */
    public function __construct(Repository $repository,
                                ValidatorInterface $validator,
                                private StringService $stringService)
    {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(TipoDocumento::class);
    }

    /**
     * @param string|null $filename
     * @param string $extesao
     * @return Entity|Proxy|null
     * @throws ORMException
     */
    public function retornaTipoDocumentoByFilename(string|null $filename, string $extesao): Entity| null | Proxy
    {
        if (!$filename) {
            return null;
        }

        if ($this->redisClient->exists('tipo_doc_all')) {
            $tipoDocumentos = unserialize(json_decode($this->redisClient->get('tipo_doc_all')));
        } else {
            $tipoDocumentos = $this->getRepository()->findAll();
            $this->redisClient->set('tipo_doc_all', json_encode(serialize($tipoDocumentos)));
            $this->redisClient->expire('tipo_doc_all', 60 * 24 * 60 * 60);
        }

        $filename = $this->stringService::utf82short(
            str_replace(
                '.'.strtoupper($extesao),
                '',
                strtoupper($filename)
            )
        );

        // Verificação por comparação de textos completos
        $percentualMinimo = 70;
        $percentualHitMinimo = 85;
        $tipoDocumentoMaisProximo = null;
        $percentualTipoDocumentoMaisProximo = 0;
        foreach ($tipoDocumentos as $tipoDocumento) {
            $percentualCalculado = 0;

            similar_text(
                StringService::removeAccents($filename),
                $this->stringService::utf82short(strtoupper(StringService::removeAccents($tipoDocumento->getNome()))),
                $percentualCalculado
            );

            if ($percentualCalculado > $percentualTipoDocumentoMaisProximo
                && $percentualCalculado >= $percentualMinimo) {
                $percentualTipoDocumentoMaisProximo = $percentualCalculado;
                $tipoDocumentoMaisProximo = $tipoDocumento;
            }
        }

        if ($tipoDocumentoMaisProximo) {
            return $this->getReference($tipoDocumentoMaisProximo->getId());
        }

        // Verificação por palavras individuais
        $filenameWords = array_filter(explode(' ', StringService::removeAccents($filename)), fn($word) => mb_strlen($word) > 2);
        $hitCountMaisProximo = 0;
        foreach ($tipoDocumentos as $tipoDocumento) {
            $tipoDocumentoWords = array_filter(explode(' ',StringService::removeAccents($tipoDocumento->getNome())), fn($word) => mb_strlen($word) > 2);
            $arrWordsPcts = [];

            foreach ($filenameWords as $fileWord) {
                foreach ($tipoDocumentoWords as $docWord) {
                    $pct = 0;
                    similar_text(
                        strtoupper($docWord),
                        $this->stringService::utf82short(strtoupper($fileWord)),
                        $pct
                    );

                    if (!isset($arrWordsPcts[$fileWord]['pct'])) {
                        $arrWordsPcts[$fileWord]['pct'] = $pct;
                    }

                    if ($pct >= $arrWordsPcts[$fileWord]['pct']) {
                        $arrWordsPcts[$fileWord]['pct'] = $pct;
                    }
                }
            }
            $arrMatch = array_filter($arrWordsPcts, fn($wordData) => $wordData['pct'] >= $percentualHitMinimo);
            $hitCount = count($arrMatch);
            $percentualCalculado = empty($arrWordsPcts) ?
                0 : array_sum(array_map(fn($wordData) => $wordData['pct'], $arrWordsPcts))/count($arrWordsPcts);

            if ($percentualCalculado > $percentualTipoDocumentoMaisProximo &&
                ($percentualCalculado >= 50 && $hitCount <= 1
                    || $percentualCalculado > 40 && $hitCount > 1
                    || $percentualCalculado >= $percentualMinimo)
                    && (
                        $hitCount > $hitCountMaisProximo
                        || $hitCount === $hitCountMaisProximo && $hitCount
                    )
            ) {
                $percentualTipoDocumentoMaisProximo = $percentualCalculado;
                $hitCountMaisProximo = $hitCount;
                $tipoDocumentoMaisProximo = $tipoDocumento;
            }
        }


        if ($tipoDocumentoMaisProximo) {
            return $this->getReference($tipoDocumentoMaisProximo->getId());
        }

        return null;
    }
}

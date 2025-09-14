<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/NumeroUnicoDocumentoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento as Entity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;
use SuppCore\AdministrativoBackend\Repository\NumeroUnicoDocumentoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class NumeroUnicoDocumentoResource.
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
class NumeroUnicoDocumentoResource extends RestResource
{
    private TokenStorageInterface $tokenStorage;
    private LoggerInterface $logger;

    /**
     * NumeroUnicoDocumentoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
        LoggerInterface $logger
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(NumeroUnicoDocumento::class);
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param Setor         $setor
     * @param TipoDocumento $tipoDocumento
     *
     * @return EntityInterface|null
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function generate(Setor $setor, TipoDocumento $tipoDocumento): ?EntityInterface
    {
        /**
         *  criação do número unico do documento de maneira isolado e à prova de concorrência.
         */
        $sucesso = false;
        $tentativa = 1;

        $em = $this->getRepository()->getEntityManager();
        $conn = $em->getConnection();

        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            $usuarioId = $this->tokenStorage->getToken()->getUser()->getId();
        } else {
            $usuarioId = null;
        }

        // correcao para numeracao por unidade
        if ($setor->getUnidade()->getNumeracaoDocumentoUnidade()) {
            $setor = $setor->getUnidade();
        }

        $agora = new DateTime();

        $numeroUnicoDocumento = null;

        while (false === $sucesso) {
            if ($tentativa > 10) {
                throw new Exception('Houve erro na geração do número único de documento!');
            }

            $maxSequencia = $this->getRepository()
                ->findMaxSequencia($agora->format('Y'), $setor->getId(), $tipoDocumento->getId());

            ++$maxSequencia;

            $uuid = Uuid::uuid4()->toString();

            $numeroUnicoDocumento = [
                'uuid' => $uuid,
                'ano' => $agora->format('Y'),
                'tipo_documento_id' => $tipoDocumento->getId(),
                'setor_id' => $setor->getId(),
                'sequencia' => $maxSequencia,
                'criado_por' => $usuarioId,
                'atualizado_por' => $usuarioId,
                'criado_em' => $agora->format($conn->getDatabasePlatform()->getDateTimeFormatString()),
                'atualizado_em' => $agora->format($conn->getDatabasePlatform()->getDateTimeFormatString()),
            ];

            if ('oracle' === $conn->getDatabasePlatform()->getName() ||
                'postgresql' === $conn->getDatabasePlatform()->getName()) {
                $sequenceName = 'ad_numero_unico_documento_id_seq';
                $nextvalQuery = $conn->getDatabasePlatform()->getSequenceNextValSQL($sequenceName);
                $id = (int) $conn->fetchOne($nextvalQuery);
                $numeroUnicoDocumento['id'] = $id;
            }

            try {
                $conn->insert('ad_numero_unico_documento', $numeroUnicoDocumento);
                $numeroUnicoDocumento = $this->getRepository()->findOneBy(['uuid' => $uuid]);
                break;
            } catch (Exception $e) {
                // não faz nada
                if (3 === $tentativa) {
                    $this->logger->critical($e->getMessage());
                }
                usleep(200_000);
            }
            ++$tentativa;
        }

        return $numeroUnicoDocumento;
    }
}

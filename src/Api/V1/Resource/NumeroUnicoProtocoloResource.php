<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/NumeroUnicoProtocoloResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use function bcmod;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoProtocolo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoProtocolo as Entity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Repository\NumeroUnicoProtocoloRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class NumeroUnicoProtocoloResource.
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
class NumeroUnicoProtocoloResource extends RestResource
{
    private TokenStorageInterface $tokenStorage;
    private LoggerInterface $logger;

    /**
     * NumeroUnicoProtocoloResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
        LoggerInterface $logger
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(NumeroUnicoProtocolo::class);
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param Setor $setor
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function generate(Setor $setor): string
    {
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

        $agora = new DateTime();

        $maxSequencia = 0;

        $redisClient = $this->getRedisClient();

        while (false === $sucesso) {
            if ($tentativa > 180) {
                throw new Exception('Houve erro na geração do número único de protocolo!');
            }

            // cache
            $maxSequencia = $redisClient->get($setor->getPrefixoNUP().'_'.$agora->format('Y'));
            if (!$maxSequencia) {
                $maxSequencia = $this->getRepository()
                    ->findMaxSequencia($setor->getPrefixoNUP());
            }

            if (0 === $maxSequencia) {
                $maxSequencia = ($setor->getUnidade()->getSequenciaInicialNUP() > 0 ? $setor->getUnidade()->getSequenciaInicialNUP() : 1);
            } else {
                ++$maxSequencia;
            }

            $numeroUnicoProtocolo = [
                'uuid' => Uuid::uuid4()->toString(),
                'ano' => $agora->format('Y'),
                'prefixo_nup' => $setor->getPrefixoNUP(),
                'setor_id' => $setor->getId(),
                'sequencia' => $maxSequencia,
                'criado_por' => $usuarioId,
                'atualizado_por' => $usuarioId,
                'criado_em' => $agora->format($conn->getDatabasePlatform()->getDateTimeFormatString()),
                'atualizado_em' => $agora->format($conn->getDatabasePlatform()->getDateTimeFormatString()),
            ];

            if ('oracle' === $conn->getDatabasePlatform()->getName() ||
                'postgresql' === $conn->getDatabasePlatform()->getName()) {
                $sequenceName = 'ad_numero_unico_protocolo_id_seq';
                $nextvalQuery = $conn->getDatabasePlatform()->getSequenceNextValSQL($sequenceName);
                $id = (int) $conn->fetchOne($nextvalQuery);
                $numeroUnicoProtocolo['id'] = $id;
            }

            try {
                $conn->insert('ad_numero_unico_protocolo', $numeroUnicoProtocolo);
                $redisClient->set($setor->getPrefixoNUP().'_'.$agora->format('Y'), $maxSequencia);
                $redisClient->expire($setor->getPrefixoNUP().'_'.$agora->format('Y'), 30 * 24 * 60 * 60);
                $sucesso = true;
            } catch (Exception $e) {
                // limpa o cache
                $redisClient->del($setor->getPrefixoNUP().'_'.$agora->format('Y'));
                if (3 === $tentativa) {
                    $this->logger->critical($e->getMessage());
                }
                usleep(500_000);
            }
            ++$tentativa;
        }

        return $this->geraNumeroUnico($setor->getPrefixoNUP(), $maxSequencia, $agora->format('Y'));
    }

    /**
     * @param $prefixoNUP
     * @param $sequencia
     * @param $ano
     */
    public function calculaDigitosVerificadores($prefixoNUP, $sequencia, $ano): string
    {
        // calculo de dv1
        $digitos = str_pad($prefixoNUP, 5, '0', STR_PAD_LEFT).
            str_pad((string) $sequencia, 6, '0', STR_PAD_LEFT).
            str_pad($ano, 4, '0', STR_PAD_LEFT);

        for ($dv1 = 0, $i = 14, $peso = 2; $i >= 0; $i--, $peso++) {
            $dv1 += $digitos[$i] * $peso;
        }

        if (($dv1 = 11 - (int) bcmod((string) $dv1, '11')) >= 10) {
            $dv1 -= 10;
        }

        // calculo de dv2
        $digitos .= $dv1;
        for ($dv2 = 0, $i = 15, $peso = 2; $i >= 0; $i--, $peso++) {
            $dv2 += $digitos[$i] * $peso;
        }
        if (($dv2 = 11 - (int) bcmod((string) $dv2, (string) '11')) >= 10) {
            $dv2 -= 10;
        }

        return $dv1.$dv2;
    }

    /**
     * @param $prefixoNUP
     * @param $sequencia
     * @param $ano
     */
    public function geraNumeroUnico($prefixoNUP, $sequencia, $ano): string
    {
        return str_pad($prefixoNUP, 5, '0', STR_PAD_LEFT).
            str_pad((string) $sequencia, 6, '0', STR_PAD_LEFT).
            str_pad($ano, 4, '0', STR_PAD_LEFT).
            $this->calculaDigitosVerificadores($prefixoNUP, $sequencia, $ano);
    }
}

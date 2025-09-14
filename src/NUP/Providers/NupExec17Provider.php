<?php

declare(strict_types=1);
/**
 * /src/NUP/NumeroUnicoProtocoloProvider.php.
 */

namespace SuppCore\AdministrativoBackend\NUP\Providers;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoProtocoloResource;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\NUP\NumeroUnicoProtocoloInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class NumeroUnicoProtocoloProvider.
 */
class NupExec17Provider implements NumeroUnicoProtocoloInterface
{
    private TokenStorageInterface $tokenStorage;
    private RulesTranslate $rulesTranslate;
    private NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource;
    private LoggerInterface $logger;

    /**
     * NupExec17Provider constructor.
     *
     * @param TokenStorageInterface         $tokenStorage
     * @param RulesTranslate                $rulesTranslate
     * @param NumeroUnicoProtocoloResource  $numeroUnicoProtocoloResource
     * @param LoggerInterface               $logger
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RulesTranslate $rulesTranslate,
        NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource,
        LoggerInterface $logger
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->rulesTranslate = $rulesTranslate;
        $this->numeroUnicoProtocoloResource = $numeroUnicoProtocoloResource;
        $this->logger = $logger;
    }

    public function gerarNumeroUnicoProtocolo(ProcessoDTO $processo): string
    {
        /**
         *  criação do número unico do documento de maneira isolado e à prova de concorrência.
         */
        $sucesso = false;
        $tentativa = 1;
        $setor = $processo->getSetorAtual();

        $em = $this->numeroUnicoProtocoloResource->getRepository()->getEntityManager();
        $conn = $em->getConnection();
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            $usuarioId = $this->tokenStorage->getToken()->getUser()->getId();
        } else {
            $usuarioId = null;
        }

        $agora = new DateTime();
        $maxSequencia = 0;

        $prefixo = $setor->getPrefixoNUP();

        if (!$prefixo) {
            $prefixo = $setor->getUnidade()->getPrefixoNUP();
        }

        $redisClient = $this->numeroUnicoProtocoloResource->getRedisClient();

        while (false === $sucesso) {
            if ($tentativa > 10) {
                throw new Exception('Houve erro na geração do número único de protocolo!');
            }

            $isCache = false;

            // cache
            $maxSequencia = $redisClient->get($prefixo.'_'.$agora->format('Y'));
            if (!$maxSequencia) {
                $maxSequencia = $this->numeroUnicoProtocoloResource->getRepository()
                    ->findMaxSequencia($prefixo);
            } else {
                $isCache = true;
            }

            if (0 === $maxSequencia) {
                $maxSequencia = ($prefixo > 0 ? ($setor->getUnidade(
                )->getSequenciaInicialNUP() + 1) : 1);
            } else {
                ++$maxSequencia;
            }

            $numeroUnicoProtocolo = [
                'uuid' => Uuid::uuid4()->toString(),
                'ano' => $agora->format('Y'),
                'prefixo_nup' => $prefixo,
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
                $sucesso = true;
                $redisClient->set($prefixo.'_'.$agora->format('Y'), $maxSequencia);
                $redisClient->expire($prefixo.'_'.$agora->format('Y'), 30 * 24 * 60 * 60);
            } catch (Exception $e) {
                // não faz nada
                $redisClient->del($prefixo.'_'.$agora->format('Y'));
                if (3 === $tentativa) {
                    $this->logger->critical($e->getMessage());
                }
                if (!$isCache) {
                    usleep(200_000);
                }
            }
            ++$tentativa;
        }

        if ((ProcessoEntity::UA_PROCESSO === $processo->getUnidadeArquivistica() ||
                ProcessoEntity::UA_DOCUMENTO_AVULSO === $processo->getUnidadeArquivistica()) &&
            (ProcessoEntity::TP_NOVO === $processo->getTipoProtocolo())) {
            return $this->geraNumeroUnico($prefixo, $maxSequencia, $agora->format('Y'));
        }

        if (ProcessoEntity::UA_DOSSIE === $processo->getUnidadeArquivistica()) {
            return $processo->getUuid();
        }
    }

    /**
     * @param $prefixoNUP
     * @param $sequencia
     * @param $ano
     *
     * @return string
     */
    private function geraNumeroUnico($prefixoNUP, $sequencia, $ano): string
    {
        return str_pad($prefixoNUP, 5, '0', STR_PAD_LEFT).
            str_pad((string) $sequencia, 6, '0', STR_PAD_LEFT).
            str_pad($ano, 4, '0', STR_PAD_LEFT).
            $this->calculaDigitosVerificadores($prefixoNUP, $sequencia, $ano);
    }

    /**
     * @param $prefixoNUP
     * @param $sequencia
     * @param $ano
     *
     * @return string
     */
    private function calculaDigitosVerificadores($prefixoNUP, $sequencia, $ano): string
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
     * @param ProcessoDTO $processo
     * @param string|null $errorMessage
     *
     * @return bool
     */
    public function validarNumeroUnicoProtocolo(ProcessoDTO $processo, string &$errorMessage = null): bool
    {
        try {
            if ((ProcessoEntity::UA_PROCESSO !== $processo->getUnidadeArquivistica() &&
                    ProcessoEntity::UA_DOCUMENTO_AVULSO !== $processo->getUnidadeArquivistica()) ||
                $processo->getNupInvalido()) {
                return true;
            }

            $digitos = str_replace(['-', '.', '/', '\\', ' '], '', $processo->getNUP());
            $tamanho = (mb_strlen($digitos, 'UTF-8'));
            if (17 !== $tamanho) {
                throw new Exception($this->rulesTranslate->translate('processo', '0012'));
            }
            $agora = new DateTime();
            $ano = (int) substr($digitos, 11, 4);
            $anoAtual = (int) $agora->format('Y');
            if ($ano > $anoAtual) {
                throw new Exception($this->rulesTranslate->translate('processo', '0013'));
            }

            // pega o digito verificador informado
            $dvInformado = substr($digitos, -2);
            for ($dv1 = 0, $i = ($tamanho - 3), $peso = 2; $i >= 0; $i--, $peso++) {
                $dv1 += $digitos[$i] * $peso;
            }
            if (($dv1 = 11 - (int) bcmod((string) $dv1, '11')) >= 10) {
                $dv1 -= 10;
            }
            // calculo de dv2 esperado
            $digitos .= $dv1;
            for ($dv2 = 0, $i = ($tamanho - 2), $peso = 2; $i >= 0; $i--, $peso++) {
                $dv2 += $digitos[$i] * $peso;
            }
            if (($dv2 = 11 - (int) bcmod((string) $dv2, '11')) >= 10) {
                $dv2 -= 10;
            }
            $dvEsperado = (string) $dv1.(string) $dv2;

            if ($dvInformado !== $dvEsperado) {
                throw new Exception($this->rulesTranslate->translate('processo', '0014', [$dvEsperado]));
            }

            return true;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return false;
        }
    }

    /**
     * @param string $nup
     *
     * @return string
     */
    public function formatarNumeroUnicoProtocolo(string $nup): string
    {
        return substr($nup, 0, 5).'.'.
            substr($nup, 5, 6).'/'.
            substr($nup, 11, 4).'-'.
            substr($nup, 15, 2);
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return 'NUP DO PODER EXECUTIVO FEDERAL DE 17 DÍGITOS';
    }

    /**
     * @return string
     */
    public function getSigla(): string
    {
        return 'NUPEXEC17';
    }

    /**
     * @return DateTime
     */
    public function getDataHoraInicioVigencia(): DateTime
    {
        return DateTime::createFromFormat('YmdHis', '20030307000000');
    }

    /**
     * @return DateTime|null
     */
    public function getDataHoraFimVigencia(): ?DateTime
    {
        return null;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return 'NÚMERO ÚNICO DE PROTOCOLO DO PODER EXECUTIVO FEDERAL DE 17 DÍGITOS';
    }

    /**
     * @return int
     */
    public function getDigitos(): int
    {
        return 17;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 10;
    }
}

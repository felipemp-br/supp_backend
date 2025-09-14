<?php

declare(strict_types=1);
/**
 * /src/NUP/NupExec15Provider.php.
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

/**
 * Class NupExec15Provider.
 */
class NupExec15Provider implements NumeroUnicoProtocoloInterface
{
    private RulesTranslate $rulesTranslate;

    private NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource;

    private TokenStorageInterface $tokenStorage;

    private LoggerInterface $logger;

    /**
     * NupExec15Provider constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource,
        TokenStorageInterface $tokenStorage,
        LoggerInterface $logger
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->numeroUnicoProtocoloResource = $numeroUnicoProtocoloResource;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param ProcessoDTO $processo
     *
     * @return string
     *
     * @throws Exception
     */
    public function gerarNumeroUnicoProtocolo(ProcessoDTO $processo): string
    {
        return $processo->getNUP();
    }

    /**
     * @param ProcessoDTO $processo
     * @param string|null $errorMessage
     *
     * @return bool
     *
     * @throws Exception
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
            if (15 !== $tamanho) {
                throw new Exception($this->rulesTranslate->translate('processo', '0012'));
            }

            return true;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return false;
        }
    }

    /**
     * @param $prefixoNUP
     * @param $sequencia
     * @param $ano
     * @return string
     */
    private function geraNumeroUnico($prefixoNUP, $sequencia, $ano): string
    {
        return str_pad($prefixoNUP, 5, '0', STR_PAD_LEFT).
            str_pad((string)$sequencia, 6, '0', STR_PAD_LEFT).
            str_pad($ano, 4, '0', STR_PAD_LEFT);
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
            substr($nup, 11, 2).'-'.
            substr($nup, 13, 2);
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return 'NUP DO PODER EXECUTIVO FEDERAL DE 15 DÍGITOS';
    }

    /**
     * @return string
     */
    public function getSigla(): string
    {
        return 'NUPEXEC15';
    }

    /**
     * @return DateTime
     */
    public function getDataHoraInicioVigencia(): DateTime
    {
        return DateTime::createFromFormat('YmdHis', '19700101000000');
    }

    /**
     * @return DateTime|null
     */
    public function getDataHoraFimVigencia(): ?DateTime
    {
        return DateTime::createFromFormat('YmdHis', '20030305235959');
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return 'NÚMERO ÚNICO DE PROTOCOLO DO PODER EXECUTIVO FEDERAL DE 15 DÍGITOS';
    }

    /**
     * @return int
     */
    public function getDigitos(): int
    {
        return 15;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 20;
    }
}

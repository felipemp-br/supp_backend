<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Nome as NomeDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NomeResource;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Repository\NomeRepository;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;

/**
 * Classe responsável por sincronizar o objeto Outro Nome de Pessoa.
 *
 */
class BarramentoOutroNomeManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Array de objetos a serem persistidos.
     */
    private array $toPersistEntities = [];

    /**
     * Array de objetos a serem removidos.
     */
    private array $toRemoveEntities = [];

    /**
     * Array de objetos a serem removidos.
     */
    private NomeRepository $nomeRepository;

    private string $transactionId;

    /**
     * Array de objetos a serem removidos.
     */
    private NomeResource $nomeResource;

    /** BarramentoOutroNomeManager constructor.
     * BarramentoOutroNomeManager constructor.
     *
     * @param BarramentoLogger $logger
     * @param NomeRepository   $nomeRepository
     * @param NomeResource     $nomeResource
     */
    public function __construct(
        BarramentoLogger $logger,
        NomeRepository $nomeRepository,
        NomeResource $nomeResource
    ) {
        $this->logger = $logger;
        $this->nomeRepository = $nomeRepository;
        $this->nomeResource = $nomeResource;
    }

    /**
     * Sincroniza outros nomes do Objeto Pessoa.
     *
     * @param stdClass $interessadoTramitacao
     * @param Pessoa   $pessoa
     * @param string   $transactionId
     *
     * @return Pessoa
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function sincronizaOutroNome(stdClass $interessadoTramitacao, Pessoa $pessoa, string $transactionId): Pessoa
    {
        $this->transactionId = $transactionId;

        if (isset($interessadoTramitacao->outroNome)) {
            if (!is_array($interessadoTramitacao->outroNome)) {
                $interessadoTramitacao->outroNome = [$interessadoTramitacao->outroNome];
            }

            $nomesSupp = $this->nomeRepository->findByPessoaAndFonteDados($pessoa->getId(), $this->fonteDeDados);

            $pessoa = $this->acrescentaOutroNome($interessadoTramitacao, $nomesSupp, $pessoa);
            $pessoa = $this->removeOutroNome($interessadoTramitacao, $nomesSupp, $pessoa);
        }

        return $pessoa;
    }

    /** Acrescenta outros nomes do Objeto Pessoa.
     * @param stdClass $interessadoTramitacao
     * @param array    $nomesSupp
     * @param Pessoa   $pessoa
     *
     * @return Pessoa
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function acrescentaOutroNome(stdClass $interessadoTramitacao, array $nomesSupp, Pessoa $pessoa): Pessoa
    {
        foreach ($interessadoTramitacao->outroNome as $outroNome) {
            $existe = false;

            foreach ($nomesSupp as $nomeSupp) {
                if (mb_strtoupper($outroNome, 'UTF-8') == $nomeSupp->getValor()) {
                    $existe = true;
                }
            }

            if (false == $existe) {
                //Criar DTO
                $nomeNovoDto = new NomeDto();
                $nomeNovoDto->setValor($this->upperUtf8($outroNome));

                $nomeNovoDto->setOrigemDados($this->origemDadosFactory());
                $nomeNovoDto->setPessoa($pessoa);

                $this->nomeResource->create($nomeNovoDto, $this->transactionId);
            }
        }

        return $pessoa;
    }

    /**
     * Remove outros nomes do Objeto Pessoa.
     *
     * @param stdClass $interessadoTramitacao
     * @param array $nomesSupp
     * @param Pessoa $pessoa
     *
     * @return Pessoa
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function removeOutroNome(stdClass $interessadoTramitacao, array $nomesSupp, Pessoa $pessoa): Pessoa
    {
        foreach ($nomesSupp as $nomeSupp) {
            $existe = false;

            foreach ($interessadoTramitacao->outroNome as $outroNome) {
                if (mb_strtoupper($outroNome, 'UTF-8') == $nomeSupp->getValor()) {
                    $existe = true;
                }
            }

            if (false == $existe) {
                $this->nomeResource->delete($nomeSupp->getId(), $this->transactionId);
            }
        }

        return $pessoa;
    }
}

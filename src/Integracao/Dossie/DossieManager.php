<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * src/Integracao/Dossie/DossieManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;

/**
 * Class DossieManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DossieManager
{
    /**
     * @var array|AbstractGeradorDossie[]
     */
    private array $geradoresDossies;
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->geradoresDossies = [];
        $this->logger = $logger;
    }

    /**
     * @param AbstractGeradorDossie $geradorDossie
     * @noinspection PhpUnused
     */
    public function addGeradorDossie(AbstractGeradorDossie $geradorDossie): void
    {
        $this->geradoresDossies[] = $geradorDossie;
    }

    /**
     * @param TarefaEntity|TarefaDTO           $tarefa
     * @param InteressadoEntity|InteressadoDTO $interessado
     *
     * @return AbstractGeradorDossie[]
     */
    public function getGeradoresDossies(TarefaEntity | TarefaDTO $tarefa, InteressadoEntity | InteressadoDTO $interessado): array
    {
        $geradoresSuportados = [];
        foreach ($this->getGeradoresDossiesRegistrados() as $gerador) {
            if ($gerador->getTipoDossie() && $gerador->supports($tarefa, $interessado)) {
                $geradoresSuportados[$gerador->getGrupo()][$gerador->getOrder()] = $gerador;
            }
        }

        $geradoresEscolhidos = [];
        foreach ($geradoresSuportados as $geradoresSuportadosGrupo) {
            ksort($geradoresSuportadosGrupo, SORT_NUMERIC);
            $geradoresEscolhidos[] = reset($geradoresSuportadosGrupo);
        }

        return $geradoresEscolhidos;
    }

    /**
     * @return AbstractGeradorDossie[]
     */
    public function getGeradoresDossiesRegistrados(): array
    {
        return $this->geradoresDossies;
    }

    /**
     * @param TipoDossieEntity $tipoDossie
     *
     * @return AbstractGeradorDossie|null
     */
    public function getGeradorDossiePorTipoDossie(TipoDossieEntity $tipoDossie): ?AbstractGeradorDossie
    {
        foreach ($this->getGeradoresDossiesRegistrados() as $geradorDossie) {
            if ($tipoDossie->getNome() === $geradorDossie->getNomeTipoDossie()) {
                return $geradorDossie;
            }
        }

        return null;
    }
}

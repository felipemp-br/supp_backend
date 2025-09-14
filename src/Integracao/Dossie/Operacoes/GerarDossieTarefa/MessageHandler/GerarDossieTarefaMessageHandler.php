<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

/**
 * src/Integracao/Dossie/Operacoes/GerarDossieTarefa/MessageHandler/GerarDossieTarefaMessageHandler.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossieTarefa\MessageHandler;

use DateTime;
use Exception;
use Gedmo\Blameable\BlameableListener;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieManager;
use SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossieTarefa\Message\GerarDossieTarefaMessage;
use SuppCore\AdministrativoBackend\Security\InternalLogInService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class GerarDossieTarefaMessageHandler.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class GerarDossieTarefaMessageHandler
{
    /**
     * GerarDossieMessageHandler constructor.
     *
     * @param BlameableListener $blameableListener
     * @param DossieResource $dossieResource
     * @param TransactionManager $transactionManager
     * @param DossieManager $dossieManager
     * @param TarefaResource $tarefaResource
     * @param UsuarioResource $usuarioResource
     * @param InternalLogInService $internalLogInService
     */
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private readonly InternalLogInService $internalLogInService,
        private readonly DossieResource $dossieResource,
        private readonly TransactionManager $transactionManager,
        private readonly DossieManager $dossieManager,
        private readonly TarefaResource $tarefaResource,
        private readonly UsuarioResource $usuarioResource,
    ) {
    }

    /**
     * @param TipoDossieEntity $tipoDossie
     * @param PessoaEntity $pessoa
     * @param ProcessoEntity $processo
     * @return bool
     */
    public function existeDossieGeradoProcessoPeriodoGuarda(
        TipoDossieEntity $tipoDossie,
        PessoaEntity $pessoa,
        ProcessoEntity $processo
    ): bool {
        if (!$tipoDossie->getPeriodoGuarda()) {
            return false;
        }

        $dossie = $this
            ->dossieResource
            ->getRepository()
            ->findMostRecent($tipoDossie, $pessoa, $processo);

        return $dossie && (((new DateTime())->diff($dossie->getDataConsulta()))->days <= $tipoDossie->getPeriodoGuarda());
    }

    /**
     * @param GerarDossieTarefaMessage $gerarDossieTarefaMessage
     *
     * @return void|null
     *
     * @throws Exception
     */
    public function __invoke(GerarDossieTarefaMessage $gerarDossieTarefaMessage)
    {
        $tarefa = $this->tarefaResource->findOneBy(['uuid' => $gerarDossieTarefaMessage->getTarefaUuid()]);
        if (!$tarefa) {
            throw new Exception("Tarefa informada não foi encontrada {$gerarDossieTarefaMessage->getTarefaUuid()}");
        }

        $transactionId = $this->transactionManager->getCurrentTransactionId() ?? $this->transactionManager->begin();
        /** @var Interessado $interessado */
        foreach ($tarefa->getProcesso()->getInteressados() as $interessado) {
            $geradoresDossies = $this->dossieManager->getGeradoresDossies($tarefa, $interessado);
            foreach ($geradoresDossies as $geradorDossie) {
                if (!$this->existeDossieGeradoProcessoPeriodoGuarda(
                    $geradorDossie->getTipoDossie(),
                    $interessado->getPessoa(),
                    $tarefa->getProcesso()
                )) {
                    if ($gerarDossieTarefaMessage->getUsuarioId()) {
                        $usuario = $this->usuarioResource->findOne($gerarDossieTarefaMessage->getUsuarioId());
                        if ($usuario) {
                            $this->internalLogInService->logUserIn($usuario);
                            $this->blameableListener->setUserValue($usuario);
                        }
                    }

                    if ($interessado->getPessoa()?->getNumeroDocumentoPrincipal()) {
                        $dossieDTO = new Dossie();
                        $dossieDTO->setNumeroDocumentoPrincipal($interessado->getPessoa()->getNumeroDocumentoPrincipal());
                        $dossieDTO->setPessoa($interessado->getPessoa());
                        $dossieDTO->setFonteDados($geradorDossie->getTipoDossie()->getFonteDados());
                        $dossieDTO->setTipoDossie($geradorDossie->getTipoDossie());
                        $dossieDTO->setProcesso($tarefa->getProcesso());
                        $this->dossieResource->create($dossieDTO, $transactionId);
                    }
                }
            }
        }
        $this->transactionManager->commit($transactionId);
    }
}

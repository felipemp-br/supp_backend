<?php
/**
 * @noinspection PhpUnused
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

/**
 * src/Integracao/Dossie/Operacoes/GerarDossie/MessageHandler/GerarDossieMessageHandler.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossie\MessageHandler;

use Exception;
use SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossie\Message\GerarDossieMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class GerarDossieMessageHandler.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class GerarDossieMessageHandler
{
    /**
     * GerarDossieMessageHandler constructor.
     *
     * @param GerarDossieService $gerarDossieService
     */
    public function __construct(private GerarDossieService $gerarDossieService)
    {
    }

    /**
     * @param GerarDossieMessage $gerarDossieMessage
     *
     * @return void|null
     *
     * @throws Exception
     */
    public function __invoke(GerarDossieMessage $gerarDossieMessage)
    {
        $this->gerarDossieService->gerarDossie($gerarDossieMessage);
    }
}

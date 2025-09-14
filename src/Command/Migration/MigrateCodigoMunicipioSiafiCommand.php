<?php

declare(strict_types=1);
/**
 * /src/Command/Migration/MigrateCodigoMunicipioSiafiCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Migration;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio as MunicipioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\MunicipioResource;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MigrateCodigoMunicipioSiafiCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:migration:codigo_municipio_siafi', description: 'Inserir código SIAFI do município')]
class MigrateCodigoMunicipioSiafiCommand extends Command
{
    /**
     * MigrateCodigoMunicipioSiafiCommand constructor.
     *
     * @param MunicipioResource  $municipioResource
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly MunicipioResource $municipioResource,
        private readonly TransactionManager $transactionManager
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $codigosIbgeSiafi = array_chunk($this->parseCSVToArray(), 100, true);

        foreach ($codigosIbgeSiafi as $codigoIbgeSiafi) {
            $municipios = $this->municipioResource->getRepository()->findBy(
                ['codigoIBGE' => array_keys($codigoIbgeSiafi), 'codigoSIAFI' => null]
            );

            $transactionId = $this->transactionManager->begin();

            foreach ($municipios as $municipio) {
                /** @var MunicipioDTO $municiopioDTO */
                $municiopioDTO = $this->municipioResource->getDtoForEntity(
                    $municipio->getId(),
                    MunicipioDTO::class
                );

                $municiopioDTO->setCodigoSIAFI($codigoIbgeSiafi[$municipio->getCodigoIBGE()]);

                $this->municipioResource->update(
                    $municipio->getId(),
                    $municiopioDTO,
                    $transactionId
                );
            }

            $this->transactionManager->commit($transactionId);
        }

        return self::SUCCESS;
    }

    /**
     * @return array
     */
    private function parseCSVToArray(): array
    {
        $dePara = [];
        $csvFileToRead = fopen(dirname(__DIR__, 2).'/Resources/doc/TABMUN.csv', 'r');

        while (!feof($csvFileToRead)) {
            $data = fgetcsv($csvFileToRead, null, ';');

            if (false !== $data) {
                $dePara[end($data)] = (string) reset($data);
            }
        }

        fclose($csvFileToRead);

        return $dePara;
    }
}

<?php

declare(strict_types=1);
/**
 * /src/Command/Migration/MigrateCodigoPaisReceitaFederalCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Migration;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pais as PaisDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PaisResource;
use SuppCore\AdministrativoBackend\Entity\Pais;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MigrateCodigoPaisReceitaFederalCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:migration:codigo_pais_receita_federal',
    description: 'Inserir código Pais e Nome da Receita Federal'
)]
class MigrateCodigoPaisReceitaFederalCommand extends Command
{
    /**
     * MigrateCodigoPaisReceitaFederalCommand constructor.
     *
     * @param PaisResource       $paisResource
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly PaisResource $paisResource,
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
        $codigosReceitaFederal = array_chunk($this->parseCSVToArray(), 100, true);

        foreach ($codigosReceitaFederal as $codigoReceitaFederal) {
            /** @var Pais $paises */
            $paises = $this->paisResource->getRepository()->findBy(
                ['codigo' => array_keys($codigoReceitaFederal)]
            );

            $transactionId = $this->transactionManager->begin();

            foreach ($paises as $pais) {
                /** @var PaisDTO $paisDTO */
                $paisDTO = $this->paisResource->getDtoForEntity(
                    $pais->getId(),
                    PaisDTO::class
                );

                $paisDTO->setCodigoReceitaFederal($codigoReceitaFederal[$pais->getCodigo()]['codigo']);
                $paisDTO->setNomeReceitaFederal($codigoReceitaFederal[$pais->getCodigo()]['nome']);

                $this->paisResource->update(
                    $pais->getId(),
                    $paisDTO,
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
        $csvFileToRead = fopen(dirname(__DIR__, 2).'/Resources/doc/CODIGOPAISRECEITAFEDERAL.csv', 'r');

        while (!feof($csvFileToRead)) {
            $data = fgetcsv($csvFileToRead, null, ';');

            if (false !== $data) {
                if ('' !== $data[3]) {
                    $dePara[$data[3]] = ['codigo' => $data[0], 'nome' => $data[1]];
                }
            }
        }

        fclose($csvFileToRead);

        return $dePara;
    }
}

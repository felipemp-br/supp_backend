<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Database.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use SuppCore\AdministrativoBackend\Entity\Healthz;

/**
 * Check database.
 */
class Database implements CheckInterface
{
    /**
     * Define o tempo limite para a execução do teste.
     */
    private const TIME_LIMIT = 5;

    /**
     * Define o tempo estimado para a execução do teste.
     */
    private const ESTIMATED_TIME = 1.5;

    /**
     * Define a quantidade de registros para incluir e excluir no banco de dados.
     */
    private const NUMBER_OF_RECORDS = 100;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function check(): Failure|Success|Warning
    {
        $startTime = microtime(true);
        $endTime = null;

        try {
            for ($i = 0; $i < self::NUMBER_OF_RECORDS; ++$i) {
                $healthz = new Healthz();

                $this->entityManager->persist($healthz);
                $this->entityManager->flush();

                $this->entityManager->remove($healthz);
                $this->entityManager->flush();

                if ((microtime(true) - $startTime) > self::TIME_LIMIT) {
                    return new Failure(
                        sprintf('O teste excedeu o tempo limite de %d segundos', self::TIME_LIMIT)
                    );
                }
            }
            $endTime = microtime(true);
        } catch (\Throwable $e) {
            return new Failure($e->getMessage());
        } finally {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->delete()
                ->from(Healthz::class, 'h')
                ->getQuery()
                ->execute();
        }

        $time = $endTime - $startTime;

        if ($time > self::ESTIMATED_TIME) {
            return new Warning(sprintf('Teste executado em %f segundos', $time));
        }

        return new Success(sprintf('Teste executado em %f segundos', $time));
    }

    public function getLabel(): string
    {
        return 'Database';
    }
}

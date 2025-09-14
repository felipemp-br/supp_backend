<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Juntada/Trigger0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Juntada;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada\Trigger0005;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005Test extends TestCase
{
    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|TransactionManager $transactionManager;

    private TriggerInterface $trigger;

    public function setUp(): void
    {
        parent::setUp();

        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->trigger = new Trigger0005(
            $this->transactionManager
        );
    }

    /**
     * @throws Exception
     */
    public function testIndexacao(): void
    {
        $this->juntadaEntity->expects(self::once())
            ->method('getUuid')
            ->willReturn(Uuid::uuid4()->toString());

        $this->transactionManager->expects(self::once())
            ->method('addAsyncDispatch')
            ->with(self::isInstanceOf(IndexacaoMessage::class), self::isType('string'));

        $this->trigger->execute(null, $this->juntadaEntity, 'transaction');
    }
}

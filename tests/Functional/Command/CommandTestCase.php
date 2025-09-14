<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CommandTestCase.
 */
class CommandTestCase extends KernelTestCase
{
    protected KernelInterface $kernelClient;

    protected Application $aplication;

    protected BufferedOutput $consoleOutput;

    public function setUp(): void
    {
        parent::setUp();
        $this->kernelClient = static::createKernel();
        static::bootKernel();
        $this->aplication = new Application($this->kernelClient);
        $this->aplication->setAutoExit(false);
        $this->consoleOutput = new BufferedOutput();
    }

    /**
     * @param array $parameters
     *
     * @return int
     * @throws \Exception
     */
    protected function executeCommand(array $parameters): int
    {
        $input = new ArrayInput(
            $parameters,
        );
        $input->setInteractive(false);

        return $this->aplication->run($input, $this->consoleOutput);
    }
}

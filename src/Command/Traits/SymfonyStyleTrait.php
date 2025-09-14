<?php

declare(strict_types=1);
/**
 * /src/Command/Traits/SymfonyStyleTrait.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Trait SymfonyStyleTrait.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait SymfonyStyleTrait
{
    /**
     * Method to get SymfonyStyle object for console commands.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param bool|null       $clearScreen
     *
     * @return SymfonyStyle
     */
    protected function getSymfonyStyle(
        InputInterface $input,
        OutputInterface $output,
        ?bool $clearScreen = null
    ): SymfonyStyle {
        $clearScreen ??= true;

        $io = new SymfonyStyle($input, $output);

        if ($clearScreen) {
            $io->write("\033\143");
        }

        return $io;
    }
}

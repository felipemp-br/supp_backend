<?php

declare(strict_types=1);
/**
 * /src//Mapper/BaseMapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper;

use SuppCore\AdministrativoBackend\Mapper\Pipes\PipesManager;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class BaseMapper.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class BaseMapper
{
    protected MapperManager $mapperManager;

    protected PipesManager $pipesManager;

    protected array $cache = [];

    /**
     * BaseMapper constructor.
     *
     * @param MapperManager $mapperManager
     * @param PipesManager  $pipesManager
     * @param Stopwatch     $stopwatch
     */
    public function __construct(
        MapperManager $mapperManager,
        PipesManager $pipesManager,
        protected Stopwatch $stopwatch
    ) {
        $this->mapperManager = $mapperManager;
        $this->pipesManager = $pipesManager;
    }
}

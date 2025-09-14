<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\TipoRelatorio;

use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoRelatorioResource;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;
use SuppCore\DividaBackend\Api\V1\Resource\CertidaoDividaAtivaResource;
use SuppCore\DividaBackend\Api\V1\Resource\VinculacaoCredorEspecieCreditoResource;
use SuppCore\DividaBackend\Entity\Credito as CreditoEntity;
use SuppCore\DividaBackend\Entity\ImputacaoPagamento as ImputacaoPagamentoEntity;
use SuppCore\DividaBackend\Helper\Credito\MotorCalculo\InterfaceMotorCalculoCredito;
use SuppCore\DividaBackend\Helper\Credito\MotorCalculo\MemoriaCalculo\MemoriaCalculoConsolidadaCreditos;
use SuppCore\DividaBackend\Helper\Credito\MotorCalculo\MemoriaCalculo\MemoriaCalculoCredito;
use SuppCore\DividaBackend\Helper\Traits\CalculoEncargos;
use SuppCore\DividaBackend\Helper\Utils\Cache;
use SuppCore\DividaBackend\Rules\RulesTranslate;

/**
 *
 */
class TipoRelatorioManager
{
    /** @var InterfaceTipoRelatorio[] */
    protected array $driversTipoRelatorio;

    /**
     */
    public function __construct()
    {
        $this->driversTipoRelatorio = [];
    }


    /**
     * @param InterfaceTipoRelatorio $driverTipoRelatorio
     */
    public function addDriverTipoRelatorio(InterfaceTipoRelatorio $driverTipoRelatorio): void
    {
        $this->driversTipoRelatorio[] = $driverTipoRelatorio;
    }

    /**
     * @return InterfaceTipoRelatorio[]
     */
    public function getDriversTiposRelatorio(): array
    {
        return $this->driversTipoRelatorio;
    }

    /**
     * @param TipoRelatorio $tipoRelatorio
     * @return InterfaceTipoRelatorio|null
     */
    public function getTipoRelatorioDriver(TipoRelatorio $tipoRelatorio): InterfaceTipoRelatorio|null
    {
        foreach ($this->driversTipoRelatorio as $driver) {
            // identifica o driver aplicável
            if ($driver->supports($tipoRelatorio)) {
               return $driver;
            }
        }

        return null;
    }
}

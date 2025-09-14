<?php

/** @noinspection PhpUnused */

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/ProtocoloExternoManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ProtocoloExternoManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProtocoloExternoManager
{
    /** @var ProtocoloExterno[] */
    protected array $driversProtocoloExterno;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param FormularioResource            $formularioResource
     */
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly FormularioResource $formularioResource,
    ) {
        $this->driversProtocoloExterno = [];
    }

    /**
     * @param ProtocoloExterno $driverProtocoloExterno
     */
    public function addDriverProtocoloExterno(ProtocoloExterno $driverProtocoloExterno): void
    {
        $this->driversProtocoloExterno[] = $driverProtocoloExterno;
    }

    /**
     * @return ProtocoloExterno[]
     */
    public function getDriversProtocolosExternos(): array
    {
        return $this->driversProtocoloExterno;
    }

    /**
     * @param FormularioEntity $formulario
     * @param ProcessoEntity   $processoEntity
     * @param ProcessoDTO      $processoDTO
     *
     * @return DadosProtocoloExterno|null
     *
     * @throws Exception
     */
    public function getDadosProtocoloExterno(
        FormularioEntity $formulario,
        ProcessoEntity $processoEntity,
        ProcessoDTO $processoDTO
    ): ?DadosProtocoloExterno {
        $driverProcotoloExterno = $this->getDriverProtocoloExterno($processoDTO);

        return $driverProcotoloExterno?->getDadosProtocoloExterno($formulario, $processoEntity, $processoDTO);
    }

    /**
     * @param ProcessoDTO $processoDTO
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validate(ProcessoDTO $processoDTO): bool
    {
        $driverProcotoloExterno = $this->getDriverProtocoloExterno($processoDTO);
        if ($driverProcotoloExterno) {
            $dadosRequerimento = $processoDTO->getDadosRequerimento() ?
                json_decode($processoDTO->getDadosRequerimento(), true) : [];

            return $driverProcotoloExterno->validate($dadosRequerimento);
        }

        return true;
    }

    /**
     * @param ProcessoDTO $restDto
     *
     * @return ProtocoloExterno|null
     *
     * @throws Exception
     */
    private function getDriverProtocoloExterno(ProcessoDTO $restDto): ProtocoloExterno|null
    {
        // verifica se eh um protocolo externo
        if (!$restDto->getProtocoloEletronico()
            || (
                $this->authorizationChecker->isGranted('ROLE_COLABORADOR')
                && !$this->authorizationChecker->isGranted('ROLE_DPU')
            )
            || !$restDto->getDadosRequerimento()) {
            return null;
        }

        // verifica se  foi enviado um tipo especifico de formulario via protocolo externo
        $dadosFormulario = json_decode($restDto->getDadosRequerimento(), true);
        $siglaFormulario = $dadosFormulario['tipoRequerimento'] ?? false;
        if (!$siglaFormulario) {
            return null;
        }

        $formularioEntity = $this->formularioResource->getRepository()->findOneBy(
            ['sigla' => $siglaFormulario]
        );

        if (!$formularioEntity) {
            throw new Exception("Formulário enviado ($siglaFormulario) não indetificado!");
        }

        // verifica se tem um driver suportado para recuperar as informacoes necessarias
        // para o protocolo externo do processo
        $supportedDrivers = [];
        foreach ($this->driversProtocoloExterno as $driver) {
            if ($driver->supports($formularioEntity)) {
                $supportedDrivers[$driver->getOrder($formularioEntity)][] = $driver;
            }
        }

        // $supportedDrivers = [ 0 => [Driver1, Driver2], 1 => [Driver3, Driver4], ...]
        return $supportedDrivers ? current($supportedDrivers[min(array_keys($supportedDrivers))]) : null;
    }
}

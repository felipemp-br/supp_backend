<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0006.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0006 implements PipeInterface
{
    /**
     * Pipe0006 constructor.
     *
     * @param RequestStack                $requestStack
     * @param JuntadaRepository           $juntadaRepository
     * @param ComponenteDigitalRepository $componenteDigitalRepository
     */
    public function __construct(
        private RequestStack $requestStack,
        private JuntadaRepository $juntadaRepository,
        private ComponenteDigitalRepository $componenteDigitalRepository
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|Processo          $entity
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        $context = null;
        if (null !== $this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
        }

        if (isset($context->juntadaIndex)) {
            $index = [];
            $juntadas = $this->juntadaRepository->findJuntadasByProcessoAsArray($entity->getId());
            foreach ($juntadas as $i => $juntada) {
                $index[$i] = [
                    'id' => $juntada['id'],
                    'numeracaoSequencial' => $juntada['numeracaoSequencial'],
                    'ativo' => $juntada['ativo'],
                    'componentesDigitais' => [],
                ];
                foreach ($juntada['documento']['componentesDigitais'] as $componenteDigital) {
                    $index[$i]['componentesDigitais'][] = $componenteDigital['id'];
                }
                foreach ($juntada['documento']['vinculacoesDocumentos'] as $vinculacaoDocumento) {
                    foreach ($vinculacaoDocumento['documentoVinculado']['componentesDigitais'] as $componenteDigital) {
                        $index[$i]['componentesDigitais'][] = $componenteDigital['id'];
                    }
                }
            }
            $restDto->setJuntadaIndex($index);
        }

        if (isset($context->latestJuntadaIndex)) {
            $index = [];
            $index['status'] = 'sem_juntadas';
            $juntada = $this->juntadaRepository->findLastNaoVinculadaByProcessoId($entity->getId());
            if ($juntada) {
                $index['status'] = 'sucesso';
                $index['juntadaId'] = $juntada->getId();
                $index['numeracaoSequencial'] = $juntada->getNumeracaoSequencial();
                if (!$juntada->getAtivo()) {
                    $index['status'] = 'desentranhada';
                }
                $componenteDigital = $this->componenteDigitalRepository->findFirstByJuntadaIdAndProcessoId(
                    $juntada->getId()
                );
                if ($componenteDigital) {
                    $index['componenteDigitalId'] = $componenteDigital->getId();
                } else {
                    $index['status'] = 'sem_componentes_digitais';
                    /* @var VinculacaoDocumento $vinculacoesDocumentos */
                    foreach ($juntada->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                        /* @var ComponenteDigital $componenteDigital */
                        foreach ($vinculacaoDocumento->getDocumentoVinculado()
                                     ->getComponentesDigitais() as $componenteDigitalVinculado) {
                            $index['status'] = 'sucesso';
                            $index['componenteDigitalId'] = $componenteDigitalVinculado->getId();
                            break 2;
                        }
                    }
                }
            }
            $restDto->setJuntadaIndex($index);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

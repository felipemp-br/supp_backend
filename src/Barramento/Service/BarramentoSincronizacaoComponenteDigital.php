<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Barramento\Message\CriaRespostaOficioBarramentoMessage;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos diversos entre o Supp e o Barramento.
 */
class BarramentoSincronizacaoComponenteDigital
{
    use BarramentoUtil;
    use OrigemDados;

    private string $transactionId;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Response obtido por meio do request enviado ao Soap
     * Será utilizado para reuso dentro dos métodos das classes herdadas.
     */
    private stdClass $response;

    private AssinaturaResource $assinaturaResource;

    private OrigemDadosResource $origemDadosResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    private ComponenteDigitalRepository $componenteDigitalRepository;

    private DocumentoResource $documentoResource;

    private TransactionManager $transactionManager;

    protected array $config;

    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        AssinaturaResource $assinaturaResource,
        ComponenteDigitalResource $componenteDigitalResource,
        ComponenteDigitalRepository $componenteDigitalRepository,
        OrigemDadosResource $origemDadosResource,
        DocumentoResource $documentoResource,
        TransactionManager $transactionManager
    ) {
        $this->logger = $logger;
        $this->config = $parameterBag->get('integracao_barramento');
        $this->assinaturaResource = $assinaturaResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->componenteDigitalRepository = $componenteDigitalRepository;
        $this->origemDadosResource = $origemDadosResource;
        $this->documentoResource = $documentoResource;
        $this->transactionManager = $transactionManager;
    }

    /**
     * Sincroniza componente digital do Barramento com o Sapiens.
     *
     * @param string $conteudo - Conteúdo do componente digital
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function sincronizaComponenteDigital(
        string $conteudo,
        DocumentoEntity $documento,
        stdClass $componenteDigitalTramitacao,
        string $transactionId
    ): ComponenteDigital {
        $this->transactionId = $transactionId;

        $componenteDigital = $this->componenteDigitalRepository->findByHashAndDocumento(
            hash('SHA256', $conteudo),
            $documento->getId()
        );

        if ($componenteDigital) {
            $this->logger->info('Componente Digital já foi sincronizado: '.hash('SHA256', $conteudo));

            return $componenteDigital[0];
        }

        if ($documento->getComponentesDigitais() &&
                $documento->getComponentesDigitais()->first() instanceof ComponenteDigital) {
            return $documento->getComponentesDigitais()->first();
        }

        /** @var ComponenteDigitalDTO $componenteDigitalDto */
        $componenteDigitalEntity = $this->criaComponenteDigital($conteudo, $componenteDigitalTramitacao, $documento);

        if (isset($componenteDigitalTramitacao->assinaturaDigital)) {
            if (!is_array($componenteDigitalTramitacao->assinaturaDigital)) {
                $componenteDigitalTramitacao->assinaturaDigital = [$componenteDigitalTramitacao->assinaturaDigital];
            }
            foreach ($componenteDigitalTramitacao->assinaturaDigital as $assinaturaDigitalTramitacao) {
                /* @var AssinaturaEntity $assinaturaDigitalEntity */
                $this->criaAssinaturaDigital($assinaturaDigitalTramitacao, $componenteDigitalEntity, $documento);
            }
        }

        return $componenteDigitalEntity;
    }

    /**
     * Cria componente digital.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function criaComponenteDigital(
        string $conteudo,
        stdClass $componenteDigitalTramitacao,
        Documento $documento
    ): ComponenteDigital {
        $extensao = strtolower(pathinfo($componenteDigitalTramitacao->nome, PATHINFO_EXTENSION));
        $componenteDigitalDto = new ComponenteDigitalDTO();
        $componenteDigitalDto->setConteudo($conteudo);
        $componenteDigitalDto->setEditavel(false);
        $componenteDigitalDto->setFileName($componenteDigitalTramitacao->nome);
        $componenteDigitalDto->setExtensao($extensao);
        $componenteDigitalDto->setMimetype($componenteDigitalTramitacao->mimeType);
        $componenteDigitalDto->setTamanho($componenteDigitalTramitacao->tamanhoEmBytes);
        $componenteDigitalDto->setHash(hash('SHA256', $conteudo));
        $componenteDigitalDto->setDocumento($documento);

        $origemDadosDTo = $this->origemDadosFactory();
        $origemDadosDTo->setIdExterno($componenteDigitalTramitacao->hash->_);
        $origemDadosDTo->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);

        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTo, $this->transactionId);
        $componenteDigitalDto->setOrigemDados($origemDadosEntity);

        return $this->componenteDigitalResource
            ->create($componenteDigitalDto, $this->transactionId);
    }

    /**
     * Cria assinatura de um componente digital.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function criaAssinaturaDigital(
        stdClass $assinaturaDigitalTramitacao,
        ComponenteDigital $componenteDigital,
        DocumentoEntity $documento
    ): AssinaturaEntity {
        $assinaturaDto = new AssinaturaDTO();

        $assinaturaDto->setComponenteDigital($componenteDigital);

        $assinaturaDto->setDataHoraAssinatura(
            $this->converteDataBarramento($assinaturaDigitalTramitacao->dataHora)
        );

        $assinaturaDto->setCadeiaCertificadoPEM(
            ('null' !== $assinaturaDigitalTramitacao->cadeiaDoCertificado->_ ?
                base64_decode($assinaturaDigitalTramitacao->cadeiaDoCertificado->_) :
                'semCertificado'
            )
        );
        $assinaturaDto->setAlgoritmoHash($assinaturaDigitalTramitacao->hash->algoritmo);
        $assinaturaDto->setAssinatura($assinaturaDigitalTramitacao->hash->_);

        /** @var OrigemDadosDTO $origemDadosDTo */
        $origemDadosDTo = $this->origemDadosFactory();
        $origemDadosDTo->setIdExterno($documento->getOrigemDados()->getIdExterno());
        $origemDadosDTo->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);

        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTo, $this->transactionId);
        $assinaturaDto->setOrigemDados($origemDadosEntity);

        return $this->assinaturaResource->create($assinaturaDto, $this->transactionId);
    }

    /**
     * @param $hash
     * @param DocumentoEntity $documento
     * @return void
     */
    public function searchOficioFromHash($hash, DocumentoEntity $documento)
    {
        $componenteOficio = $this->componenteDigitalResource->findOneBy(
            [
                'hash' => $hash,
                'origemDados' => null,
            ]
        );
        if ($componenteOficio) {
            $criarRespostaOficioMessage = new CriaRespostaOficioBarramentoMessage();
            $criarRespostaOficioMessage->setDocumentoId($componenteOficio->getDocumento()->getId());
            $criarRespostaOficioMessage->setProcessoId($documento->getProcessoOrigem()->getId());
            $this->transactionManager->addAsyncDispatch($criarRespostaOficioMessage, $this->transactionId);
        }
    }
}

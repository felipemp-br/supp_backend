<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Representante;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoAdministrativoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\InteressadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeInteressadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RepresentanteResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\CopyProcessoDocumentosMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0011.
 *
 * @descSwagger  =Adiciona assuntos e interessados do processo origem ao novo processo!
 *
 * @classeSwagger=Trigger0011
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0011 implements TriggerInterface
{
    /**
     * @param AssuntoResource $assuntoResource
     * @param InteressadoResource $interessadoResource
     * @param ModalidadeInteressadoResource $modalidadeInteressadoResource
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ParameterBagInterface $parameterBag
     * @param RepresentanteResource $representanteResource
     * @param TransactionManager $transactionManager
     * @param FormularioResource $formularioResource
     * @param ProtocoloExternoManager $protocoloExternoManager
     * @param AssuntoAdministrativoResource $assuntoAdministrativoResource
     */
    public function __construct(
        private readonly AssuntoResource $assuntoResource,
        private readonly InteressadoResource $interessadoResource,
        private readonly ModalidadeInteressadoResource $modalidadeInteressadoResource,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly ParameterBagInterface $parameterBag,
        private readonly RepresentanteResource $representanteResource,
        private readonly TransactionManager $transactionManager,
        private readonly FormularioResource $formularioResource,
        private readonly ProtocoloExternoManager $protocoloExternoManager,
        private readonly AssuntoAdministrativoResource $assuntoAdministrativoResource
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getProcessoOrigem()) {
            /* @var Assunto */
            foreach ($restDto->getProcessoOrigem()->getAssuntos() as $assuntoClonado) {
                $assuntoDTO = new Assunto();
                $assuntoDTO->setProcesso($entity);
                $assuntoDTO->setAssuntoAdministrativo($assuntoClonado->getAssuntoAdministrativo());
                $assuntoDTO->setPrincipal($assuntoClonado->getPrincipal());
                $this->assuntoResource->create($assuntoDTO, $transactionId);
            }

            /* @var Interessado */
            foreach ($restDto->getProcessoOrigem()->getInteressados() as $interessadoClonado) {
                $interessadoDTO = new Interessado();
                $interessadoDTO->setPessoa($interessadoClonado->getPessoa());
                $interessadoDTO->setProcesso($entity);
                $interessadoDTO->setModalidadeInteressado($interessadoClonado->getModalidadeInteressado());
                $this->interessadoResource->create($interessadoDTO, $transactionId);

                /** @var Representante $representanteClonado */
                foreach ($interessadoClonado->getRepresentantes() as $representanteClonado) {
                    $representanteDTO = new Representante();
                    $representanteDTO->setNome($representanteClonado->getNome());
                    $representanteDTO->setInscricao($representanteClonado->getInscricao());
                    $representanteDTO->setModalidadeRepresentante($representanteClonado->getModalidadeRepresentante());
                    $representanteDTO->setInteressado($interessadoClonado);
                    $this->representanteResource->create($representanteDTO, $transactionId);
                }
            }

            if ($restDto->getProcessoOrigemIncluirDocumentos()) {
                // Implementado processamento de documentos em background
                $this->transactionManager->addAsyncDispatch(
                    new CopyProcessoDocumentosMessage(
                        $entity->getUuid()
                    ),
                    $transactionId
                );
            }
        }

        //CASO SEJA USUÁRIO EXTERNO E SEJA PROCESSO PADRÃO DE PROTOCOLO (SEM DADOS REQUERIMENTO)
        if ($restDto->getProtocoloEletronico() &&
            (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) &&
            !$restDto->getDadosRequerimento()) {
            $interessadoDTO = new Interessado();
            $interessadoDTO->setPessoa($restDto->getProcedencia());
            $interessadoDTO->setProcesso($entity);
            $interessadoDTO->setModalidadeInteressado(
                $this->modalidadeInteressadoResource
                    ->findOneBy(
                        ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_interessado.const_1')]
                    )
            );
            $this->interessadoResource->create($interessadoDTO, $transactionId);

            $assuntoDTO = new Assunto();
            $assuntoDTO->setAssuntoAdministrativo(
                $this->assuntoAdministrativoResource
                    ->findOneBy(
                        ['nome' => 'ANÁLISE DE DOCUMENTO PROTOCOLADO VIA PROTOCOLO ELETRÔNICO']
                    )
            );
            $assuntoDTO->setPrincipal(true);
            $assuntoDTO->setProcesso($entity);
            $this->assuntoResource->create($assuntoDTO, $transactionId);
        }

        // CASO SEJA USUÁRIO EXTERNO
        if ($restDto->getProtocoloEletronico()
            && (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) &&
            $restDto->getDadosRequerimento()) {
            $dadosFormulario = json_decode($restDto->getDadosRequerimento(), true);
            $siglaFormulario = $dadosFormulario['tipoRequerimento'] ?? false;
            $formularioEntity = $siglaFormulario ?
                $this->formularioResource->getRepository()->findOneBy(
                    ['sigla' => $siglaFormulario]
                ) : null;

            $dadosProtocoloExterno = $formularioEntity ?
                $this->protocoloExternoManager->getDadosProtocoloExterno($formularioEntity, $entity, $restDto) :
                null;

            $modalidadeInteressadoAtivo = $this->modalidadeInteressadoResource->findOneBy(
                ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_interessado.const_1')]
            );

            if (!$this->authorizationChecker->isGranted('ROLE_DPU')) {
                $interessadoDTO = new Interessado();
                $interessadoDTO->setPessoa($restDto->getProcedencia());
                $interessadoDTO->setProcesso($entity);
                $interessadoDTO->setModalidadeInteressado($modalidadeInteressadoAtivo);
                $this->interessadoResource->create($interessadoDTO, $transactionId);
            }

            foreach ($dadosProtocoloExterno->getAssuntosAdministrativoProcesso() as $assunto) {
                $assuntoDTO = new Assunto();
                $assuntoDTO->setAssuntoAdministrativo($assunto);
                $assuntoDTO->setPrincipal(true);
                $assuntoDTO->setProcesso($entity);
                $this->assuntoResource->create($assuntoDTO, $transactionId);
            }

            if ($dadosProtocoloExterno->getRequerente()) {
                // Criamos o beneficiário como Interessado tb
                $interessadoDTO = new Interessado();
                $interessadoDTO->setPessoa($dadosProtocoloExterno->getRequerente());
                $interessadoDTO->setProcesso($entity);
                $interessadoDTO->setModalidadeInteressado($modalidadeInteressadoAtivo);
                $this->interessadoResource->create($interessadoDTO, $transactionId);
            }

            $modalidadeInteressadoPassivo = $this->modalidadeInteressadoResource->findOneBy(
                ['valor' => 'REQUERIDO (PÓLO PASSIVO)']
            );

            if ($dadosProtocoloExterno->getRequerido()) {
                $interessadoDTO = new Interessado();
                $interessadoDTO->setPessoa($dadosProtocoloExterno->getRequerido());
                $interessadoDTO->setProcesso($entity);
                $interessadoDTO->setModalidadeInteressado($modalidadeInteressadoPassivo);
                $this->interessadoResource->create($interessadoDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 11;
    }
}

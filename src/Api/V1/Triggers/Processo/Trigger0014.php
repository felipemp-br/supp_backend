<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoSolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta\Rule0001;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0014.
 *
 * @descSwagger  =Cria tarefa de processo do usuário externo
 *
 * @classeSwagger=Trigger0014
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0014 implements TriggerInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TarefaResource $tarefaResource
     * @param EspecieTarefaResource $especieTarefaResource
     * @param ProtocoloExternoManager $protocoloExternoManager
     * @param FormularioResource $formularioResource
     * @param VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
     * @param TipoSolicitacaoAutomatizadaResource $tipoSolicitacaoAutomatizadaResource
     * @param RulesManager $rulesManager
     * @param SuppParameterBag $suppParameterBag
     */
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TarefaResource $tarefaResource,
        private readonly EspecieTarefaResource $especieTarefaResource,
        private readonly ProtocoloExternoManager $protocoloExternoManager,
        private readonly FormularioResource $formularioResource,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private readonly TipoSolicitacaoAutomatizadaResource $tipoSolicitacaoAutomatizadaResource,
        private readonly RulesManager $rulesManager,
        private readonly SuppParameterBag $suppParameterBag
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
     * @param string                         $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function execute(
        Processo|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        //CASO SEJA USUÁRIO EXTERNO E SEJA PROCESSO PADRÃO DE PROTOCOLO (SEM DADOS REQUERIMENTO)
        if ($restDto->getProtocoloEletronico() &&
            (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) &&
            !$restDto->getDadosRequerimento()) {
            $inicioPrazo = new DateTime();
            $finalPrazo = new DateTime();
            $finalPrazo->add(new DateInterval('P5D'));
            $especieTarefa = $this->especieTarefaResource->findOneBy(
                [
                    'nome' => 'ANALISAR REQUERIMENTO DO PROTOCOLO ELETRÔNICO',
                ]
            );

            $tarefaDTO = new Tarefa();
            $tarefaDTO->setProcesso($entity);
            $tarefaDTO->setSetorResponsavel($restDto->getSetorInicial()); //PROTOCOLO DA UNIDADE ENVIADA
            $tarefaDTO->setEspecieTarefa($especieTarefa);
            $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
            $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

            $this->tarefaResource->create($tarefaDTO, $transactionId);
        }

        // CASO SEJA USUÁRIO EXTERNO
        if ($restDto->getProtocoloEletronico() &&
            (false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR')) &&
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

            $tipoSolicitacaoAutomatizada = $this->tipoSolicitacaoAutomatizadaResource->getRepository()->findOneBy(
                ['formulario' => $formularioEntity]
            );

            if ($tipoSolicitacaoAutomatizada) {
                // Sabendo que o formulário em questão é relacionado com um tipoSolicitacaoAutomatizada,
                // não iremos criar tarefas
                return;
            }

            if ($dadosProtocoloExterno?->getAbrirTarefa()) {
                $inicioPrazo = new DateTime();
                $finalPrazo = new DateTime();
                $finalPrazo->add(new DateInterval('P5D'));

                $especieTarefa = $dadosProtocoloExterno?->getEspecieTarefa() ?:
                    $this
                        ->especieTarefaResource
                        ->findOneBy([
                            'nome' => $this->suppParameterBag->get('constantes.entidades.especie_tarefa.const_5'),
                        ]);

                // cria a tarefa
                $tarefaDTO = new Tarefa();
                $tarefaDTO->setProcesso($entity);
                $tarefaDTO->setSetorResponsavel($restDto->getSetorInicial()); // PROTOCOLO DA UNIDADE ENVIADA
                $tarefaDTO->setSetorOrigem($restDto->getSetorInicial());
                $tarefaDTO->setEspecieTarefa($especieTarefa);
                $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
                $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);
                if ($dadosProtocoloExterno) {
                    $tarefaDTO->setObservacao($dadosProtocoloExterno->getObservacaoTarefa());
                    $tarefaDTO->setPostIt($dadosProtocoloExterno->getPostItTarefa());
                }
                $tarefaEntity = $this->tarefaResource->create($tarefaDTO, $transactionId);

                // etiquetas da tarefa
                $this->rulesManager->disableRule(Rule0001::class);
                foreach ($dadosProtocoloExterno->getEtiquetasTarefa() as $etiquetaTarefa) {
                    $vinculacaoEtiquetaTarefaDTO = new VinculacaoEtiquetaDTO();
                    $vinculacaoEtiquetaTarefaDTO->setEtiqueta($etiquetaTarefa);
                    $vinculacaoEtiquetaTarefaDTO->setTarefa($tarefaEntity);
                    $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaTarefaDTO, $transactionId);
                }
                $this->rulesManager->enableRule(Rule0001::class);
            }
        }
    }

    public function getOrder(): int
    {
        return 14;
    }
}

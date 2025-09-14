<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta\Rule0003;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0013.
 *
 * @descSwagger  =Cria metadados do processo automaticamente caso o usuário logado seja um usuário externo
 *
 * @classeSwagger=Trigger0013
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0013 implements TriggerInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ClassificacaoResource $classificacaoResource
     * @param EspecieProcessoResource $especieProcessoResource
     * @param ModalidadeMeioResource $modalidadeMeioResource
     * @param ParameterBagInterface $parameterBag
     * @param ProtocoloExternoManager $protocoloExternoManager
     * @param FormularioResource $formularioResource
     * @param VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
     * @param RulesManager $rulesManager
     */
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly ClassificacaoResource $classificacaoResource,
        private readonly EspecieProcessoResource $especieProcessoResource,
        private readonly ModalidadeMeioResource $modalidadeMeioResource,
        private readonly ParameterBagInterface $parameterBag,
        private readonly ProtocoloExternoManager $protocoloExternoManager,
        private readonly FormularioResource $formularioResource,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private readonly RulesManager $rulesManager,
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeCreate',
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
     * @throws Exception
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        Processo|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        //CASO SEJA USUÁRIO EXTERNO E SEJA PROCESSO PADRÃO DE PROTOCOLO (SEM DADOS REQUERIMENTO)
        if ($restDto->getProtocoloEletronico() &&
            $this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO') &&
            !$restDto->getDadosRequerimento()) {
            // SETA METADADOS DO PROCESSO
            $restDto->setClassificacao(
                $this->classificacaoResource->getRepository()->findOneBy(['codigo' => '069'])
            );
            // COMUNICAÇÕES: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL
            $restDto->setEspecieProcesso($this->especieProcessoResource
                ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.especie_processo.const_1')]));
            $restDto->setModalidadeMeio($this->modalidadeMeioResource
                ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_meio.const_1')]));
            ($restDto->getRequerimento() || $restDto->getDadosRequerimento()) ?
                $restDto->setVisibilidadeExterna(false) :
                $restDto->setVisibilidadeExterna(true);
        }

        // CASO SEJA USUÁRIO EXTERNO OU ADOVGADO DPU
        if ($restDto->getProtocoloEletronico()
            && (
                $this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')
                || $this->authorizationChecker->isGranted('ROLE_DPU')
            )
            && $restDto->getDadosRequerimento()) {
            $dadosFormulario = json_decode($restDto->getDadosRequerimento(), true);
            $siglaFormulario = $dadosFormulario['tipoRequerimento'] ?? false;
            $formularioEntity = $siglaFormulario ?
                $this->formularioResource->getRepository()->findOneBy(
                    ['sigla' => $siglaFormulario]
                ) : null;

            $dadosProtocoloExterno = $formularioEntity ?
                $this->protocoloExternoManager->getDadosProtocoloExterno($formularioEntity, $entity, $restDto) :
                null;

            $restDto->setTitulo($dadosProtocoloExterno?->getTituloProcesso() ?: $restDto->getTitulo());
            $restDto->setDescricao($dadosProtocoloExterno?->getDescricaoProcesso() ?: $restDto->getDescricao());

            $restDto->setClassificacao(
                $dadosProtocoloExterno?->getClassificacaoProcesso() ?:
                    $this
                        ->classificacaoResource
                        ->getRepository()
                        ->findOneBy(
                            ['codigo' => $this->parameterBag->get('constantes.entidades.classificacao.const_1')]
                        )
            );

            $restDto->setEspecieProcesso(
                $dadosProtocoloExterno?->getEspecieProcesso() ?:
                    $this->especieProcessoResource
                        ->findOneBy(
                            ['nome' => $this->parameterBag->get('constantes.entidades.especie_processo.const_1')]
                        )
            );

            $restDto->setModalidadeMeio(
                $dadosProtocoloExterno?->getModalidadeMeio() ?:
                    $this->modalidadeMeioResource
                        ->findOneBy(
                            ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_meio.const_1')]
                        )
            );

            // OLHAR ISTO AQUI
            // Trigger 0006
            $restDto->setSetorAtual($dadosProtocoloExterno->getSetor());
            $restDto->setSetorInicial($dadosProtocoloExterno->getSetor());

            $this->rulesManager->disableRule(Rule0003::class);
            foreach ($dadosProtocoloExterno->getEtiquetasProcesso() as $etiquetaProcesso) {
                $vinculacaoEtiquetaDTO = new VinculacaoEtiquetaDTO();
                $vinculacaoEtiquetaDTO->setEtiqueta($etiquetaProcesso);
                $vinculacaoEtiquetaDTO->setProcesso($entity);
                $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
            }
            $this->rulesManager->enableRule(Rule0003::class);

            ($restDto->getRequerimento() || $restDto->getDadosRequerimento()) ?
                $restDto->setVisibilidadeExterna(false) :
                $restDto->setVisibilidadeExterna(true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}

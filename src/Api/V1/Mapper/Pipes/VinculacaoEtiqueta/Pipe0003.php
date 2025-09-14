<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/VinculacaoEtiqueta/Pipe0003.php.
 *
 * @@author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoEtiqueta;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Pipe0003.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
{
    /**
     * Pipe0003 constructor.
     */
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDTO|RestDtoInterface|null $restDto
     * @param VinculacaoEtiquetaEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $context = [];
        if ($entity->getObjectClass() && $entity->getObjectUuid()) {
            /** @var Query $query */
            $query = $this->managerRegistry->getManager()->createQuery(
                'SELECT e
            FROM '.$entity->getObjectClass().' e
            WHERE e.uuid = :objectUuid'
            );
            $query->setParameter('objectUuid', $entity->getObjectUuid());
            $query->setMaxResults(1);
            /** @var EntityInterface[] $result */
            $result = $query->getResult();
            if (count($result) > 0) {
                $restDto->setObjectId($result[0]->getId());
                // documento
                if ($entity->getEtiqueta()->getNome() ===
                    $this->parameterBag->get('constantes.entidades.etiqueta.const_1')) {
                    /** @var Documento $documento */
                    $documento = $result[0];
                    $context = $restDto->getObjectContext() ? json_decode($restDto->getObjectContext(), true) : [];
                    if (!$documento->getApagadoEm() &&
                        (!$documento->getDocumentoAvulsoRemessa() ||
                            !$documento->getDocumentoAvulsoRemessa()->getDataHoraRemessa())) {
                        $context['podeApagar'] = true;
                    }
                    if (!$documento->getApagadoEm() &&
                        $documento->getDocumentoAvulsoRemessa()) {
                        $context['documentoAvulsoUuid'] = $documento->getDocumentoAvulsoRemessa()->getUuid();
                        if ($documento->getDocumentoAvulsoRemessa()->getDocumentoResposta()) {
                            $context['verRespostaId'] = $documento->getDocumentoAvulsoRemessa()->getDocumentoResposta()->getId(
                            );
                            $context['verRespostaUuid'] = $documento->getDocumentoAvulsoRemessa()->getDocumentoResposta(
                            )->getUuid();
                        }
                    }
                    if ($documento->getApagadoEm()) {
                        $context['podeRestaurar'] = true;
                    }
                    $context['componentesDigitaisId'] = [];
                    /** @var ComponenteDigital $componenteDigitalEntity */
                    foreach ($documento->getComponentesDigitais() as $componenteDigitalEntity) {
                        $context['componentesDigitaisId'][] = $componenteDigitalEntity->getId();
                        if ($componenteDigitalEntity->getAssinaturas()->count() > 0) {
                            $context['assinado'] = true;
                            foreach($componenteDigitalEntity->getAssinaturas() as $assinatura) {
                                if ($assinatura->getCriadoPor()->getId() === $this->tokenStorage->getToken()->getUser()->getId()) {
                                    $context['minhaAssinatura'] = true;
                                }
                            }
                        }
                        if (!$documento->getApagadoEm() &&
                            ('html' === $componenteDigitalEntity->getExtensao() ||
                                'HTML' === $componenteDigitalEntity->getExtensao())) {
                            $context['podeConverterPDF'] = true;
                        }
                        if (!$documento->getApagadoEm() &&
                            $componenteDigitalEntity->getConvertidoPdf()) {
                            $context['podeConverterHTML'] = true;
                        }
                        if(!$documento->getApagadoEm() &&
                            ('pdf' === $componenteDigitalEntity->getExtensao() ||
                                'PDF' === $componenteDigitalEntity->getExtensao())) {
                            $context['podePades'] = true;
                        }
                    }
                    if ($documento->getVinculacoesDocumentos()->count()) {
                        $context['temAnexos'] = true;
                    }
                    /** @var VinculacaoDocumento $vinculacaoDocumento */
                    foreach ($documento->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                        foreach ($vinculacaoDocumento->getDocumentoVinculado()->getComponentesDigitais(
                        ) as $componenteDigitalEntity) {
                            $context['componentesDigitaisVinculadosId'][] = $componenteDigitalEntity->getId();
                        }
                    }
                }
            }
        }


        if ($restDto->getSugestao()
            && !$restDto->getDataHoraAprovacaoSugestao()
            && $entity->getEtiqueta()->getAcoes()->count() > 0) {
            $context['pendencias'] =  true;
        }

        if (count($context) > 0) {
            $restDto->setObjectContext(json_encode($context));
        }
    }

    public function getOrder(): int
    {
        return 0001;
    }
}

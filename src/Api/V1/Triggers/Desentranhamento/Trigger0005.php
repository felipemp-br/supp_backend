<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Desentranhamento/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Criar o termo de desentranhamento do processo
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        private JuntadaResource $juntadaResource,
        private TipoDocumentoResource $tipoDocumentoResource,
        private DocumentoResource $documentoResource,
        private Environment $twig,
        private TokenStorageInterface $tokenStorage,
        private ComponenteDigitalResource $componenteDigitalResource
    ) {
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $idMax = 0;
        foreach ($restDto->getJuntadasBloco()->toArray() as $juntada) {
            if ($juntada->getId() > $idMax) {
                $idMax = $juntada->getId();
            }
        }

        if ($restDto->getJuntada()->getId() !== $idMax) {
            return;
        }

        $processoAnterior = $restDto->getJuntada()->getVolume()->getProcesso();
        $documentoDto = new DocumentoDTO();
        $documentoDto->setMinuta(false);

        $documentoDto->setProcessoOrigem($processoAnterior);
        $documentoDto->setSetorOrigem($processoAnterior->getSetorAtual());

        $documentoDto->setTipoDocumento(
            $this
                ->tipoDocumentoResource
                ->getRepository()
                ->findByNomeAndEspecie('OUTROS', 'ADMINISTRATIVO')
        );

        $documentoEntity = $this
            ->documentoResource
            ->create($documentoDto, $transactionId);

        $responsavel = 'SISTEMA';
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            $responsavel = $this->tokenStorage->getToken()->getUser()->getNome();
        }

        $template = 'Resources/Desentranhamento/relatorio.html.twig';
        $conteudoHTML = $this->twig->render(
            $template,
            [
                'nup' => $processoAnterior->getNUPFormatado(),
                'responsavel' => $responsavel,
                'juntadas' => $restDto->getJuntadasBloco(),
                'setor' => $processoAnterior->getSetorAtual()->getNome() .
                            '/' . $processoAnterior->getSetorAtual()->getUnidade()->getSigla(),
                'motivo' => $restDto->getObservacao(),
            ]
        );

        // cria o componente Digital
        $componenteDigitalDTO = new ComponenteDigital();
        $componenteDigitalDTO->setConteudo($conteudoHTML);
        $componenteDigitalDTO->setMimetype('text/html');
        $componenteDigitalDTO->setExtensao('html');
        $componenteDigitalDTO->setEditavel(false);
        $componenteDigitalDTO->setFileName('termo_desentranhamento.html');
        $componenteDigitalDTO->setTamanho(strlen($conteudoHTML));
        $componenteDigitalDTO->setNivelComposicao(3);
        $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setTamanho(strlen($componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setDocumento($documentoEntity);

        $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

        $juntadaDTO = new Juntada();
        $juntadaDTO->setDescricao('JUNTADA DO TERMO DE DESENTRANHAMENTO');
        $juntadaDTO->setDocumento($documentoEntity);
        $this->juntadaResource->create($juntadaDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}

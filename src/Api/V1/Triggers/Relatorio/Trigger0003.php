<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relatorio/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelatorioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Seta os favoritos do relatório criado!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    private SetorRepository $setorRepository;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        TransactionManager $transactionManager,
        SetorRepository $setorRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->transactionManager = $transactionManager;
        $this->setorRepository = $setorRepository;
    }

    public function supports(): array
    {
        return [
            Relatorio::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Relatorio|RestDtoInterface|null $restDto
     * @param RelatorioEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $blocoFavoritos = [];
        
        if($restDto->getTipoRelatorio()->getEspecieRelatorio()->getGeneroRelatorio()) {
            $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getTipoRelatorio()->getEspecieRelatorio()->getGeneroRelatorio()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getTipoRelatorio()->getEspecieRelatorio()->getGeneroRelatorio())),
                'context' => 'relatorio_genero',
            ];
        }

        if($restDto->getTipoRelatorio()->getEspecieRelatorio()) {
            $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getTipoRelatorio()->getEspecieRelatorio()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getTipoRelatorio()->getEspecieRelatorio())),
                'context' => 'relatorio_especie_genero_' . $restDto->getTipoRelatorio()->getEspecieRelatorio()->getGeneroRelatorio()->getId(),
            ];
        }

        if($restDto->getTipoRelatorio()) {
            $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getTipoRelatorio()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getTipoRelatorio())),
                'context' => 'relatorio_tipo_especie_' . $restDto->getTipoRelatorio()->getEspecieRelatorio()->getId(),
            ];
        }
        
        if($restDto->getParametrosAsArray()) {
            if(isset($restDto->getParametrosAsArray()['setor'])){
                $setor = $this->setorRepository->find($restDto->getParametrosAsArray()['setor']['value']);
                $unidade = $setor->getUnidade();

                $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $unidade->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($unidade)),
                    'context' => 'relatorio_unidade',
                ];

                $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $setor->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($setor)),
                    'context' => 'relatorio_setor_unidade_' . $unidade->getId(),
                ];
            }
            
        }

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos)), $transactionId);
    }

    public function getOrder(): int
    {
        return 1000;
    }
}

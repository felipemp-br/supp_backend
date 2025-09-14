<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Counters/Tarefa/Counter0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Counters\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Counter\CounterInterface;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\Repository\FolderRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Counter0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Counter0001 implements CounterInterface
{
    private GeneroTarefaResource $generoTarefaResource;
    protected TokenStorageInterface $tokenStorage;
    private FolderRepository $folderRepository;

    /**
     * Counter0001 constructor.
     */
    public function __construct(
        GeneroTarefaResource $generoTarefaResource,
        TokenStorageInterface $tokenStorage,
        FolderRepository $folderRepository
    ) {
        $this->generoTarefaResource = $generoTarefaResource;
        $this->tokenStorage = $tokenStorage;
        $this->folderRepository = $folderRepository;
    }

    /**
     * @return PushMessage[]
     */
    public function getMessages(): array
    {
        $messages = [];

        $pushMessage = new PushMessage();
        $pushMessage->setIdentifier('tarefas_pendentes_eventos');
        $pushMessage->setChannel(
            $this->tokenStorage->getToken()->getUser()->getUserIdentifier()
        );
        $pushMessage->setResource(
            TarefaResource::class
        );
        $pushMessage->setCriteria(
            [
                'usuarioResponsavel.username' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
                'dataHoraConclusaoPrazo' => 'isNull',
            ]
        );
        $messages[] = $pushMessage;

        foreach ($this->generoTarefaResource->getRepository()->findAll() as $generoTarefa) {
            // Recupera a quantidade das tarefas da caixa de entrada
            $pushMessage = new PushMessage();
            $pushMessage->setIdentifier('caixa_entrada_'.mb_strtolower($generoTarefa->getNome()));
            $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
            $pushMessage->setResource(TarefaResource::class);
            $pushMessage->setCriteria(
                [
                    'usuarioResponsavel.username' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
                    'dataHoraConclusaoPrazo' => 'isNull',
                    'folder' => 'isNull',
                    'especieTarefa.generoTarefa.id' => 'eq:'.$generoTarefa->getId(),
                ]
            );
            $messages[] = $pushMessage;

            // Recupera a quantidade das tarefas da lixeira
            $data = new \DateTime();
            $pushMessage = new PushMessage();
            $pushMessage->setIdentifier('lixeira_'.mb_strtolower($generoTarefa->getNome()));
            $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
            $pushMessage->setResource(TarefaResource::class);
            $pushMessage->setDesabilitaSoftDeleteable(true);
            $pushMessage->setCriteria(
                [
                    'usuarioResponsavel.username' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
                    'dataHoraConclusaoPrazo' => 'isNull',
                    'apagadoEm' => 'gt:'.$data->modify('-10 days')->format('Y-m-d\TH:i:s'),
                    'especieTarefa.generoTarefa.id' => 'eq:'.$generoTarefa->getId(),
                ]
            );
            $messages[] = $pushMessage;

            // Recupera a quantidade das tarefas compartilhadas para mim
            $pushMessage = new PushMessage();
            $pushMessage->setIdentifier('tarefas_compartilhadas_'.mb_strtolower($generoTarefa->getNome()));
            $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
            $pushMessage->setResource(TarefaResource::class);
            $pushMessage->setCriteria(
                [
                    'compartilhamentos.usuario.id' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getId(),
                    'dataHoraConclusaoPrazo' => 'isNull',
                    'especieTarefa.generoTarefa.id' => 'eq:'.$generoTarefa->getId(),
                ]
            );
            $messages[] = $pushMessage;

            $folders = $this->folderRepository->findTarefaByUsuarioId(
                $this->tokenStorage->getToken()->getUser()->getId()
            );

            // Recupera a quantidade das tarefas dos folders
            foreach ($folders as $folder) {
                $pushMessage = new PushMessage();
                $pushMessage->setIdentifier(
                    'folder_'.mb_strtolower($generoTarefa->getNome()).'_'.mb_strtolower($folder->getNome())
                );
                $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
                $pushMessage->setResource(TarefaResource::class);
                $pushMessage->setCriteria(
                    [
                        'folder.id' => 'eq:'.$folder->getId(),
                        'usuarioResponsavel.id' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getId(),
                        'dataHoraConclusaoPrazo' => 'isNull',
                        'especieTarefa.generoTarefa.id' => 'eq:'.$generoTarefa->getId(),
                    ]
                );
                $messages[] = $pushMessage;
            }

            $pushMessage = new PushMessage();
            $pushMessage->setIdentifier('tarefas_pendentes_'.mb_strtolower($generoTarefa->getNome()));
            $pushMessage->setChannel(
                $this->tokenStorage->getToken()->getUser()->getUserIdentifier()
            );
            $pushMessage->setResource(
                TarefaResource::class
            );
            $pushMessage->setCriteria(
                [
                    'especieTarefa.generoTarefa.nome' => 'eq:'.$generoTarefa->getNome(),
                    'usuarioResponsavel.username' => 'eq:'.$this->tokenStorage->getToken()->getUser()->getUserIdentifier(),
                    'dataHoraConclusaoPrazo' => 'isNull',
                ]
            );
            $messages[] = $pushMessage;

            // Recupera a quantidade das tarefas de setores de coordenação
            foreach ($this->tokenStorage->getToken()->getUser()->getCoordenadores() as $coordenacao) {
                if ($coordenacao->getSetor()) {
                    $pushMessage = new PushMessage();
                    $pushMessage->setIdentifier(
                        'tarefas_coordenadas_'
                        .$coordenacao->getSetor()->getId().'_'.mb_strtolower($generoTarefa->getNome())
                    );
                    $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUsername());
                    $pushMessage->setResource(TarefaResource::class);
                    $pushMessage->setCriteria(
                        [
                            'setorResponsavel.id' => 'eq:'.$coordenacao->getSetor()->getId(),
                            'dataHoraConclusaoPrazo' => 'isNull',
                            'especieTarefa.generoTarefa.id' => 'eq:'.$generoTarefa->getId(),
                        ]
                    );
                    $messages[] = $pushMessage;
                }
            }
        }

        return $messages;
    }

    public function getOrder(): int
    {
        return 1;
    }
}

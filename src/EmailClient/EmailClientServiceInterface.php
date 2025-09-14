<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EmailClient;

use SuppCore\AdministrativoBackend\Entity\ContaEmail;

/**
 * Interface EmailClientServiceInterface.
 */
interface EmailClientServiceInterface
{
    public const FOLDER_INBOX = 'Caixa de Entrada';
    public const FOLDER_SPAM = 'Spam';
    public const FOLDER_SENT = 'Enviados';
    public const FOLDER_TRASH = 'Lixeira';
    public const FOLDER_DRAFT = 'Rascunhos';

    public const DEFAULT_FOLDERS =
        self::FOLDER_INBOX
        .'|'.self::FOLDER_SPAM
        .'|'.self::FOLDER_SENT
        .'|'.self::FOLDER_TRASH
        .'|'.self::FOLDER_DRAFT;

    public const BLACKLIST_FOLDERS = 'Tarefas|Journal|Histórico de Conversa|Contatos|Calendário|Anotações|[Gmail]|Com estrela';

    public function setConfigs(array $configs = []): void;

    /**
     * @throws ConnectionException
     */
    public function testConnection(ContaEmail $contaEmail): bool;

    /**
     * @return Folder[]
     */
    public function getDefaultFolders(ContaEmail $contaEmail): array;

    /**
     * @return Folder[]
     */
    public function getFolders(ContaEmail $contaEmail, int $limit = 10, int $offset = 0): array;

    public function getInboxFolder(ContaEmail $contaEmail): ?Folder;

    public function getFolder(ContaEmail $contaEmail, string $idenfifier): ?Folder;

    public function getMessages(
        ContaEmail $contaEmail,
        Folder $folder,
        int $limit = 10,
        int $offset = 0,
        bool $withAttachments = false
    ): array;

    public function searchMessages(
        ContaEmail $contaEmail,
        array $criterias,
        int $limit = 10,
        int $offset = 0,
        bool $withAttachments = false
    ): array;

    public function getMessage(
        ContaEmail $contaEmail,
        string|int $folderIdentifier,
        string|int $messageIdentifier,
        bool $withAttachments = false
    ): ?Message;

    /**
     * @return Attachment[]
     */
    public function getAttachments(
        ContaEmail $contaEmail,
        string|int $folderIdentifier,
        string|int $messageIdentifier
    ): array;

    public function getAttachment(
        ContaEmail $contaEmail,
        string|int $folderIdentifier,
        string|int $messageIdentifier,
        string|int $attachmentIdentifier
    ): ?Attachment;
}

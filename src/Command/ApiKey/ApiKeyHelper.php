<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/ApiKeyHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Closure;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRoleResource;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole as VinculacaoRoleEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

use function array_map;
use function sprintf;

/**
 * Class ApiKeyHelper.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class ApiKeyHelper
{
    public function __construct(
        private ApiKeyResource $apiKeyResource,
        private VinculacaoRoleResource $vinculacaoRoleResource,
    ) {
    }

    /**
     * Method to get API key entity. Also note that this may return a null in cases that user do not want to make any
     * changes to API keys.
     *
     * @param SymfonyStyle $io
     * @param string       $question
     *
     * @return ApiKeyEntity|null
     */
    public function getApiKey(SymfonyStyle $io, string $question): ?ApiKeyEntity
    {
        $apiKeyFound = false;

        while (true !== $apiKeyFound) {
            /** @var ApiKeyEntity|null $apiKeyEntity */
            $apiKeyEntity = $this->getApiKeyEntity($io, $question);

            if (null === $apiKeyEntity) {
                break;
            }

            $message = sprintf(
                'Esta é a API key correta \'[%s] [%s] %s\'?',
                $apiKeyEntity->getId(),
                $apiKeyEntity->getToken(),
                $apiKeyEntity->getNome()
            );

            $apiKeyFound = (bool) $io->confirm($message, true);
        }

        return $apiKeyEntity ?? null;
    }

    /**
     * Helper method to get "normalized" message for API key. This is used on following cases:
     *  - User changes API key token
     *  - User creates new API key
     *  - User modifies API key
     *  - User removes API key.
     *
     * @param string       $message
     * @param ApiKeyEntity $apiKey
     *
     * @return mixed[]
     */
    public function getApiKeyMessage(string $message, ApiKeyEntity $apiKey): array
    {
        return [
            $message,
            sprintf(
                "Id:  %s\nToken: %s",
                $apiKey->getId(),
                $apiKey->getToken()
            ),
        ];
    }

    /**
     * Method to list ApiKeys where user can select desired one.
     *
     * @param SymfonyStyle $io
     * @param string       $question
     *
     * @return ApiKeyEntity|EntityInterface|null
     */
    private function getApiKeyEntity(SymfonyStyle $io, string $question): ?EntityInterface
    {
        $choices = [];
        $iterator = $this->getApiKeyIterator($choices);

        array_map(
            $iterator,
            $this->apiKeyResource->getRepository()->findBy(
                ['ativo' => true, 'apagadoEm' => null],
                ['id' => 'ASC'],
            )
        );

        $choices['Exit'] = 'Exit command';

        $choice = $io->choice($question, $choices);

        if ('Exit' === $choice) {
            exit(Command::SUCCESS);
        }

        return $this->apiKeyResource->findOne((int) $choice);
    }

    /**
     * Method to return ApiKeyIterator closure. This will format ApiKey entities for choice list.
     *
     * @param string[] $choices
     *
     * @return Closure
     */
    private function getApiKeyIterator(array &$choices): Closure
    {
        /*
         * Lambda function create api key choices
         *
         * @param ApiKeyEntity $apiKey
         */
        return function (ApiKeyEntity $apiKey) use (&$choices): void {
            $value = sprintf(
                '%s - %s',
                $apiKey->getNome(),
                $apiKey->getToken()
            );

            $choices[$apiKey->getId()] = $value;
        };
    }

    public function getVinculacaoRoleEntity(SymfonyStyle $io, string $question, int $apiKeyId): ?EntityInterface
    {
        $choices = [];
        $iterator = $this->getVinculacaoRoleIterator($choices);

        array_map(
            $iterator,
            $this->vinculacaoRoleResource->getRepository()->findBy(
                ['apiKey' => $apiKeyId, 'apagadoEm' => null],
                ['id' => 'ASC']
            )
        );

        $choices['Exit'] = 'Exit command';

        $choice = $io->choice($question, $choices);

        if ('Exit' === $choice) {
            exit(Command::SUCCESS);
        }

        return $this->vinculacaoRoleResource->findOne((int) $choice);
    }

    private function getVinculacaoRoleIterator(array &$choices): Closure
    {
        return function (VinculacaoRoleEntity $vinculacaoRole) use (&$choices): void {
            $value = sprintf(
                '%s',
                $vinculacaoRole->getRole()
            );

            $choices[$vinculacaoRole->getId()] = $value;
        };
    }
}

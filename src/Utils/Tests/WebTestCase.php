<?php

declare(strict_types=1);
/**
 * /src/Utils/Tests/WebTestCase.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils\Tests;

use function array_merge;
use function gc_collect_cycles;
use function gc_enable;
use function getenv;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class WebTestCase.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class WebTestCase extends BaseWebTestCase
{
    private Auth $authService;

    /**
     * @codeCoverageIgnore
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        gc_enable();
    }

    /**
     * @codeCoverageIgnore
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        gc_collect_cycles();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        /** @var Auth $authService */
        $authService = self::getContainer()->get(Auth::class);

        $this->authService = $authService;
    }

    /**
     * Helper method to get authorized client for specified username and password.
     *
     * @param string|null  $username
     * @param string|null  $password
     * @param array[]|null $options
     * @param array[]|null $server
     *
     * @return KernelBrowser
     *
     * @throws Throwable
     */
    public function getTestClient(
        ?string $username = null,
        ?string $password = null,
        ?array $options = null,
        ?array $server = null
    ): KernelBrowser {
        $options ??= [];
        $server ??= [];

        // Merge authorization headers
        $server = array_merge(
            null === $username || null === $password
            ? []
                : $this->authService->getAuthorizationHeadersForUser($username, $password),
            array_merge($this->getJsonHeaders(), $this->getFastestHeaders()),
            $this->authService->getJwtHeaders(),
            $server
        );

        self::ensureKernelShutdown();

        return static::createClient(array_merge($options, ['debug' => false]), $server);
    }

    /**
     * Helper method to get authorized API Key client for specified role.
     *
     * @param string|null  $role
     * @param array[]|null $options
     * @param array[]|null $server
     *
     * @return KernelBrowser
     */
    public function getApiKeyClient(?string $role = null, ?array $options = null, ?array $server = null): KernelBrowser
    {
        $options ??= [];
        $server ??= [];

        // Merge authorization headers
        $server = array_merge(
            null === $role ? [] : $this->authService->getAuthorizationHeadersForApiKey($role),
            array_merge($this->getJsonHeaders(), $this->getFastestHeaders()),
            $this->authService->getJwtHeaders(),
            $server
        );

        self::ensureKernelShutdown();

        return static::createClient($options, $server);
    }

    /**
     * @return array[]
     */
    public function getJsonHeaders(): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ];
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array[]
     */
    public function getFastestHeaders(): array
    {
        $output = [];

        if (getenv('ENV_TEST_CHANNEL_READABLE')) {
            $output = [
                'X-FASTEST-ENV-TEST-CHANNEL-READABLE' => getenv('ENV_TEST_CHANNEL_READABLE'),
            ];
        }

        return $output;
    }

    /**
     * @param string  $url
     * @param ?string $username
     * @param ?string $pass
     * @param string  $method
     * @param array   $data
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function basicRequest(string $url, string $method, ?string $username, ?string $pass, array $data): Response
    {
        $dados = json_encode($data);
        $client = $this->getTestClient($username, $pass);
        $client->request(
            $method,
            $url,
            [],
            [],
            [],
            $dados
        );

        return $client->getResponse();
    }
}

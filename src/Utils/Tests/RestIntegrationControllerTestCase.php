<?php

declare(strict_types=1);
/**
 * /src/Utils/Tests/RestIntegrationControllerTestCase.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils\Tests;

use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function mb_substr;
use function sprintf;

/**
 * Class RestIntegrationControllerTestCase.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class RestIntegrationControllerTestCase extends KernelTestCase
{
    protected mixed $controller;

    protected string $controllerClass;

    protected string $resourceClass;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = parent::getContainer()->get($this->controllerClass);
    }

    /**
     * @throws ReflectionException
     */
    public function testThatGivenControllerIsCorrect(): void
    {
        $expected = mb_substr((new ReflectionClass($this))->getShortName(), 0, -4);

        $message = sprintf(
            'Your REST controller integration test \'%s\' uses likely wrong controller class \'%s\'',
            static::class,
            $this->controllerClass
        );

        static::assertSame($expected, (new ReflectionClass($this->controller))->getShortName(), $message);
    }

    /**
     * This test is to make sure that controller has set the expected resource. There is multiple resources and each
     * controller needs to use specified one.
     */
    public function testThatGetResourceReturnsExpected(): void
    {
        static::assertInstanceOf($this->resourceClass, $this->controller->getResource());
    }
}

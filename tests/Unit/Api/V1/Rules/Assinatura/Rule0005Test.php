<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assinatura/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura\Rule0005;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|AssinaturaDto $assinaturaDto;

    private MockObject|AssinaturaEntity $assinaturaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assinaturaDto = $this->createMock(AssinaturaDto::class);
        $this->assinaturaEntity = $this->createMock(AssinaturaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate
        );
    }

    /**
     * @throws RuleException
     */
    public function testChavePublicaValida(): void
    {
        $this->assinaturaDto->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn(<<<EOF
                            -----BEGIN PUBLIC KEY-----
                            MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA6v6hcoF/J3xdsaudA6W8
                            KnIDHwdK2UrUPoMVJqjfefz9qX3K+dfjePYuS2qPBceR7rwfeRsJx8BsW6WuQYQ/
                            oybflWmmeWg+QAf7/FflT1lhXwRzHro85lNAtfNMYB1FMa7BAGPrDo8UK7Bk2pgE
                            XtJ8Zb2b1ZTwlO7Up8iQWEkjufj9u1lAWrTUA3SoWSG8GsrdYb2HEeX0OM1bMNWL
                            Akse0+BXMijbFHfsgzdhiSPKx16tYMD7xuEN5cwSTw8V7QkJ0qL6VzDSlfQNf0zT
                            9mN4OLa7vGOvR6XmYShE6t4H3TyBgX84ZnlemwzWtfHVRPqPo2/YScvUtQpG2ltY
                            UBiCjvxzvR68kJAByQ8yBrAvTdNf+k78PadpaUtjUTuLF5l11Fw/FRwyPDh+73g+
                            89FrqeejoxlqCUgC+C/QfM8HTro5Zpu/RwhAxmHGs1pZiEBNYIbgeY8041QPa9Bh
                            bqlFtXG79GTBlSkIzm6ikrb5TXNILi7EsrIYze7YWSuu6KyT4MNa2Ob0N/m3AnpR
                            oMI9Syc3k+/4YpY6xrv2I52KiJExgxf6pZSWGrrxGJV+7ahHkrFBadhAfDy052gD
                            lOI41n+Cm58spSwjCHfkecDsiLJ5CQsSSle1EFe28mEnQfwiS/0V1idZnKdQ8IMc
                            MAwPP4EC06LnBtqy4PjBRv8CAwEAAQ==
                            -----END PUBLIC KEY-----
                            -----BEGIN CERTIFICATE-----
                            MIICFTCCAX4CAgECMA0GCSqGSIb3DQEBBAUAMFcxCzAJBgNVBAYTAlVTMRMwEQYD
                            VQQKEwpSVEZNLCBJbmMuMRkwFwYDVQQLExBXaWRnZXRzIERpdmlzaW9uMRgwFgYD
                            VQQDEw9UZXN0IENBMjAwMTA1MTcwHhcNMDEwNTE3MTYxMTM2WhcNMDQwMzA2MTYx
                            FIDCfinyz24m
                            -----END CERTIFICATE-----
                            EOF);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction'));
    }

    public function testChavePublicaInvalida(): void
    {
        $this->assinaturaDto->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn(<<<EOF
                            -----BEGIN PUBLIC KEY-----
                            MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA6v6hcoF/J3xdsaudA6W8
                            KnIDHwdK2UrUPoMVJqjfefz9qX3K+dfjePYuS2qPBceR7rwfeRsJx8BsW6WuQYQ/
                            -----END PUBLIC KEY-----
                            -----BEGIN CERTIFICATE-----
                            MIICFTCCAX4CAgECMA0GCSqGSIb3DQEBBAUAMFcxCzAJBgNVBAYTAlVTMRMwEQYD
                            VQQKEwpSVEZNLCBJbmMuMRkwFwYDVQQLExBXaWRnZXRzIERpdmlzaW9uMRgwFgYD
                            VQQDEw9UZXN0IENBMjAwMTA1MTcwHhcNMDEwNTE3MTYxMTM2WhcNMDQwMzA2MTYx
                            FIDCfinyz24m
                            -----END CERTIFICATE-----
                            EOF);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction');
    }
}

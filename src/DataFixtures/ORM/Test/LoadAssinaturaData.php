<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAssinaturaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAssinaturaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAssinaturaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $assinatura = new Assinatura();
        $assinatura->setAssinatura(<<<EOD
MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID
vDCCAqQCFBFyv9BYEmfgSW3DeBjJIuXAzpTxMA0GCSqGSIb3DQEBCwUAMIGcMQswCQYDVQQGEwJC
UjELMAkGA1UECAwCUlMxFTATBgNVBAcMDFBPUlRPIEFMRUdSRTEhMB8GA1UECgwYQURWT0NBQ0lB
LUdFUkFMIERBIFVOSUFPMSswKQYDVQQLDCJERVBBUlRBTUVOVE8gREUgR0VTVEFPIEVTVFJBVEVH
SUNBMRkwFwYDVQQDDBBBQyBJTlRFUk1FRElBUklBMCAXDTIyMDMyMjE3MjQzNVoYDzIxMjIwMjI2
MTcyNDM1WjCBlTELMAkGA1UEBhMCQlIxCzAJBgNVBAgMAlJTMRUwEwYDVQQHDAxQT1JUTyBBTEVH
UkUxITAfBgNVBAoMGEFEVk9DQUNJQS1HRVJBTCBEQSBVTklBTzErMCkGA1UECwwiREVQQVJUQU1F
TlRPIERFIEdFU1RBTyBFU1RSQVRFR0lDQTESMBAGA1UEAwwJQVNTSU5BRE9SMIIBIjANBgkqhkiG
9w0BAQEFAAOCAQ8AMIIBCgKCAQEArJLU3eT60ssujVBQgMZnZJN9uyyTRR0rNycZNAFcrYrAOflV
BDig1l8P3t4CgCWDGKjNnNZ5l0oC1Iwb/MqY3aW5TxJW63R2nC1Uxqh+mAyali4gTJLNdT15gy3o
rS05X5/tnXM8/BiqU4gapBRX2TuSoZ2rw4v97/hF7e3rtsnWNzWLKNetWSRFT5S8xmCXJ0vLLz8J
nFsVV/i+61dxGwRDmbCDVGvCgNDkd2yitiAY9JANHYeYfCwcQiCHnVGrnUuYTfZY5WDZZI/58N1e
c25jWjxjbWXY72DdqSqPx3DFR0TmCgQiXGRoz9JrBHl4YD9/EgWzRrURFRs1Y/nWBwIDAQABMA0G
CSqGSIb3DQEBCwUAA4IBAQArCN/xoCoN8OOjjoxNbfxMuu3c4Zv2y4UKG40qaPxOgpWfZXjU+u7Y
G02rKSwIWe3O8ZJ7YRfO6LjFGlCzu0fjH7W2JVP0YJuQWzJ2BaRyT2SInHYhdwtVlgx3vECAkYN+
KWAJC5v/PJ/jBPuawvJ0lJoIoeReDnSR3k5ss4thDvLKXoHEMhvTeY5QT08mZG/3pUOR3VDWlREb
J/8l4bmYsbTp+woQBYwslppp/6yWeIkDzF1FASrMASFzHJPig6FpgQn87//fQtCl0xA9SfxLpIit
KG+XwRgVDzI5c6pgL1+aIMqALYfSfIY6KyCjiWeiOR8oBTC3H+ycYbJGH20RAAAxggQQMIIEDAIB
ATCBtTCBnDELMAkGA1UEBhMCQlIxCzAJBgNVBAgMAlJTMRUwEwYDVQQHDAxQT1JUTyBBTEVHUkUx
ITAfBgNVBAoMGEFEVk9DQUNJQS1HRVJBTCBEQSBVTklBTzErMCkGA1UECwwiREVQQVJUQU1FTlRP
IERFIEdFU1RBTyBFU1RSQVRFR0lDQTEZMBcGA1UEAwwQQUMgSU5URVJNRURJQVJJQQIUEXK/0FgS
Z+BJbcN4GMki5cDOlPEwDQYJYIZIAWUDBAIBBQCgggIrMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0B
BwEwHAYJKoZIhvcNAQkFMQ8XDTIyMDMzMTIwMzQxMVowLQYJKoZIhvcNAQk0MSAwHjANBglghkgB
ZQMEAgEFAKENBgkqhkiG9w0BAQsFADAvBgkqhkiG9w0BCQQxIgQgduuaJo99+rvQApY0pfJ/i/Yj
zsJRCI7Rs++6XM3wpmEwgZQGCyqGSIb3DQEJEAIPMYGEMIGBBghgTAEHAQECAzAvMAsGCWCGSAFl
AwQCAQQgsW6Iu/dzIqZ5lbeQeHeO09DqfIhYe29tUYtxXo92o9UwRDBCBgsqhkiG9w0BCRAFARYz
aHR0cDovL3BvbGl0aWNhcy5pY3BicmFzaWwuZ292LmJyL1BBX0FEX1JCX3YyXzMuZGVyMIH5Bgsq
hkiG9w0BCRACLzGB6TCB5jCB4zCB4AQgkNK/JMK9NpbMxqHej6SyQI87BSP/tgG6t6fCZUf3ooMw
gbswgaKkgZ8wgZwxCzAJBgNVBAYTAkJSMQswCQYDVQQIDAJSUzEVMBMGA1UEBwwMUE9SVE8gQUxF
R1JFMSEwHwYDVQQKDBhBRFZPQ0FDSUEtR0VSQUwgREEgVU5JQU8xKzApBgNVBAsMIkRFUEFSVEFN
RU5UTyBERSBHRVNUQU8gRVNUUkFURUdJQ0ExGTAXBgNVBAMMEEFDIElOVEVSTUVESUFSSUECFBFy
v9BYEmfgSW3DeBjJIuXAzpTxMA0GCSqGSIb3DQEBCwUABIIBADjxT78Qb4TwpiPPGdNKUpjeRd3f
rmeUyU+a9i/xcbuKdv2MQHJ9/6abHsBe6zCPMIMNf0+1QxWHq0PJUoZmyMLVOC4Zmyw85AUcppJh
P81gV52j65o/LVGklBUpTbuPNlIZJhAPlvUX9LmW2fiOXibxQ0RYqBmVmbrNjCf3fjBNeV/okH1f
+zclXwYuuks/HIbCpCSyFisIp+GB/6ifBeaGCGmv3ksNahPoTUlRQrGMV4QxWZsPdaMyBKClXg4A
/xeEUMUyVNv/ebFHgBlpGYxaRgvu6xjcOyToE3gXA8Ccm7JsHOLbcIDftis6g4pLE05DU9Rr8isQ
PDz8HewV2OQAAAAAAAA=
EOD
        );
        $assinatura->setCadeiaCertificadoPEM('TESTE');
        $assinatura->setComponenteDigital(
            $this->getReference('ComponenteDigital-TEMPLATE DESPACHO', ComponenteDigital::class)
        );
        $assinatura->setDataHoraAssinatura(DateTime::createFromFormat('Y-m-d', '2021-12-05'));
        $assinatura->setAlgoritmoHash('SHA256');
        $assinatura->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($assinatura);

        $assinatura = new Assinatura();
        $assinatura->setAssinatura(<<<EOD
MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID
vDCCAqQCFBFyv9BYEmfgSW3DeBjJIuXAzpTxMA0GCSqGSIb3DQEBCwUAMIGcMQswCQYDVQQGEwJC
EOD
        );
        $assinatura->setCadeiaCertificadoPEM('cadeia_teste');
        $assinatura->setComponenteDigital(
            $this->getReference('ComponenteDigital-MODELO DESPACHO EM BRANCO', ComponenteDigital::class)
        );
        $assinatura->setDataHoraAssinatura(DateTime::createFromFormat('Y-m-d', '2022-19-08'));
        $assinatura->setAlgoritmoHash('SHA256');
        $assinatura->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($assinatura);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 6;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}

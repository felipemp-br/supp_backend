<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadPaisData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Pais;

/**
 * Class LoadPaisData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadPaisData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pais = new Pais();
        $pais->setCodigo('AD');
        $pais->setNome('ANDORRA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AE');
        $pais->setNome('UNITED ARAB EMIRATES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AF');
        $pais->setNome('AFGHANISTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AG');
        $pais->setNome('ANTIGUA AND BARBUDA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AI');
        $pais->setNome('ANGUILLA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AL');
        $pais->setNome('ALBANIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AM');
        $pais->setNome('ARMENIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AO');
        $pais->setNome('ANGOLA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AQ');
        $pais->setNome('ANTARCTICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AR');
        $pais->setNome('ARGENTINA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AS');
        $pais->setNome('AMERICAN SAMOA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AT');
        $pais->setNome('AUSTRIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AU');
        $pais->setNome('AUSTRALIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AW');
        $pais->setNome('ARUBA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AX');
        $pais->setNome('Ã…LAND ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AZ');
        $pais->setNome('AZERBAIJAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BA');
        $pais->setNome('BOSNIA AND HERZEGOVINA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BB');
        $pais->setNome('BARBADOS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BD');
        $pais->setNome('BANGLADESH');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BE');
        $pais->setNome('BELGIUM');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BF');
        $pais->setNome('BURKINA FASO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BG');
        $pais->setNome('BULGARIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BH');
        $pais->setNome('BAHRAIN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BI');
        $pais->setNome('BURUNDI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BJ');
        $pais->setNome('BENIN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BL');
        $pais->setNome('SAINT BARTHÃ©LEMY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BM');
        $pais->setNome('BERMUDA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BN');
        $pais->setNome('BRUNEI DARUSSALAM');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BO');
        $pais->setNome('BOLIVIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BQ');
        $pais->setNome('BONAIRE, SINT EUSTATIUS AND SABA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BR');
        $pais->setNome('BRAZIL');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BS');
        $pais->setNome('BAHAMAS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BT');
        $pais->setNome('BHUTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BV');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $pais->setNome('BOUVET ISLAND (BOUVETOYA)');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BW');
        $pais->setNome('BOTSWANA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BY');
        $pais->setNome('BELARUS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BZ');
        $pais->setNome('BELIZE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CA');
        $pais->setNome('CANADA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CC');
        $pais->setNome('COCOS (KEELING) ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CD');
        $pais->setNome('CONGO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CF');
        $pais->setNome('CENTRAL AFRICAN REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CG');
        $pais->setNome('CONGO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CH');
        $pais->setNome('SWITZERLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CI');
        $pais->setNome('COTE D\'IVOIRE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CK');
        $pais->setNome('COOK ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CL');
        $pais->setNome('CHILE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CM');
        $pais->setNome('CAMEROON');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CN');
        $pais->setNome('CHINA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CO');
        $pais->setNome('COLOMBIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CR');
        $pais->setNome('COSTA RICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CU');
        $pais->setNome('CUBA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CV');
        $pais->setNome('CAPE VERDE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CW');
        $pais->setNome('CURAÃ§AO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CX');
        $pais->setNome('CHRISTMAS ISLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CY');
        $pais->setNome('CYPRUS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CZ');
        $pais->setNome('CZECH REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DE');
        $pais->setNome('GERMANY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DJ');
        $pais->setNome('DJIBOUTI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DK');
        $pais->setNome('DENMARK');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DM');
        $pais->setNome('DOMINICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DO');
        $pais->setNome('DOMINICAN REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DZ');
        $pais->setNome('ALGERIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EC');
        $pais->setNome('ECUADOR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EE');
        $pais->setNome('ESTONIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EG');
        $pais->setNome('EGYPT');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EH');
        $pais->setNome('WESTERN SAHARA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ER');
        $pais->setNome('ERITREA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ES');
        $pais->setNome('SPAIN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ET');
        $pais->setNome('ETHIOPIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FI');
        $pais->setNome('FINLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FJ');
        $pais->setNome('FIJI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FK');
        $pais->setNome('FALKLAND ISLANDS (MALVINAS)');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FM');
        $pais->setNome('MICRONESIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FO');
        $pais->setNome('FAROE ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FR');
        $pais->setNome('FRANCE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GA');
        $pais->setNome('GABON');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GB');
        $pais->setNome('UNITED KINGDOM OF GREAT BRITAIN & NORTHERN IRELAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GD');
        $pais->setNome('GRENADA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GE');
        $pais->setNome('GEORGIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GF');
        $pais->setNome('FRENCH GUIANA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GG');
        $pais->setNome('GUERNSEY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GH');
        $pais->setNome('GHANA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GI');
        $pais->setNome('GIBRALTAR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GL');
        $pais->setNome('GREENLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GM');
        $pais->setNome('GAMBIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GN');
        $pais->setNome('GUINEA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GP');
        $pais->setNome('GUADELOUPE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GQ');
        $pais->setNome('EQUATORIAL GUINEA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GR');
        $pais->setNome('GREECE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GS');
        $pais->setNome('SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GT');
        $pais->setNome('GUATEMALA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GU');
        $pais->setNome('GUAM');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GW');
        $pais->setNome('GUINEA-BISSAU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GY');
        $pais->setNome('GUYANA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HK');
        $pais->setNome('HONG KONG');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HM');
        $pais->setNome('HEARD ISLAND AND MCDONALD ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HN');
        $pais->setNome('HONDURAS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HR');
        $pais->setNome('CROATIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HT');
        $pais->setNome('HAITI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HU');
        $pais->setNome('HUNGARY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ID');
        $pais->setNome('INDONESIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IE');
        $pais->setNome('IRELAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IL');
        $pais->setNome('ISRAEL');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IM');
        $pais->setNome('ISLE OF MAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IN');
        $pais->setNome('INDIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IO');
        $pais->setNome('BRITISH INDIAN OCEAN TERRITORY (CHAGOS ARCHIPELAGO)');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IQ');
        $pais->setNome('IRAQ');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IR');
        $pais->setNome('IRAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IS');
        $pais->setNome('ICELAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IT');
        $pais->setNome('ITALY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JE');
        $pais->setNome('JERSEY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JM');
        $pais->setNome('JAMAICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JO');
        $pais->setNome('JORDAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JP');
        $pais->setNome('JAPAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KE');
        $pais->setNome('KENYA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KG');
        $pais->setNome('KYRGYZ REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KH');
        $pais->setNome('CAMBODIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KI');
        $pais->setNome('KIRIBATI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KM');
        $pais->setNome('COMOROS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KN');
        $pais->setNome('SAINT KITTS AND NEVIS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KP');
        $pais->setNome('KOREA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KR');
        $pais->setNome('KOREA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KW');
        $pais->setNome('KUWAIT');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KY');
        $pais->setNome('CAYMAN ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KZ');
        $pais->setNome('KAZAKHSTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LA');
        $pais->setNome('LAO PEOPLE\'S DEMOCRATIC REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LB');
        $pais->setNome('LEBANON');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LC');
        $pais->setNome('SAINT LUCIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LI');
        $pais->setNome('LIECHTENSTEIN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LK');
        $pais->setNome('SRI LANKA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LR');
        $pais->setNome('LIBERIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LS');
        $pais->setNome('LESOTHO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LT');
        $pais->setNome('LITHUANIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LU');
        $pais->setNome('LUXEMBOURG');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LV');
        $pais->setNome('LATVIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LY');
        $pais->setNome('LIBYA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MA');
        $pais->setNome('MOROCCO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MC');
        $pais->setNome('MONACO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MD');
        $pais->setNome('MOLDOVA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ME');
        $pais->setNome('MONTENEGRO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MF');
        $pais->setNome('SAINT MARTIN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MG');
        $pais->setNome('MADAGASCAR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MH');
        $pais->setNome('MARSHALL ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MK');
        $pais->setNome('MACEDONIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ML');
        $pais->setNome('MALI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MM');
        $pais->setNome('MYANMAR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MN');
        $pais->setNome('MONGOLIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MO');
        $pais->setNome('MACAO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MP');
        $pais->setNome('NORTHERN MARIANA ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MQ');
        $pais->setNome('MARTINIQUE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MR');
        $pais->setNome('MAURITANIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MS');
        $pais->setNome('MONTSERRAT');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MT');
        $pais->setNome('MALTA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MU');
        $pais->setNome('MAURITIUS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MV');
        $pais->setNome('MALDIVES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MW');
        $pais->setNome('MALAWI');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MX');
        $pais->setNome('MEXICO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MY');
        $pais->setNome('MALAYSIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MZ');
        $pais->setNome('MOZAMBIQUE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NA');
        $pais->setNome('NAMIBIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NC');
        $pais->setNome('NEW CALEDONIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NE');
        $pais->setNome('NIGER');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NF');
        $pais->setNome('NORFOLK ISLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NG');
        $pais->setNome('NIGERIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NI');
        $pais->setNome('NICARAGUA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NL');
        $pais->setNome('NETHERLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NO');
        $pais->setNome('NORWAY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NP');
        $pais->setNome('NEPAL');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NR');
        $pais->setNome('NAURU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NU');
        $pais->setNome('NIUE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NZ');
        $pais->setNome('NEW ZEALAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('OM');
        $pais->setNome('OMAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PA');
        $pais->setNome('PANAMA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PE');
        $pais->setNome('PERU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PF');
        $pais->setNome('FRENCH POLYNESIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PG');
        $pais->setNome('PAPUA NEW GUINEA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PH');
        $pais->setNome('PHILIPPINES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PK');
        $pais->setNome('PAKISTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PL');
        $pais->setNome('POLAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PM');
        $pais->setNome('SAINT PIERRE AND MIQUELON');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PN');
        $pais->setNome('PITCAIRN ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PR');
        $pais->setNome('PUERTO RICO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PS');
        $pais->setNome('PALESTINE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PT');
        $pais->setNome('PORTUGAL');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PW');
        $pais->setNome('PALAU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PY');
        $pais->setNome('PARAGUAY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('QA');
        $pais->setNome('QATAR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RE');
        $pais->setNome('RÃ©UNION');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RO');
        $pais->setNome('ROMANIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RS');
        $pais->setNome('SERBIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RU');
        $pais->setNome('RUSSIAN FEDERATION');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RW');
        $pais->setNome('RWANDA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SA');
        $pais->setNome('SAUDI ARABIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SB');
        $pais->setNome('SOLOMON ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SC');
        $pais->setNome('SEYCHELLES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SD');
        $pais->setNome('SUDAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SE');
        $pais->setNome('SWEDEN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SG');
        $pais->setNome('SINGAPORE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SH');
        $pais->setNome('SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SI');
        $pais->setNome('SLOVENIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SJ');
        $pais->setNome('SVALBARD & JAN MAYEN ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SK');
        $pais->setNome('SLOVAKIA (SLOVAK REPUBLIC)');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SL');
        $pais->setNome('SIERRA LEONE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SM');
        $pais->setNome('SAN MARINO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SN');
        $pais->setNome('SENEGAL');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SO');
        $pais->setNome('SOMALIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SR');
        $pais->setNome('SURINAME');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SS');
        $pais->setNome('SOUTH SUDAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ST');
        $pais->setNome('SAO TOME AND PRINCIPE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SV');
        $pais->setNome('EL SALVADOR');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SX');
        $pais->setNome('SINT MAARTEN (DUTCH PART)');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SY');
        $pais->setNome('SYRIAN ARAB REPUBLIC');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SZ');
        $pais->setNome('SWAZILAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TC');
        $pais->setNome('TURKS AND CAICOS ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TD');
        $pais->setNome('CHAD');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TF');
        $pais->setNome('FRENCH SOUTHERN TERRITORIES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TG');
        $pais->setNome('TOGO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TH');
        $pais->setNome('THAILAND');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TJ');
        $pais->setNome('TAJIKISTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TK');
        $pais->setNome('TOKELAU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TL');
        $pais->setNome('TIMOR-LESTE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TM');
        $pais->setNome('TURKMENISTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TN');
        $pais->setNome('TUNISIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TO');
        $pais->setNome('TONGA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TR');
        $pais->setNome('TURKEY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TT');
        $pais->setNome('TRINIDAD AND TOBAGO');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TV');
        $pais->setNome('TUVALU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TW');
        $pais->setNome('TAIWAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TZ');
        $pais->setNome('TANZANIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UA');
        $pais->setNome('UKRAINE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UG');
        $pais->setNome('UGANDA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UM');
        $pais->setNome('UNITED STATES MINOR OUTLYING ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('US');
        $pais->setNome('UNITED STATES OF AMERICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UY');
        $pais->setNome('URUGUAY');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UZ');
        $pais->setNome('UZBEKISTAN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VA');
        $pais->setNome('HOLY SEE (VATICAN CITY STATE)');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VC');
        $pais->setNome('SAINT VINCENT AND THE GRENADINES');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VE');
        $pais->setNome('VENEZUELA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VG');
        $pais->setNome('BRITISH VIRGIN ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VI');
        $pais->setNome('UNITED STATES VIRGIN ISLANDS');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VN');
        $pais->setNome('VIETNAM');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VU');
        $pais->setNome('VANUATU');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('WF');
        $pais->setNome('WALLIS AND FUTUNA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('WS');
        $pais->setNome('SAMOA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('YE');
        $pais->setNome('YEMEN');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('YT');
        $pais->setNome('MAYOTTE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZA');
        $pais->setNome('SOUTH AFRICA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZM');
        $pais->setNome('ZAMBIA');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZW');
        $pais->setNome('ZIMBABWE');
        $pais->setCodigoReceitaFederal(null);
        $pais->setNomeReceitaFederal(null);
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prod'];
    }
}

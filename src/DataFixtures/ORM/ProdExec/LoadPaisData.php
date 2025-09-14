<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadPaisData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

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
        $pais->setCodigo('AF');
        $pais->setNome('AFEGANISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZA');
        $pais->setNome('ÁFRICA DO SUL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AL');
        $pais->setNome('ALBÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DE');
        $pais->setNome('ALEMANHA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AD');
        $pais->setNome('ANDORRA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AO');
        $pais->setNome('ANGOLA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AI');
        $pais->setNome('ANGUILLA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AQ');
        $pais->setNome('ANTÁRTIDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AG');
        $pais->setNome('ANTÍGUA E BARBUDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NA');
        $pais->setNome('ANTILHAS HOLANDESAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SA');
        $pais->setNome('ARÁBIA SAUDITA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DZ');
        $pais->setNome('ARGÉLIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AR');
        $pais->setNome('ARGENTINA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AM');
        $pais->setNome('ARMÊNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AW');
        $pais->setNome('ARUBA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AU');
        $pais->setNome('AUSTRÁLIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AT');
        $pais->setNome('ÁUSTRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AZ');
        $pais->setNome('AZERBAIJÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BS');
        $pais->setNome('BAHAMAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BH');
        $pais->setNome('BAHREIN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BD');
        $pais->setNome('BANGLADESH');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BB');
        $pais->setNome('BARBADOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BY');
        $pais->setNome('BELARUS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BE');
        $pais->setNome('BÉLGICA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BZ');
        $pais->setNome('BELIZE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BJ');
        $pais->setNome('BENIN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BM');
        $pais->setNome('BERMUDAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BO');
        $pais->setNome('BOLÍVIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BA');
        $pais->setNome('BÓSNIA-HERZEGÓVINA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BW');
        $pais->setNome('BOTSUANA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BR');
        $pais->setNome('BRASIL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BN');
        $pais->setNome('BRUNEI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BG');
        $pais->setNome('BULGÁRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BF');
        $pais->setNome('BURKINA FASSO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BI');
        $pais->setNome('BURUNDI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BT');
        $pais->setNome('BUTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CV');
        $pais->setNome('CABO VERDE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CM');
        $pais->setNome('CAMARÕES');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KH');
        $pais->setNome('CAMBOJA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CA');
        $pais->setNome('CANADÁ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KZ');
        $pais->setNome('CAZAQUISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TD');
        $pais->setNome('CHADE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CL');
        $pais->setNome('CHILE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CN');
        $pais->setNome('CHINA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CY');
        $pais->setNome('CHIPRE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SG');
        $pais->setNome('SINGAPURA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CO');
        $pais->setNome('COLÔMBIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CG');
        $pais->setNome('CONGO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KP');
        $pais->setNome('CORÉIA DO NORTE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KR');
        $pais->setNome('CORÉIA DO SUL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CI');
        $pais->setNome('COSTA DO MARFIM');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CR');
        $pais->setNome('COSTA RICA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HR');
        $pais->setNome('CROÁCIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CU');
        $pais->setNome('CUBA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DK');
        $pais->setNome('DINAMARCA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DJ');
        $pais->setNome('DJIBUTI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DM');
        $pais->setNome('DOMINICA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EG');
        $pais->setNome('EGITO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SV');
        $pais->setNome('EL SALVADOR');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AE');
        $pais->setNome('EMIRADOS ÁRABES UNIDOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EC');
        $pais->setNome('EQUADOR');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ER');
        $pais->setNome('ERITRÉIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SK');
        $pais->setNome('ESLOVÁQUIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SI');
        $pais->setNome('ESLOVÊNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ES');
        $pais->setNome('ESPANHA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('US');
        $pais->setNome('ESTADOS UNIDOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EE');
        $pais->setNome('ESTÔNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ET');
        $pais->setNome('ETIÓPIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RU');
        $pais->setNome('FEDERAÇÃO RUSSA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FJ');
        $pais->setNome('FIJI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PH');
        $pais->setNome('FILIPINAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FI');
        $pais->setNome('FINLÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FR');
        $pais->setNome('FRANÇA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GA');
        $pais->setNome('GABÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GM');
        $pais->setNome('GÂMBIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GH');
        $pais->setNome('GANA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GE');
        $pais->setNome('GEÓRGIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GI');
        $pais->setNome('GIBRALTAR');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GB');
        $pais->setNome('GRÃ-BRETANHA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GD');
        $pais->setNome('GRANADA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GR');
        $pais->setNome('GRÉCIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GL');
        $pais->setNome('GROELÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GP');
        $pais->setNome('GUADALUPE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GU');
        $pais->setNome('GUAM');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GT');
        $pais->setNome('GUATEMALA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('G');
        $pais->setNome('GUERNSEY');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GY');
        $pais->setNome('GUIANA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GF');
        $pais->setNome('GUIANA FRANCESA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GN');
        $pais->setNome('GUINÉ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GQ');
        $pais->setNome('GUINÉ EQUATORIAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GW');
        $pais->setNome('GUINÉ-BISSAU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HT');
        $pais->setNome('HAITI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NL');
        $pais->setNome('HOLANDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HN');
        $pais->setNome('HONDURAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HK');
        $pais->setNome('HONG KONG');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HU');
        $pais->setNome('HUNGRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('YE');
        $pais->setNome('IÊMEN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BV');
        $pais->setNome('ILHA BOUVET');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IM');
        $pais->setNome('ILHA DO HOMEM');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CX');
        $pais->setNome('ILHA NATAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PN');
        $pais->setNome('ILHA PITCAIRN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RE');
        $pais->setNome('ILHA REUNIÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AX');
        $pais->setNome('ILHAS ALAND');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KY');
        $pais->setNome('ILHAS CAYMAN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CC');
        $pais->setNome('ILHAS COCOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KM');
        $pais->setNome('ILHAS COMORES');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CK');
        $pais->setNome('ILHAS COOK');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FO');
        $pais->setNome('ILHAS FAROES');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FK');
        $pais->setNome('ILHAS FALKLAND');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('GS');
        $pais->setNome('ILHAS GEÓRGIA DO SUL E SANDWICH DO SUL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('HM');
        $pais->setNome('ILHAS HEARD E MCDONALD');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MP');
        $pais->setNome('ILHAS MARIANAS DO NORTE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MH');
        $pais->setNome('ILHAS MARSHALL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UM');
        $pais->setNome('ILHAS MENORES DOS ESTADOS UNIDOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NF');
        $pais->setNome('ILHAS NORFOLK');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SC');
        $pais->setNome('ILHAS SEYCHELLES');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SB');
        $pais->setNome('ILHAS SOLOMÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SJ');
        $pais->setNome('ILHAS SVALBARD E JAN MAYEN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TK');
        $pais->setNome('ILHAS TOKELAU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TC');
        $pais->setNome('ILHAS TURKS E CAICOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VI');
        $pais->setNome('ILHAS VIRGENS-ESTADOS UNIDOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VG');
        $pais->setNome('ILHAS VIRGENS-INGLATERRA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('WF');
        $pais->setNome('ILHAS WALLIS E FUTUNA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IN');
        $pais->setNome('ÍNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ID');
        $pais->setNome('INDONÉSIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IR');
        $pais->setNome('IRÃ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IQ');
        $pais->setNome('IRAQUE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IE');
        $pais->setNome('IRLANDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IS');
        $pais->setNome('ISLÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IL');
        $pais->setNome('ISRAEL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IT');
        $pais->setNome('ITÁLIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JM');
        $pais->setNome('JAMAICA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JP');
        $pais->setNome('JAPÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JE');
        $pais->setNome('JERSEY');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('JO');
        $pais->setNome('JORDÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KE');
        $pais->setNome('KÊNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KI');
        $pais->setNome('KIRIBATI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KW');
        $pais->setNome('KUAIT');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LA');
        $pais->setNome('LAOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LV');
        $pais->setNome('LÁTVIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LS');
        $pais->setNome('LESOTO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LB');
        $pais->setNome('LÍBANO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LR');
        $pais->setNome('LIBÉRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LY');
        $pais->setNome('LÍBIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LI');
        $pais->setNome('LIECHTENSTEIN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LT');
        $pais->setNome('LITUÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LU');
        $pais->setNome('LUXEMBURGO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MO');
        $pais->setNome('MACAU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MK');
        $pais->setNome('MACEDÔNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MG');
        $pais->setNome('MADAGASCAR');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MY');
        $pais->setNome('MALÁSIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MW');
        $pais->setNome('MALAUI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MV');
        $pais->setNome('MALDIVAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ML');
        $pais->setNome('MALI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MT');
        $pais->setNome('MALTA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MA');
        $pais->setNome('MARROCOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MQ');
        $pais->setNome('MARTINICA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MU');
        $pais->setNome('MAURÍCIO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MR');
        $pais->setNome('MAURITÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('YT');
        $pais->setNome('MAYOTTE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MX');
        $pais->setNome('MÉXICO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('FM');
        $pais->setNome('MICRONÉSIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MZ');
        $pais->setNome('MOÇAMBIQUE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MD');
        $pais->setNome('MOLDOVA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MC');
        $pais->setNome('MÔNACO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MN');
        $pais->setNome('MONGÓLIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ME');
        $pais->setNome('MONTENEGRO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MS');
        $pais->setNome('MONTSERRAT');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MM');
        $pais->setNome('MYANMA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('nan');
        $pais->setNome('NAMÍBIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NR');
        $pais->setNome('NAURU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NP');
        $pais->setNome('NEPAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NI');
        $pais->setNome('NICARÁGUA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NE');
        $pais->setNome('NÍGER');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NG');
        $pais->setNome('NIGÉRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NU');
        $pais->setNome('NIUE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NO');
        $pais->setNome('NORUEGA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NC');
        $pais->setNome('NOVA CALEDÔNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('NZ');
        $pais->setNome('NOVA ZELÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('OM');
        $pais->setNome('OMÃ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PW');
        $pais->setNome('PALAU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PA');
        $pais->setNome('PANAMÁ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PG');
        $pais->setNome('PAPUA-NOVA GUINÉ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PK');
        $pais->setNome('PAQUISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PY');
        $pais->setNome('PARAGUAI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PE');
        $pais->setNome('PERU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PF');
        $pais->setNome('POLINÉSIA FRANCESA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PL');
        $pais->setNome('POLÔNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PR');
        $pais->setNome('PORTO RICO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PT');
        $pais->setNome('PORTUGAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('QA');
        $pais->setNome('QATAR');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KG');
        $pais->setNome('QUIRGUISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CF');
        $pais->setNome('REPÚBLICA CENTRO-AFRICANA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CD');
        $pais->setNome('REPÚBLICA DEMOCRÁTICA DO CONGO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('DO');
        $pais->setNome('REPÚBLICA DOMINICANA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CZ');
        $pais->setNome('REPÚBLICA TCHECA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RO');
        $pais->setNome('ROMÊNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RW');
        $pais->setNome('RUANDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('EH');
        $pais->setNome('SAARA OCIDENTAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VC');
        $pais->setNome('SAINT VINCENTE E GRANADINAS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('AS');
        $pais->setNome('SAMOA OCIDENTAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('WS');
        $pais->setNome('SAMOA OCIDENTAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SM');
        $pais->setNome('SAN MARINO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SH');
        $pais->setNome('SANTA HELENA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LC');
        $pais->setNome('SANTA LÚCIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('BL');
        $pais->setNome('SÃO BARTOLOMEU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('KN');
        $pais->setNome('SÃO CRISTÓVÃO E NÉVIS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('MF');
        $pais->setNome('SÃO MARTIM');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ST');
        $pais->setNome('SÃO TOMÉ E PRÍNCIPE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SN');
        $pais->setNome('SENEGAL');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SL');
        $pais->setNome('SERRA LEOA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('RS');
        $pais->setNome('SÉRVIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SY');
        $pais->setNome('SÍRIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SO');
        $pais->setNome('SOMÁLIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('LK');
        $pais->setNome('SRI LANKA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PM');
        $pais->setNome('ST. PIERRE AND MIQUELON');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SZ');
        $pais->setNome('SUAZILÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SD');
        $pais->setNome('SUDÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SE');
        $pais->setNome('SUÉCIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('CH');
        $pais->setNome('SUÍÇA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('SR');
        $pais->setNome('SURINAME');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TJ');
        $pais->setNome('TADJIQUISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TH');
        $pais->setNome('TAILÂNDIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TW');
        $pais->setNome('TAIWAN');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TZ');
        $pais->setNome('TANZÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('IO');
        $pais->setNome('TERRITÓRIO BRITÂNICO DO OCEANO ÍNDICO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TF');
        $pais->setNome('TERRITÓRIOS DO SUL DA FRANÇA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('PS');
        $pais->setNome('TERRITÓRIOS PALESTINOS OCUPADOS');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TP');
        $pais->setNome('TIMOR LESTE');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TG');
        $pais->setNome('TOGO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TO');
        $pais->setNome('TONGA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TT');
        $pais->setNome('TRINIDAD AND TOBAGO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TN');
        $pais->setNome('TUNÍSIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TM');
        $pais->setNome('TURCOMENISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TR');
        $pais->setNome('TURQUIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('TV');
        $pais->setNome('TUVALU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UA');
        $pais->setNome('UCRÂNIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UG');
        $pais->setNome('UGANDA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UY');
        $pais->setNome('URUGUAI');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('UZ');
        $pais->setNome('UZBEQUISTÃO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VU');
        $pais->setNome('VANUATU');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VA');
        $pais->setNome('VATICANO');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VE');
        $pais->setNome('VENEZUELA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('VN');
        $pais->setNome('VIETNÃ');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZM');
        $pais->setNome('ZÂMBIA');
        $this->addReference('Pais-'.$pais->getCodigo(), $pais);
        $manager->persist($pais);

        $pais = new Pais();
        $pais->setCodigo('ZW');
        $pais->setNome('ZIMBÁBUE');
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
        return ['prodexec', 'dev', 'test'];
    }
}

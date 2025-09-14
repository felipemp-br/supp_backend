<?php

// DEV
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadAssuntoAdministrativoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;

/**
 * Class LoadAssuntoAdministrativoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAssuntoAdministrativoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ABASTECIMENTO');
        $assuntoAdministrativo->setCodigoCNJ('100');
        $assuntoAdministrativo->setGlossario('ABASTECIMENTO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ADMINISTRAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('018');
        $assuntoAdministrativo->setGlossario('ADMINISTRAÇÃO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('CADASTRO');
        $assuntoAdministrativo->setCodigoCNJ('191');
        $assuntoAdministrativo->setGlossario('CADASTRO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMPRAS GOVERNAMENTAIS');
        $assuntoAdministrativo->setCodigoCNJ('114');
        $assuntoAdministrativo->setGlossario('COMPRAS GOVERNAMENTAIS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('FISCALIZAÇÃO DO ESTADO');
        $assuntoAdministrativo->setCodigoCNJ('130');
        $assuntoAdministrativo->setGlossario('FISCALIZAÇÃO ESTADO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('NORMAS E FISCALIZAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('137');
        $assuntoAdministrativo->setGlossario('NORMAS FISCALIZAÇÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OPERAÇÕES DE DÍVIDA PÚBLICA');
        $assuntoAdministrativo->setCodigoCNJ('138');
        $assuntoAdministrativo->setGlossario('OPERAÇÕES DÍVIDA PÚBLICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ORÇAMENTO');
        $assuntoAdministrativo->setCodigoCNJ('139');
        $assuntoAdministrativo->setGlossario('ORÇAMENTO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PATRIMÔNIO');
        $assuntoAdministrativo->setCodigoCNJ('163');
        $assuntoAdministrativo->setGlossario('PATRIMÔNIO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('RECURSOS HUMANOS');
        $assuntoAdministrativo->setCodigoCNJ('175');
        $assuntoAdministrativo->setGlossario('RECURSOS HUMANOS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SERVIÇOS PÚBLICOS');
        $assuntoAdministrativo->setCodigoCNJ('180');
        $assuntoAdministrativo->setGlossario('SERVIÇOS PÚBLICOS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM ADMINISTRAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('140');
        $assuntoAdministrativo->setGlossario('OUTROS ADMINISTRAÇÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ADMINISTRAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('AGROPECUÁRIA, PESCA E EXTRATIVISMO');
        $assuntoAdministrativo->setCodigoCNJ('001');
        $assuntoAdministrativo->setGlossario('AGROPECUÁRIA PESCA EXTRATIVISMO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA E VIGILÂNCIA SANITÁRIA');
        $assuntoAdministrativo->setCodigoCNJ('196');
        $assuntoAdministrativo->setGlossario('DEFESA VIGILÂNCIA SANITÁRIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-AGROPECUÁRIA, PESCA E EXTRATIVISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome().'1', $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PRODUÇÃO AGROPECUÁRIA');
        $assuntoAdministrativo->setCodigoCNJ('171');
        $assuntoAdministrativo->setGlossario('PRODUÇÃO AGROPECUÁRIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-AGROPECUÁRIA, PESCA E EXTRATIVISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM AGROPECUÁRIA');
        $assuntoAdministrativo->setCodigoCNJ('141');
        $assuntoAdministrativo->setGlossario('OUTROS AGROPECUÁRIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-AGROPECUÁRIA, PESCA E EXTRATIVISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMÉRCIO E SERVIÇOS');
        $assuntoAdministrativo->setCodigoCNJ('003');
        $assuntoAdministrativo->setGlossario('COMÉRCIO SERVIÇOS');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMERCIO EXTERNO');
        $assuntoAdministrativo->setCodigoCNJ('113');
        $assuntoAdministrativo->setGlossario('COMERCIO EXTERNO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMÉRCIO E SERVIÇOS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA DO CONSUMIDOR');
        $assuntoAdministrativo->setCodigoCNJ('119');
        $assuntoAdministrativo->setGlossario('DEFESA CONSUMIDOR');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMÉRCIO E SERVIÇOS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TURISMO');
        $assuntoAdministrativo->setCodigoCNJ('187');
        $assuntoAdministrativo->setGlossario('TURISMO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMÉRCIO E SERVIÇOS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM COMÉRCIO E SERVIÇOS');
        $assuntoAdministrativo->setCodigoCNJ('142');
        $assuntoAdministrativo->setGlossario('OUTROS COMÉRCIO SERVIÇOS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMÉRCIO E SERVIÇOS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMUNICAÇÕES');
        $assuntoAdministrativo->setCodigoCNJ('019');
        $assuntoAdministrativo->setGlossario('COMUNICAÇÕES');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMUNICAÇÕES POSTAIS');
        $assuntoAdministrativo->setCodigoCNJ('115');
        $assuntoAdministrativo->setGlossario('COMUNICAÇÕES POSTAIS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMUNICAÇÕES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TELECOMUNICAÇÕES');
        $assuntoAdministrativo->setCodigoCNJ('183');
        $assuntoAdministrativo->setGlossario('TELECOMUNICAÇÕES');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMUNICAÇÕES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM COMUNICAÇÕES');
        $assuntoAdministrativo->setCodigoCNJ('143');
        $assuntoAdministrativo->setGlossario('OUTROS COMUNICAÇÕES');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-COMUNICAÇÕES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('CULTURA');
        $assuntoAdministrativo->setCodigoCNJ('020');
        $assuntoAdministrativo->setGlossario('CULTURA');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DIFUSÃO CULTURAL');
        $assuntoAdministrativo->setCodigoCNJ('122');
        $assuntoAdministrativo->setGlossario('DIFUSÃO CULTURAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-CULTURA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PATRIMÔNIO CULTURAL');
        $assuntoAdministrativo->setCodigoCNJ('164');
        $assuntoAdministrativo->setGlossario('PATRIMÔNIO CULTURAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-CULTURA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM CULTURA');
        $assuntoAdministrativo->setCodigoCNJ('144');
        $assuntoAdministrativo->setGlossario('OUTROS CULTURA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-CULTURA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA NACIONAL');
        $assuntoAdministrativo->setCodigoCNJ('021');
        $assuntoAdministrativo->setGlossario('DEFESA NACIONAL');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA CIVIL');
        $assuntoAdministrativo->setCodigoCNJ('118');
        $assuntoAdministrativo->setGlossario('DEFESA CIVIL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-DEFESA NACIONAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA MILITAR');
        $assuntoAdministrativo->setCodigoCNJ('120');
        $assuntoAdministrativo->setGlossario('DEFESA MILITAR');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-DEFESA NACIONAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM DEFESA NACIONAL');
        $assuntoAdministrativo->setCodigoCNJ('145');
        $assuntoAdministrativo->setGlossario('OUTROS DEFESA NACIONAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-DEFESA NACIONAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ECONOMIA E FINANÇAS');
        $assuntoAdministrativo->setCodigoCNJ('006');
        $assuntoAdministrativo->setGlossario('ECONOMIA FINANÇAS');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA DA CONCORRÊNCIA');
        $assuntoAdministrativo->setCodigoCNJ('192');
        $assuntoAdministrativo->setGlossario('DEFESA CONCORRÊNCIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ECONOMIA E FINANÇAS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('POLITICA ECONÔMICA');
        $assuntoAdministrativo->setCodigoCNJ('167');
        $assuntoAdministrativo->setGlossario('POLITICA ECONÔMICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ECONOMIA E FINANÇAS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SISTEMA FINANCEIRO');
        $assuntoAdministrativo->setCodigoCNJ('182');
        $assuntoAdministrativo->setGlossario('SISTEMA FINANCEIRO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ECONOMIA E FINANÇAS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM ECONOMIA E FINANÇAS');
        $assuntoAdministrativo->setCodigoCNJ('146');
        $assuntoAdministrativo->setGlossario('OUTROS ECONOMIA FINANÇAS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ECONOMIA E FINANÇAS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('EDUCAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('007');
        $assuntoAdministrativo->setGlossario('EDUCAÇÃO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('EDUCAÇÃO BÁSICA');
        $assuntoAdministrativo->setCodigoCNJ('123');
        $assuntoAdministrativo->setGlossario('EDUCAÇÃO BÁSICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-EDUCAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('EDUCAÇÃO PROFISSIONALIZANTE');
        $assuntoAdministrativo->setCodigoCNJ('124');
        $assuntoAdministrativo->setGlossario('EDUCAÇÃO PROFISSIONALIZANTE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-EDUCAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('EDUCAÇÃO SUPERIOR');
        $assuntoAdministrativo->setCodigoCNJ('125');
        $assuntoAdministrativo->setGlossario('EDUCAÇÃO SUPERIOR');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-EDUCAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM EDUCAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('147');
        $assuntoAdministrativo->setGlossario('OUTROS EDUCAÇÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-EDUCAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ENERGIA');
        $assuntoAdministrativo->setCodigoCNJ('022');
        $assuntoAdministrativo->setGlossario('ENERGIA');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMBUSTÍVEIS');
        $assuntoAdministrativo->setCodigoCNJ('112');
        $assuntoAdministrativo->setGlossario('COMBUSTÍVEIS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ENERGIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ENERGIA ELÉTRICA');
        $assuntoAdministrativo->setCodigoCNJ('127');
        $assuntoAdministrativo->setGlossario('ENERGIA ELÉTRICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ENERGIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM ENERGIA');
        $assuntoAdministrativo->setCodigoCNJ('148');
        $assuntoAdministrativo->setGlossario('OUTROS ENERGIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ENERGIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ESPORTE E LAZER');
        $assuntoAdministrativo->setCodigoCNJ('023');
        $assuntoAdministrativo->setGlossario('ESPORTE LAZER');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ESPORTE COMUNITÁRIO');
        $assuntoAdministrativo->setCodigoCNJ('128');
        $assuntoAdministrativo->setGlossario('ESPORTE COMUNITÁRIO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ESPORTE E LAZER', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ESPORTE PROFISSIONAL');
        $assuntoAdministrativo->setCodigoCNJ('129');
        $assuntoAdministrativo->setGlossario('ESPORTE PROFISSIONAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ESPORTE E LAZER', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('LAZER');
        $assuntoAdministrativo->setCodigoCNJ('134');
        $assuntoAdministrativo->setGlossario('LAZER');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ESPORTE E LAZER', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM ESPORTE E LAZER');
        $assuntoAdministrativo->setCodigoCNJ('149');
        $assuntoAdministrativo->setGlossario('OUTROS ESPORTE LAZER');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-ESPORTE E LAZER', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('HABITAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('024');
        $assuntoAdministrativo->setGlossario('HABITAÇÃO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('HABITAÇÃO RURAL');
        $assuntoAdministrativo->setCodigoCNJ('132');
        $assuntoAdministrativo->setGlossario('HABITAÇÃO RURAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-HABITAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('HABITAÇÃO URBANA');
        $assuntoAdministrativo->setCodigoCNJ('133');
        $assuntoAdministrativo->setGlossario('HABITAÇÃO URBANA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-HABITAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM HABITAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('150');
        $assuntoAdministrativo->setGlossario('OUTROS HABITAÇÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-HABITAÇÃO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('INDÚSTRIA');
        $assuntoAdministrativo->setCodigoCNJ('010');
        $assuntoAdministrativo->setGlossario('INDÚSTRIA');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('MINERAÇÃO');
        $assuntoAdministrativo->setCodigoCNJ('136');
        $assuntoAdministrativo->setGlossario('MINERAÇÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-INDÚSTRIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PRODUÇÃO INDUSTRIAL');
        $assuntoAdministrativo->setCodigoCNJ('172');
        $assuntoAdministrativo->setGlossario('PRODUÇÃO INDUSTRIAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-INDÚSTRIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PROPRIEDADE INDUSTRIAL');
        $assuntoAdministrativo->setCodigoCNJ('173');
        $assuntoAdministrativo->setGlossario('PROPRIEDADE INDUSTRIAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-INDÚSTRIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM INDUSTRIA');
        $assuntoAdministrativo->setCodigoCNJ('151');
        $assuntoAdministrativo->setGlossario('OUTROS INDUSTRIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-INDÚSTRIA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('INFRAESTRUTURA E FOMENTO');
        $assuntoAdministrativo->setCodigoCNJ('193');
        $assuntoAdministrativo->setGlossario('INFRAESTRUTURA FOMENTO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('MEIO AMBIENTE');
        $assuntoAdministrativo->setCodigoCNJ('013');
        $assuntoAdministrativo->setGlossario('MEIO AMBIENTE');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ÁGUA');
        $assuntoAdministrativo->setCodigoCNJ('101');
        $assuntoAdministrativo->setGlossario('ÁGUA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-MEIO AMBIENTE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('BIODIVERSIDADE');
        $assuntoAdministrativo->setCodigoCNJ('107');
        $assuntoAdministrativo->setGlossario('BIODIVERSIDADE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-MEIO AMBIENTE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('CLIMA');
        $assuntoAdministrativo->setCodigoCNJ('109');
        $assuntoAdministrativo->setGlossario('CLIMA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-MEIO AMBIENTE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PRESERVAÇÃO E CONSERVAÇÃO AMBIENTAL');
        $assuntoAdministrativo->setCodigoCNJ('168');
        $assuntoAdministrativo->setGlossario('PRESERVAÇÃO CONSERVAÇÃO AMBIENTAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-MEIO AMBIENTE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM MEIO AMBIENTE');
        $assuntoAdministrativo->setCodigoCNJ('152');
        $assuntoAdministrativo->setGlossario('OUTROS MEIO AMBIENTE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-MEIO AMBIENTE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PESQUISA E DESENVOLVIMENTO');
        $assuntoAdministrativo->setCodigoCNJ('025');
        $assuntoAdministrativo->setGlossario('PESQUISA DESENVOLVIMENTO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DIFUSÃO');
        $assuntoAdministrativo->setCodigoCNJ('121');
        $assuntoAdministrativo->setGlossario('DIFUSÃO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PESQUISA E DESENVOLVIMENTO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM PESQUISA E DESENVOLVIMENTO');
        $assuntoAdministrativo->setCodigoCNJ('153');
        $assuntoAdministrativo->setGlossario('OUTROS PESQUISA DESENVOLVIMENTO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PESQUISA E DESENVOLVIMENTO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PLANEJAMENTO E GESTÃO');
        $assuntoAdministrativo->setCodigoCNJ('165');
        $assuntoAdministrativo->setGlossario('PLANEJAMENTO GESTÃO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PREVIDÊNCIA SOCIAL');
        $assuntoAdministrativo->setCodigoCNJ('026');
        $assuntoAdministrativo->setGlossario('PREVIDÊNCIA SOCIAL');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMBATE A POBREZA');
        $assuntoAdministrativo->setCodigoCNJ('194');
        $assuntoAdministrativo->setGlossario('COMBATE POBREZA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PREVIDÊNCIA SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PREVIDÊNCIA BÁSICA');
        $assuntoAdministrativo->setCodigoCNJ('169');
        $assuntoAdministrativo->setGlossario('PREVIDÊNCIA BÁSICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PREVIDÊNCIA SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PREVIDÊNCIA COMPLEMENTAR');
        $assuntoAdministrativo->setCodigoCNJ('170');
        $assuntoAdministrativo->setGlossario('PREVIDÊNCIA COMPLEMENTAR');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PREVIDÊNCIA SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('BENEFÍCIOS SOCIAIS');
        $assuntoAdministrativo->setCodigoCNJ('195');
        $assuntoAdministrativo->setGlossario('BENEFÍCIOS SOCIAIS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PREVIDÊNCIA SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM PREVIDÊNCIA');
        $assuntoAdministrativo->setCodigoCNJ('154');
        $assuntoAdministrativo->setGlossario('OUTROS PREVIDÊNCIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PREVIDÊNCIA SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PROTEÇÃO SOCIAL');
        $assuntoAdministrativo->setCodigoCNJ('027');
        $assuntoAdministrativo->setGlossario('PROTEÇÃO SOCIAL');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ASSISTÊNCIA À CRIANÇA E AO ADOLESCENTE');
        $assuntoAdministrativo->setCodigoCNJ('102');
        $assuntoAdministrativo->setGlossario('ASSISTÊNCIA CRIANÇA ADOLESCENTE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ASSISTÊNCIA AO IDOSO');
        $assuntoAdministrativo->setCodigoCNJ('103');
        $assuntoAdministrativo->setGlossario('ASSISTÊNCIA IDOSO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ASSISTÊNCIA AO PORTADOR DE DEFICIÊNCIA');
        $assuntoAdministrativo->setCodigoCNJ('104');
        $assuntoAdministrativo->setGlossario('ASSISTÊNCIA PORTADOR DEFICIÊNCIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('CIDADANIA');
        $assuntoAdministrativo->setCodigoCNJ('108');
        $assuntoAdministrativo->setGlossario('CIDADANIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMBATE A DESIGUALDADE');
        $assuntoAdministrativo->setCodigoCNJ('110');
        $assuntoAdministrativo->setGlossario('COMBATE DESIGUALDADE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM PROTEÇÃO SOCIAL');
        $assuntoAdministrativo->setCodigoCNJ('155');
        $assuntoAdministrativo->setGlossario('OUTROS EM PROTEÇÃO SOCIAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-PROTEÇÃO SOCIAL', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('RELAÇÕES INTERNACIONAIS');
        $assuntoAdministrativo->setCodigoCNJ('014');
        $assuntoAdministrativo->setGlossario('RELAÇÕES INTERNACIONAIS');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COOPERAÇÃO INTERNACIONAL');
        $assuntoAdministrativo->setCodigoCNJ('116');
        $assuntoAdministrativo->setGlossario('COOPERAÇÃO INTERNACIONAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-RELAÇÕES INTERNACIONAIS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('RELAÇÕES DIPLOMÁTICAS');
        $assuntoAdministrativo->setCodigoCNJ('177');
        $assuntoAdministrativo->setGlossario('RELAÇÕES DIPLOMÁTICAS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-RELAÇÕES INTERNACIONAIS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM RELAÇÕES INTERNACIONAIS');
        $assuntoAdministrativo->setCodigoCNJ('156');
        $assuntoAdministrativo->setGlossario('OUTROS RELAÇÕES INTERNACIONAIS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-RELAÇÕES INTERNACIONAIS', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SANEAMENTO');
        $assuntoAdministrativo->setCodigoCNJ('028');
        $assuntoAdministrativo->setGlossario('SANEAMENTO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SANEAMENTO BÁSICO RURAL');
        $assuntoAdministrativo->setCodigoCNJ('178');
        $assuntoAdministrativo->setGlossario('SANEAMENTO BÁSICO RURAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SANEAMENTO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SANEAMENTO BÁSICO URBANO');
        $assuntoAdministrativo->setCodigoCNJ('179');
        $assuntoAdministrativo->setGlossario('SANEAMENTO BÁSICO URBANO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SANEAMENTO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM SANEAMENTO');
        $assuntoAdministrativo->setCodigoCNJ('157');
        $assuntoAdministrativo->setGlossario('OUTROS SANEAMENTO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SANEAMENTO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SAÚDE');
        $assuntoAdministrativo->setCodigoCNJ('015');
        $assuntoAdministrativo->setGlossario('SAÚDE');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ASSISTÊNCIA HOSPITALAR E AMBULATORIAL');
        $assuntoAdministrativo->setCodigoCNJ('105');
        $assuntoAdministrativo->setGlossario('ASSISTÊNCIA HOSPITALAR AMBULATORIAL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ATENDIMENTO BÁSICO');
        $assuntoAdministrativo->setCodigoCNJ('106');
        $assuntoAdministrativo->setGlossario('ATENDIMENTO BÁSICO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('COMBATE A EPIDEMIAS');
        $assuntoAdministrativo->setCodigoCNJ('111');
        $assuntoAdministrativo->setGlossario('COMBATE EPIDEMIAS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA E VIGILÂNCIA SANITÁRIA');
        $assuntoAdministrativo->setCodigoCNJ('196');
        $assuntoAdministrativo->setGlossario('DEFESA VIGILÂNCIA SANITÁRIA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('MEDICAMENTOS E APARELHOS');
        $assuntoAdministrativo->setCodigoCNJ('135');
        $assuntoAdministrativo->setGlossario('MEDICAMENTOS APARELHOS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM SAÚDE');
        $assuntoAdministrativo->setCodigoCNJ('158');
        $assuntoAdministrativo->setGlossario('OUTROS SAÚDE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SAÚDE', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SEGURANÇA E ORDEM PÚBLICA');
        $assuntoAdministrativo->setCodigoCNJ('029');
        $assuntoAdministrativo->setGlossario('SEGURANÇA ORDEM PÚBLICA');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DEFESA CIVIL');
        $assuntoAdministrativo->setCodigoCNJ('118');
        $assuntoAdministrativo->setGlossario('DEFESA CIVIL');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SEGURANÇA E ORDEM PÚBLICA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome().'1', $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('POLICIAMENTO');
        $assuntoAdministrativo->setCodigoCNJ('166');
        $assuntoAdministrativo->setGlossario('POLICIAMENTO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SEGURANÇA E ORDEM PÚBLICA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM SEGURANÇA E ORDEM PÚBLICA');
        $assuntoAdministrativo->setCodigoCNJ('159');
        $assuntoAdministrativo->setGlossario('OUTROS SEGURANÇA ORDEM PÚBLICA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-SEGURANÇA E ORDEM PÚBLICA', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRABALHO');
        $assuntoAdministrativo->setCodigoCNJ('016');
        $assuntoAdministrativo->setGlossario('TRABALHO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('EMPREGABILIDADE');
        $assuntoAdministrativo->setCodigoCNJ('126');
        $assuntoAdministrativo->setGlossario('EMPREGABILIDADE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRABALHO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('FOMENTO AO TRABALHO');
        $assuntoAdministrativo->setCodigoCNJ('131');
        $assuntoAdministrativo->setGlossario('FOMENTO TRABALHO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRABALHO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('PROTEÇÃO E BENEFÍCIOS AO TRABALHADOR');
        $assuntoAdministrativo->setCodigoCNJ('174');
        $assuntoAdministrativo->setGlossario('PROTEÇÃO BENEFÍCIOS TRABALHADOR');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRABALHO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('RELAÇÕES DE TRABALHO');
        $assuntoAdministrativo->setCodigoCNJ('176');
        $assuntoAdministrativo->setGlossario('RELAÇÕES TRABALHO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRABALHO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM TRABALHO');
        $assuntoAdministrativo->setCodigoCNJ('160');
        $assuntoAdministrativo->setGlossario('OUTROS TRABALHO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRABALHO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRANSPORTES');
        $assuntoAdministrativo->setCodigoCNJ('030');
        $assuntoAdministrativo->setGlossario('TRANSPORTES');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRANSPORTE AÉREO');
        $assuntoAdministrativo->setCodigoCNJ('184');
        $assuntoAdministrativo->setGlossario('TRANSPORTE AÉREO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRANSPORTES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRANSPORTE FERROVIÁRIO');
        $assuntoAdministrativo->setCodigoCNJ('189');
        $assuntoAdministrativo->setGlossario('TRANSPORTE FERROVIÁRIO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRANSPORTES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRANSPORTE HIDROVIÁRIO');
        $assuntoAdministrativo->setCodigoCNJ('185');
        $assuntoAdministrativo->setGlossario('TRANSPORTE HIDROVIÁRIO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRANSPORTES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('TRANSPORTE RODOVIÁRIO');
        $assuntoAdministrativo->setCodigoCNJ('186');
        $assuntoAdministrativo->setGlossario('TRANSPORTE RODOVIÁRIO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRANSPORTES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM TRANSPORTE');
        $assuntoAdministrativo->setCodigoCNJ('161');
        $assuntoAdministrativo->setGlossario('OUTROS TRANSPORTE');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-TRANSPORTES', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('URBANISMO');
        $assuntoAdministrativo->setCodigoCNJ('031');
        $assuntoAdministrativo->setGlossario('URBANISMO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('INFRAESTRUTURA URBANA');
        $assuntoAdministrativo->setCodigoCNJ('190');
        $assuntoAdministrativo->setGlossario('INFRAESTRUTURA URBANA');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-URBANISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('SERVIÇOS URBANOS');
        $assuntoAdministrativo->setCodigoCNJ('181');
        $assuntoAdministrativo->setGlossario('SERVIÇOS URBANOS');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-URBANISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('OUTROS EM URBANISMO');
        $assuntoAdministrativo->setCodigoCNJ('162');
        $assuntoAdministrativo->setGlossario('OUTROS URBANISMO');

        $assuntoAdministrativo->setParent($this->getReference('AssuntoAdministrativo-URBANISMO', AssuntoAdministrativo::class));

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);
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
        return ['prodexec'];
    }
}

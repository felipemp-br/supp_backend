<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeNaturezaJuridicaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica;

/**
 * Class LoadModalidadeNaturezaJuridicaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeNaturezaJuridicaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1015');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER EXECUTIVO FEDERAL');
         $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
             $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1023');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1031');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1040');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER LEGISLATIVO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1058');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1066');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER LEGISLATIVO MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1074');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO ESTADUAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1082');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO ESTADUAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGÃO AUTONOMO DE DIREITO PÚBLICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1090');
        $modNaturezaJuridica->setDescricao('ORGÃO AUTONOMO DE DIREITO PÚBLICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'AUTARQUIA FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1104');
        $modNaturezaJuridica->setDescricao('AUTARQUIA FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1112');
        $modNaturezaJuridica->setDescricao('AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'AUTARQUIA MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1120');
        $modNaturezaJuridica->setDescricao('AUTARQUIA MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1139');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1147');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1155');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO AUTÔNOMO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1163');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO AUTÔNOMO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO AUTÔNOMO ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1171');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO AUTÔNOMO ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO PÚBLICO AUTÔNOMO MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1180');
        $modNaturezaJuridica->setDescricao('ÓRGÃO PÚBLICO AUTÔNOMO MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COMISSÃO POLINACIONAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1198');
        $modNaturezaJuridica->setDescricao('COMISSÃO POLINACIONAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1201');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONSÓRCIO PÚBLICO DE DIREITO PÚBLICO (ASSOCIAÇÃO PÚBLICA)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1210');
        $modNaturezaJuridica->setDescricao('CONSÓRCIO PÚBLICO DE DIREITO PÚBLICO (ASSOCIAÇÃO PÚBLICA)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1228');
        $modNaturezaJuridica->setDescricao('CONSÓRCIO PÚBLICO DE DIREITO PRIVADO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ESTADO OU DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1236');
        $modNaturezaJuridica->setDescricao('ESTADO OU DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'MUNICÍPIO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1244');
        $modNaturezaJuridica->setDescricao('MUNICÍPIO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1252');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1260');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1279');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1287');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1295');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1309');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1317');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA ESTADUAL OU DO DISTRITO FEDERAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1325');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA ESTADUAL OU DO DISTRITO FEDERAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA MUNICIPAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1333');
        $modNaturezaJuridica->setDescricao('FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA MUNICIPAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'UNIÃO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1341');
        $modNaturezaJuridica->setDescricao('UNIÃO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'OUTRAS FORMAS ORGANIZAÇÃO ADMINISTRAÇÃO PUBLICA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('1996');
        $modNaturezaJuridica->setDescricao('OUTRAS FORMAS ORGANIZAÇÃO ADMINISTRAÇÃO PUBLICA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA PÚBLICA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2011');
        $modNaturezaJuridica->setDescricao('EMPRESA PÚBLICA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA PÚBLICA(SOCIEDADE ANONIMA DE CAPITAL FECHADO)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2020');
        $modNaturezaJuridica->setDescricao('EMPRESA PÚBLICA(SOCIEDADE ANONIMA DE CAPITAL FECHADO)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE DE ECONOMIA MISTA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2038');
        $modNaturezaJuridica->setDescricao('SOCIEDADE DE ECONOMIA MISTA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE ANÔNIMA ABERTA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2046');
        $modNaturezaJuridica->setDescricao('SOCIEDADE ANÔNIMA ABERTA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE ANÔNIMA FECHADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2054');
        $modNaturezaJuridica->setDescricao('SOCIEDADE ANÔNIMA FECHADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE EMPRESÁRIA LIMITADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2062');
        $modNaturezaJuridica->setDescricao('SOCIEDADE EMPRESÁRIA LIMITADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE EMPRESÁRIA EM NOME COLETIVO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2070');
        $modNaturezaJuridica->setDescricao('SOCIEDADE EMPRESÁRIA EM NOME COLETIVO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE EMPRESÁRIA EM COMANDITA SIMPLES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2089');
        $modNaturezaJuridica->setDescricao('SOCIEDADE EMPRESÁRIA EM COMANDITA SIMPLES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE EMPRESÁRIA EM COMANDITA POR AÇÕES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2097');
        $modNaturezaJuridica->setDescricao('SOCIEDADE EMPRESÁRIA EM COMANDITA POR AÇÕES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE MERCANTIL DE CAPITAL E INDÚSTRIA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2100');
        $modNaturezaJuridica->setDescricao('SOCIEDADE MERCANTIL DE CAPITAL E INDÚSTRIA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE CIVIL COM FINS LUCRATIVOS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2119');
        $modNaturezaJuridica->setDescricao('SOCIEDADE CIVIL COM FINS LUCRATIVOS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE EM CONTA DE PARTICIPAÇÃO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2127');
        $modNaturezaJuridica->setDescricao('SOCIEDADE EM CONTA DE PARTICIPAÇÃO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESÁRIO (INDIVIDUAL)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2135');
        $modNaturezaJuridica->setDescricao('EMPRESÁRIO (INDIVIDUAL)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COOPERATIVA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2143');
        $modNaturezaJuridica->setDescricao('COOPERATIVA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONSÓRCIO DE SOCIEDADES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2151');
        $modNaturezaJuridica->setDescricao('CONSÓRCIO DE SOCIEDADES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'GRUPO DE SOCIEDADES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2160');
        $modNaturezaJuridica->setDescricao('GRUPO DE SOCIEDADES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ESTABELECIMENTO, NO BRASIL, DE SOCIEDADE ESTRANGEIRA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2178');
        $modNaturezaJuridica->setDescricao('ESTABELECIMENTO, NO BRASIL, DE SOCIEDADE ESTRANGEIRA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ESTABELECIMENTO, NO BRASIL, DE EMPRESA BINACIONAL ARGENTINO-BRASILEIRA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2194');
        $modNaturezaJuridica->setDescricao('ESTABELECIMENTO, NO BRASIL, DE EMPRESA BINACIONAL ARGENTINO-BRASILEIRA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ENTIDADE BINACIONAL ITAIPU'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2208');
        $modNaturezaJuridica->setDescricao('ENTIDADE BINACIONAL ITAIPU');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA DOMICILIADA NO EXTERIOR'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2216');
        $modNaturezaJuridica->setDescricao('EMPRESA DOMICILIADA NO EXTERIOR');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CLUBE/FUNDO DE INVESTIMENTO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2224');
        $modNaturezaJuridica->setDescricao('CLUBE/FUNDO DE INVESTIMENTO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE SIMPLES PURA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2232');
        $modNaturezaJuridica->setDescricao('SOCIEDADE SIMPLES PURA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE SIMPLES LIMITADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2240');
        $modNaturezaJuridica->setDescricao('SOCIEDADE SIMPLES LIMITADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE SIMPLES EM NOME COLETIVO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2259');
        $modNaturezaJuridica->setDescricao('SOCIEDADE SIMPLES EM NOME COLETIVO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE SIMPLES EM COMANDITA SIMPLES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2267');
        $modNaturezaJuridica->setDescricao('SOCIEDADE SIMPLES EM COMANDITA SIMPLES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA BINACIONAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2275');
        $modNaturezaJuridica->setDescricao('EMPRESA BINACIONAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONSÓRCIO DE EMPREGADORES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2283');
        $modNaturezaJuridica->setDescricao('CONSÓRCIO DE EMPREGADORES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONSÓRCIO SIMPLES'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2291');
        $modNaturezaJuridica->setDescricao('CONSÓRCIO SIMPLES');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRESÁRIA)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2305');
        $modNaturezaJuridica->setDescricao('EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRESÁRIA)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2313');
        $modNaturezaJuridica->setDescricao('EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SOCIEDADE UNIPESSOAL DE ADVOCACIA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2321');
        $modNaturezaJuridica->setDescricao('SOCIEDADE UNIPESSOAL DE ADVOCACIA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COOPERATIVAS DE CONSUMO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2330');
        $modNaturezaJuridica->setDescricao('COOPERATIVAS DE CONSUMO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA SIMPLES DE INOVAÇÃO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2348');
        $modNaturezaJuridica->setDescricao('EMPRESA SIMPLES DE INOVAÇÃO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'OUTRAS FORMAS DE ORGANIZAÇÃO EMPRESARIAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('2992');
        $modNaturezaJuridica->setDescricao('OUTRAS FORMAS DE ORGANIZAÇÃO EMPRESARIAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO MANTIDAD COM RECURSOS PRIVADOS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3018');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO MANTIDAD COM RECURSOS PRIVADOS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ASSOCIAÇÃO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3026');
        $modNaturezaJuridica->setDescricao('ASSOCIAÇÃO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SERVIÇO NOTARIAL E REGISTRAL (CARTÓRIO)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3034');
        $modNaturezaJuridica->setDescricao('SERVIÇO NOTARIAL E REGISTRAL (CARTÓRIO)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO SOCIAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3042');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO SOCIAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO DA SOCIEDADE CIVIL DE INTERESSE PÚBLICO (OSCIP)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3050');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO DA SOCIEDADE CIVIL DE INTERESSE PÚBLICO (OSCIP)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO PRIVADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3069');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO PRIVADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'SERVIÇO SOCIAL AUTÔNOMO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3077');
        $modNaturezaJuridica->setDescricao('SERVIÇO SOCIAL AUTÔNOMO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONDOMÍNIO EDILÍCIO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3085');
        $modNaturezaJuridica->setDescricao('CONDOMÍNIO EDILÍCIO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'UNIDADE EXECUTORA (PROGRAMA DINHEIRO DIRETO NA ESCOLA)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3093');
        $modNaturezaJuridica->setDescricao('UNIDADE EXECUTORA (PROGRAMA DINHEIRO DIRETO NA ESCOLA)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COMISSÃO DE CONCILIAÇÃO PRÉVIA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3107');
        $modNaturezaJuridica->setDescricao('COMISSÃO DE CONCILIAÇÃO PRÉVIA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ENTIDADE DE MEDIAÇÃO E ARBITRAGEM'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3115');
        $modNaturezaJuridica->setDescricao('ENTIDADE DE MEDIAÇÃO E ARBITRAGEM');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'PARTIDO POLÍTICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3123');
        $modNaturezaJuridica->setDescricao('PARTIDO POLÍTICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ENTIDADE SINDICAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3131');
        $modNaturezaJuridica->setDescricao('ENTIDADE SINDICAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ESTABELECIMENTO, NO BRASIL, DE FUNDAÇÃO OU ASSOCIAÇÃO ESTRANGEIRAS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3204');
        $modNaturezaJuridica->setDescricao('ESTABELECIMENTO, NO BRASIL, DE FUNDAÇÃO OU ASSOCIAÇÃO ESTRANGEIRAS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDAÇÃO OU ASSOCIAÇÃO DOMICILIADA NO EXTERIOR'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3212');
        $modNaturezaJuridica->setDescricao('FUNDAÇÃO OU ASSOCIAÇÃO DOMICILIADA NO EXTERIOR');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO RELIGIOSA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3220');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO RELIGIOSA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COMUNIDADE INDÍGENA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3239');
        $modNaturezaJuridica->setDescricao('COMUNIDADE INDÍGENA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FUNDO PRIVADO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3247');
        $modNaturezaJuridica->setDescricao('FUNDO PRIVADO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO DE DIREÇÃO NACIONAL DE PARTIDO POLÍTICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3255');
        $modNaturezaJuridica->setDescricao('ÓRGÃO DE DIREÇÃO NACIONAL DE PARTIDO POLÍTICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO DE DIREÇÃO REGIONAL DE PARTIDO POLÍTICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3263');
        $modNaturezaJuridica->setDescricao('ÓRGÃO DE DIREÇÃO REGIONAL DE PARTIDO POLÍTICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ÓRGÃO DE DIREÇÃO LOCAL DE PARTIDO POLÍTICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3271');
        $modNaturezaJuridica->setDescricao('ÓRGÃO DE DIREÇÃO LOCAL DE PARTIDO POLÍTICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'COMITÊ FINANCEIRO DE PARTIDO POLÍTICO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3280');
        $modNaturezaJuridica->setDescricao('COMITÊ FINANCEIRO DE PARTIDO POLÍTICO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'FRENTE PLEBISCITÁRIA OU REFERENDÁRIA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3298');
        $modNaturezaJuridica->setDescricao('FRENTE PLEBISCITÁRIA OU REFERENDÁRIA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO SOCIAL (OS)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3301');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO SOCIAL (OS)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'PLANO DE BENEFÍCIOS DE PREVIDÊNCIA COMPLEMENTAR FECHADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3328');
        $modNaturezaJuridica->setDescricao('PLANO DE BENEFÍCIOS DE PREVIDÊNCIA COMPLEMENTAR FECHADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ASSOCIAÇÃO PRIVADA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('3999');
        $modNaturezaJuridica->setDescricao('ASSOCIAÇÃO PRIVADA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'EMPRESA INDIVIDUAL IMOBILIÁRIA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('4014');
        $modNaturezaJuridica->setDescricao('EMPRESA INDIVIDUAL IMOBILIÁRIA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CONTRIBUINTE INDIVIDUAL (PRODUTOR RURAL)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('4081');
        $modNaturezaJuridica->setDescricao('CONTRIBUINTE INDIVIDUAL (PRODUTOR RURAL)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'CANDIDATO A CARGO POLÍTICO ELETIVO'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('4090');
        $modNaturezaJuridica->setDescricao('CANDIDATO A CARGO POLÍTICO ELETIVO');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'PRODUTOR RURAL (PESSOA FÍSICA)'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('4120');
        $modNaturezaJuridica->setDescricao('PRODUTOR RURAL (PESSOA FÍSICA)');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANISMO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('4502');
        $modNaturezaJuridica->setDescricao('ORGANISMO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('5002');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'ORGANIZAÇÃO INTERNACIONAL'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('5010');
        $modNaturezaJuridica->setDescricao('ORGANIZAÇÃO INTERNACIONAL');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'REPRESENTAÇÃO DIPLOMÁTICA ESTRANGEIRA'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('5029');
        $modNaturezaJuridica->setDescricao('REPRESENTAÇÃO DIPLOMÁTICA ESTRANGEIRA');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

        $modNaturezaJuridica = $manager
            ->createQuery(
                "
                SELECT nj
                FROM SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica nj
                WHERE nj.descricao = 'OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'"
            )
            ->getOneOrNullResult() ?: new ModalidadeNaturezaJuridica();

        $modNaturezaJuridica->setValor('5037');
        $modNaturezaJuridica->setDescricao('OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS');
        $manager->persist($modNaturezaJuridica);
        $this->addReference(
            'ModalidadeNaturezaJuridica-'.
            $modNaturezaJuridica->getDescricao(),
            $modNaturezaJuridica
        );

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
        return ['prod', 'dev', 'test'];
    }
}

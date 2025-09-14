<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/DevModalidadeFundamentacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFundamentacao;

/**
 * Class LoadModalidadeFundamentacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeFundamentacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Atividade Empresarial');
        $modalidadeFundamentacao->setDescricao('Art. 5º, § 2º, do Decreto nº 7.724/2012');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);

        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Controle Interno');
        $modalidadeFundamentacao->setDescricao('Art. 26, § 3º, da Lei nº 10.180/2001');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Direito Autoral');
        $modalidadeFundamentacao->setDescricao('Art. 24, III, da Lei nº 9.610/1998');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Documento Preparatório');
        $modalidadeFundamentacao->setDescricao('Art. 7º, § 3º, da Lei nº 12.527/2011');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Informação Pessoal');
        $modalidadeFundamentacao->setDescricao('Art. 31 da Lei nº 12.527/2011');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Informações Privilegiadas de Sociedades Anônimas');
        $modalidadeFundamentacao->setDescricao('Art. 155, § 2º, da Lei nº 6.404/1976');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Interceptação de Comunicações Telefônicas');
        $modalidadeFundamentacao->setDescricao('Art. 8º, caput, da Lei nº 9.296/1996');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Investigação de Responsabilidade de Servidor');
        $modalidadeFundamentacao->setDescricao('Art. 150 da Lei nº 8.112/1990');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Investigação e Prevenção de Acidentes Aeronáuticos');
        $modalidadeFundamentacao->setDescricao('Art. 88-I, § 3º, da Lei nº 7.565/1986');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Investigação Preliminar sobre Mercado Mobiliário');
        $modalidadeFundamentacao->setDescricao('Art. 9º, § 2º, da Lei nº 6.385/1976');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Livros e Registros Contábeis Empresariais');
        $modalidadeFundamentacao->setDescricao('Art. 1.190 do Código Civil');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Operações Bancárias');
        $modalidadeFundamentacao->setDescricao('Art. 1º da Lei Complementar nº 105/2001');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Processo Administrativo de Responsabilização (PAR)');
        $modalidadeFundamentacao->setDescricao('Art. 4º, §1º, do Decreto nº 8.420/2015');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Proteção da Propriedade Intelectual de Software');
        $modalidadeFundamentacao->setDescricao('Art. 2º da Lei nº 9.609/1998');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Processo de Responsabilidade de Servidor');
        $modalidadeFundamentacao->setDescricao('Art. 150 da Lei nº 8.112/1990');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Protocolo - Pendente Análise de Restrição de Acesso');
        $modalidadeFundamentacao->setDescricao('Art. 6º, III, da Lei nº 12.527/2011');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Segredo de Justiça no Processo Civil');
        $modalidadeFundamentacao->setDescricao('Art. 189 do Código de Processo Civil');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Segredo de Justiça no Processo Penal');
        $modalidadeFundamentacao->setDescricao('Art. 201, § 6º, do Código de Processo Penal');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Segredo Industrial');
        $modalidadeFundamentacao->setDescricao('Art. 195, XIV, Lei nº 9.279/1996');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Sigilo das Comunicações');
        $modalidadeFundamentacao->setDescricao('Art. 3º, V, da Lei nº 9.472/1997');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Sigilo de Empresa em Situação Falimentar');
        $modalidadeFundamentacao->setDescricao('Art. 169 da Lei nº 11.101/2005');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Sigilo do Inquérito Policial');
        $modalidadeFundamentacao->setDescricao('Art. 20 do Código de Processo Penal');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Situação Econômico-Financeira de Sujeito Passivo');
        $modalidadeFundamentacao->setDescricao('Art. 198, caput, da Lei nº 5.172/1966 - CTN');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);
                        
        $modalidadeFundamentacao = new ModalidadeFundamentacao();
        $modalidadeFundamentacao->setValor('Tratados, acordos e atos internacionais');
        $modalidadeFundamentacao->setDescricao('Art. 36, Lei nº 12.527/2011');
        $manager->persist($modalidadeFundamentacao);
        $this->addReference('ModalidadeFundamentacao-'.$modalidadeFundamentacao->getValor(), $modalidadeFundamentacao);

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

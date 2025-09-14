<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use SuppCore\AdministrativoBackend\Entity\MomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128172249 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Alterando momento de disparo de regra etiqueta (re)distribuição de processo.';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->momentoDisparoRegraEtiquetaUp();
//        $this->debug();
    }

    /**
     * @return void
     */
    private function momentoDisparoRegraEtiquetaUp(): void
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                MomentoDisparoRegraEtiqueta::class,
                [
                    'nome' => 'DEFINIÇÃO/ALTERAÇÃO DO SETOR DO PROCESSO',
                    'descricao' => 'MOMENTO EM QUE É DEFINIDO OU ALTERADO O SETOR ATUAL DO PROCESSO.'
                ],
                [
                    'sigla' => SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value
                ]
            )
        );
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->momentoDisparoRegraEtiquetaDown();
//        $this->debug();
    }

    /**
     * @return void
     */
    private function momentoDisparoRegraEtiquetaDown(): void
    {
        $this->addSql(
            $this->migrationHelper->generateUpdateSQL(
                MomentoDisparoRegraEtiqueta::class,
                [
                    'nome' => '(RE)DISTRIBUIÇÃO DO PROCESSO',
                    'descricao' => 'MOMENTO EM QUE É FEITA A DISTRIBUIÇÃO OU REDISTRIBUIÇÃO DO PROCESSO.'
                ],
                [
                    'sigla' => SiglaMomentoDisparoRegraEtiqueta::PROCESSO_DISTRIBUICAO->value
                ]
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNaturezaJuridica;
use SuppCore\AdministrativoBackend\Migrations\Helpers\MigrationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Migration dos dados da tabela Modalidade Natureza Juridica (batched).
 */
final class Version20241122174816 extends AbstractMigration
{
    private ContainerInterface $container;
    private MigrationHelper|null $migrationHelper;

    // Data grouped for easier batch processing
    private const NATUREZA_JURIDICA_DATA = [
        ['valor' => '1015', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO FEDERAL'],
        ['valor' => '1023', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1031', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL'],
        ['valor' => '1040', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO FEDERAL'],
        ['valor' => '1058', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1066', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO MUNICIPAL'],
        ['valor' => '1074', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO FEDERAL'],
        ['valor' => '1082', 'descricao' => 'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO ESTADUAL'],
        ['valor' => '1090', 'descricao' => 'ORGÃO AUTONOMO DE DIREITO PÚBLICO'],
        ['valor' => '1104', 'descricao' => 'AUTARQUIA FEDERAL'],
        // Batch 1 ends (10 records)

        ['valor' => '1112', 'descricao' => 'AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1120', 'descricao' => 'AUTARQUIA MUNICIPAL'],
        ['valor' => '1139', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO FEDERAL'],
        ['valor' => '1147', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1155', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PÚBLICO MUNICIPAL'],
        ['valor' => '1163', 'descricao' => 'ÓRGÃO PÚBLICO AUTÔNOMO FEDERAL'],
        ['valor' => '1171', 'descricao' => 'ÓRGÃO PÚBLICO AUTÔNOMO ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1180', 'descricao' => 'ÓRGÃO PÚBLICO AUTÔNOMO MUNICIPAL'],
        ['valor' => '1198', 'descricao' => 'COMISSÃO POLINACIONAL'],
        ['valor' => '1201', 'descricao' => 'FUNDO PÚBLICO'],
        // Batch 2 ends (10 records)

        ['valor' => '1210', 'descricao' => 'CONSÓRCIO PÚBLICO DE DIREITO PÚBLICO (ASSOCIAÇÃO PÚBLICA)'],
        ['valor' => '1228', 'descricao' => 'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO'],
        ['valor' => '1236', 'descricao' => 'ESTADO OU DISTRITO FEDERAL'],
        ['valor' => '1244', 'descricao' => 'MUNICÍPIO'],
        ['valor' => '1252', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO FEDERAL'],
        ['valor' => '1260', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1279', 'descricao' => 'FUNDAÇÃO PÚBLICA DE DIREITO PRIVADO MUNICIPAL'],
        ['valor' => '1287', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA FEDERAL'],
        ['valor' => '1295', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1309', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA MUNICIPAL'],
        // Batch 3 ends (10 records)

        ['valor' => '1317', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA FEDERAL'],
        ['valor' => '1325', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA ESTADUAL OU DO DISTRITO FEDERAL'],
        ['valor' => '1333', 'descricao' => 'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA MUNICIPAL'],
        ['valor' => '1341', 'descricao' => 'UNIÃO'],
        ['valor' => '1996', 'descricao' => 'OUTRAS FORMAS ORGANIZAÇÃO ADMINISTRAÇÃO PUBLICA'],
        ['valor' => '2011', 'descricao' => 'EMPRESA PÚBLICA'],
        ['valor' => '2020', 'descricao' => 'EMPRESA PÚBLICA(SOCIEDADE ANONIMA DE CAPITAL FECHADO)'],
        ['valor' => '2038', 'descricao' => 'SOCIEDADE DE ECONOMIA MISTA'],
        ['valor' => '2046', 'descricao' => 'SOCIEDADE ANÔNIMA ABERTA'],
        ['valor' => '2054', 'descricao' => 'SOCIEDADE ANÔNIMA FECHADA'],
        // Batch 4 ends (10 records)

        ['valor' => '2062', 'descricao' => 'SOCIEDADE EMPRESÁRIA LIMITADA'],
        ['valor' => '2070', 'descricao' => 'SOCIEDADE EMPRESÁRIA EM NOME COLETIVO'],
        ['valor' => '2089', 'descricao' => 'SOCIEDADE EMPRESÁRIA EM COMANDITA SIMPLES'],
        ['valor' => '2097', 'descricao' => 'SOCIEDADE EMPRESÁRIA EM COMANDITA POR AÇÕES'],
        ['valor' => '2100', 'descricao' => 'SOCIEDADE MERCANTIL DE CAPITAL E INDÚSTRIA'],
        ['valor' => '2119', 'descricao' => 'SOCIEDADE CIVIL COM FINS LUCRATIVOS'],
        ['valor' => '2127', 'descricao' => 'SOCIEDADE EM CONTA DE PARTICIPAÇÃO'],
        ['valor' => '2135', 'descricao' => 'EMPRESÁRIO (INDIVIDUAL)'],
        ['valor' => '2143', 'descricao' => 'COOPERATIVA'],
        ['valor' => '2151', 'descricao' => 'CONSÓRCIO DE SOCIEDADES'],
        // Batch 5 ends (10 records)

        ['valor' => '2160', 'descricao' => 'GRUPO DE SOCIEDADES'],
        ['valor' => '2178', 'descricao' => 'ESTABELECIMENTO, NO BRASIL, DE SOCIEDADE ESTRANGEIRA'],
        ['valor' => '2194', 'descricao' => 'ESTABELECIMENTO, NO BRASIL, DE EMPRESA BINACIONAL ARGENTINO-BRASILEIRA'],
        ['valor' => '2208', 'descricao' => 'ENTIDADE BINACIONAL ITAIPU'],
        ['valor' => '2216', 'descricao' => 'EMPRESA DOMICILIADA NO EXTERIOR'],
        ['valor' => '2224', 'descricao' => 'CLUBE/FUNDO DE INVESTIMENTO'],
        ['valor' => '2232', 'descricao' => 'SOCIEDADE SIMPLES PURA'],
        ['valor' => '2240', 'descricao' => 'SOCIEDADE SIMPLES LIMITADA'],
        ['valor' => '2259', 'descricao' => 'SOCIEDADE SIMPLES EM NOME COLETIVO'],
        ['valor' => '2267', 'descricao' => 'SOCIEDADE SIMPLES EM COMANDITA SIMPLES'],
        // Batch 6 ends (10 records)

        ['valor' => '2275', 'descricao' => 'EMPRESA BINACIONAL'],
        ['valor' => '2283', 'descricao' => 'CONSÓRCIO DE EMPREGADORES'],
        ['valor' => '2291', 'descricao' => 'CONSÓRCIO SIMPLES'],
        ['valor' => '2305', 'descricao' => 'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRESÁRIA)'],
        ['valor' => '2313', 'descricao' => 'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)'],
        ['valor' => '2321', 'descricao' => 'SOCIEDADE UNIPESSOAL DE ADVOCACIA'],
        ['valor' => '2330', 'descricao' => 'COOPERATIVAS DE CONSUMO'],
        ['valor' => '2348', 'descricao' => 'EMPRESA SIMPLES DE INOVAÇÃO'],
        ['valor' => '2992', 'descricao' => 'OUTRAS FORMAS DE ORGANIZAÇÃO EMPRESARIAL'],
        ['valor' => '3018', 'descricao' => 'FUNDAÇÃO MANTIDAD COM RECURSOS PRIVADOS'],
        // Batch 7 ends (10 records)

        ['valor' => '3026', 'descricao' => 'ASSOCIAÇÃO'],
        ['valor' => '3034', 'descricao' => 'SERVIÇO NOTARIAL E REGISTRAL (CARTÓRIO)'],
        ['valor' => '3042', 'descricao' => 'ORGANIZAÇÃO SOCIAL'],
        ['valor' => '3050', 'descricao' => 'ORGANIZAÇÃO DA SOCIEDADE CIVIL DE INTERESSE PÚBLICO (OSCIP)'],
        ['valor' => '3069', 'descricao' => 'FUNDAÇÃO PRIVADA'],
        ['valor' => '3077', 'descricao' => 'SERVIÇO SOCIAL AUTÔNOMO'],
        ['valor' => '3085', 'descricao' => 'CONDOMÍNIO EDILÍCIO'],
        ['valor' => '3093', 'descricao' => 'UNIDADE EXECUTORA (PROGRAMA DINHEIRO DIRETO NA ESCOLA)'],
        ['valor' => '3107', 'descricao' => 'COMISSÃO DE CONCILIAÇÃO PRÉVIA'],
        ['valor' => '3115', 'descricao' => 'ENTIDADE DE MEDIAÇÃO E ARBITRAGEM'],
        // Batch 8 ends (10 records)

        ['valor' => '3123', 'descricao' => 'PARTIDO POLÍTICO'],
        ['valor' => '3131', 'descricao' => 'ENTIDADE SINDICAL'],
        ['valor' => '3204', 'descricao' => 'ESTABELECIMENTO, NO BRASIL, DE FUNDAÇÃO OU ASSOCIAÇÃO ESTRANGEIRAS'],
        ['valor' => '3212', 'descricao' => 'FUNDAÇÃO OU ASSOCIAÇÃO DOMICILIADA NO EXTERIOR'],
        ['valor' => '3220', 'descricao' => 'ORGANIZAÇÃO RELIGIOSA'],
        ['valor' => '3239', 'descricao' => 'COMUNIDADE INDÍGENA'],
        ['valor' => '3247', 'descricao' => 'FUNDO PRIVADO'],
        ['valor' => '3255', 'descricao' => 'ÓRGÃO DE DIREÇÃO NACIONAL DE PARTIDO POLÍTICO'],
        ['valor' => '3263', 'descricao' => 'ÓRGÃO DE DIREÇÃO REGIONAL DE PARTIDO POLÍTICO'],
        ['valor' => '3271', 'descricao' => 'ÓRGÃO DE DIREÇÃO LOCAL DE PARTIDO POLÍTICO'],
        // Batch 9 ends (10 records)

        ['valor' => '3280', 'descricao' => 'COMITÊ FINANCEIRO DE PARTIDO POLÍTICO'],
        ['valor' => '3298', 'descricao' => 'FRENTE PLEBISCITÁRIA OU REFERENDÁRIA'],
        ['valor' => '3301', 'descricao' => 'ORGANIZAÇÃO SOCIAL (OS)'],
        ['valor' => '3328', 'descricao' => 'PLANO DE BENEFÍCIOS DE PREVIDÊNCIA COMPLEMENTAR FECHADA'],
        ['valor' => '3999', 'descricao' => 'ASSOCIAÇÃO PRIVADA'],
        ['valor' => '4014', 'descricao' => 'EMPRESA INDIVIDUAL IMOBILIÁRIA'],
        ['valor' => '4081', 'descricao' => 'CONTRIBUINTE INDIVIDUAL (PRODUTOR RURAL)'],
        ['valor' => '4090', 'descricao' => 'CANDIDATO A CARGO POLÍTICO ELETIVO'],
        ['valor' => '4120', 'descricao' => 'PRODUTOR RURAL (PESSOA FÍSICA)'],
        ['valor' => '4502', 'descricao' => 'ORGANISMO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'],
        // Batch 10 ends (10 records)

        ['valor' => '5002', 'descricao' => 'ORGANIZAÇÃO INTERNACIONAL E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'],
        ['valor' => '5010', 'descricao' => 'ORGANIZAÇÃO INTERNACIONAL'],
        ['valor' => '5029', 'descricao' => 'REPRESENTAÇÃO DIPLOMÁTICA ESTRANGEIRA'],
        ['valor' => '5037', 'descricao' => 'OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS'],
        // Batch 11 ends (4 records)
    ];

    /**
     * @param ContainerInterface|null $container
     *
     * @return void
     */
    #[Required]
    public function setContainer(ContainerInterface $container = null): void
    {
        // Avoids issues if migration services are not fully available during __construct
        if ($container) {
             $this->container = $container;
             // It's safer to get the helper inside up/down,
             // but if it's always available after setContainer, this is fine.
             $this->migrationHelper = $this->container->get(MigrationHelper::class);
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Migration dos dados da tabela Modalidade Natureza Juridica (batched).';
    }

    /**
     * @param Schema $schema
     * @throws \Exception
     * @return void
     */
    public function up(Schema $schema): void
    {
        if (!$this->migrationHelper) {
            throw new \Exception('MigrationHelper service not available.');
        }

        // Define the batch size
        $batchSize = 15; // You can adjust this number (e.g., 10, 20, 50)
        $i = 0;

        $this->write('Starting migration for ModalidadeNaturezaJuridica...');

        foreach (self::NATUREZA_JURIDICA_DATA as $data) {
            $modNaturezaJuridica = new ModalidadeNaturezaJuridica();
            $modNaturezaJuridica->setValor($data['valor']);
            $modNaturezaJuridica->setDescricao($data['descricao']);
            // Assuming MigrationHelper also sets other fields like UUID, timestamps, etc.
            $this->addSql($this->migrationHelper->generateInsertSQL($modNaturezaJuridica));

            $i++;
            // Optional: Add a small log output for progress indication
            if ($i % $batchSize === 0) {
                 $this->write("Processed batch of {$batchSize} records (Total: {$i})...");
                 // If NOT transactional, you might add a small sleep here,
                 // but it's generally not needed/recommended with transactions.
                 // usleep(50000); // Sleep for 50ms
            }
        }
        $this->write("Finished migration. Total records processed: {$i}.");

    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Exception
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->write('Reverting migration for ModalidadeNaturezaJuridica...');
        $tableName = 'ad_mod_natureza_juridica'; // Make sure this is the correct table name

        // Get all the 'valor' keys to delete
        $valoresToDelete = array_map(fn($item) => $item['valor'], self::NATUREZA_JURIDICA_DATA);

        if (empty($valoresToDelete)) {
             $this->write('No values found to delete.');
             return;
        }

        // Delete in batches to avoid issues with too many parameters in the IN clause
        $batchSize = 50; // Adjust batch size for DELETE if necessary
        $batches = array_chunk($valoresToDelete, $batchSize);
        $totalDeleted = 0;

        foreach ($batches as $batch) {
            // Quote values for safety in SQL IN clause
            $quotedBatch = array_map(fn($val) => $this->connection->quote($val), $batch);
            $inClause = implode(',', $quotedBatch);
            $sql = "DELETE FROM {$tableName} WHERE valor IN ({$inClause})";
            $this->addSql($sql);
            $totalDeleted += count($batch);
            $this->write("Added DELETE statement for batch of ".count($batch)." records.");
        }

        $this->write("Finished reverting migration. Total records targeted for deletion: {$totalDeleted}.");
    }

    /**
     * IMPORTANT: Use transactions for bulk inserts for performance and atomicity.
     * Set this to false ONLY if a single transaction consistently times out
     * and you understand the implications (no rollback on partial failure).
     *
     * @return bool
     */
    public function isTransactional(): bool
    {
        return true; // Recommended: Use a single transaction
        // return false; // Use only if transaction times out & atomicity loss is acceptable
    }
}
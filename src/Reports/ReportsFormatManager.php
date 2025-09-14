<?php

namespace SuppCore\AdministrativoBackend\Reports;

use InvalidArgumentException;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;
use Twig\Environment;

class ReportsFormatManager
{
    private array $supportedFormats = [
        'html',
        'xls',
        'pdf',
    ];

    /**
     * ReportsFormatManager constructor.
     *
     * @param Environment              $twig
     * @param Pdf                      $pdfManager
     * @param CryptoManager            $cryptoManager
     * @param FilesystemManager        $filesystemManager
     * @param CompressServiceInterface $compresser
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     */
    public function __construct(protected Environment $twig,
                                protected Pdf $pdfManager,
                                protected CryptoManager $cryptoManager,
                                protected FilesystemManager $filesystemManager,
                                protected CompressServiceInterface $compresser,
                                protected EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    /**
     * @param string $format
     * @param ComponenteDigital $componenteDigital
     * @param string $nomeUsuarioGerador
     * @param string $nomeRelatorio
     * @param array $arrayData
     * @param array $arrayParameters
     * @param string|null $template
     * @return ComponenteDigital
     */
    public function exportToFormat(
        string $format,
        ComponenteDigital $componenteDigital,
        string $nomeUsuarioGerador,
        string $nomeRelatorio,
        array $arrayData,
        array $arrayParameters,
        string | null $template = null
    ): ComponenteDigital {
        return match (mb_strtolower($format)) {
            'html' => $this->getHtml(
                $componenteDigital,
                $nomeUsuarioGerador,
                $nomeRelatorio,
                $arrayData,
                $arrayParameters,
                $template
            ),
            'pdf' => $this->getPDF(
                $componenteDigital,
                $nomeUsuarioGerador,
                $nomeRelatorio,
                $arrayData,
                $arrayParameters,
                $template
            ),
            'xls', 'xlsx' => $this->getXlsx(
                $componenteDigital,
                $nomeUsuarioGerador,
                $nomeRelatorio,
                $arrayData,
                $arrayParameters,
                $template
            ),
            default => throw new InvalidArgumentException(printf('O formato %s não é suportado.', $format))
        };
    }

    /**
     * @param string|null $template
     */
    public function getHtml(
        ComponenteDigital $componenteDigital,
        string $nomeUsuarioGerador,
        string $nomeRelatorio,
        array $arrayData,
        array $arrayParameters,
        string | null $template
    ): ComponenteDigital {
        if (!$template) {
            $template = 'Resources/Relatorio/lista.html.twig';
        }
        $parametrosRender = [
            'table' => $arrayData,
            'nome' => $nomeRelatorio,
            'usuario' => $nomeUsuarioGerador,
            'parametros' => $arrayParameters,
        ];

        if ($template === 'Resources/Relatorio/registroAtividades.html.twig') {
            $parametrosRender = [
                'table' => $arrayData,
                'nome' => $nomeRelatorio,
                'parametrosRaw' => [
                    'dataHoraInicio' => $arrayParameters['dataHoraInicio']['value'],
                    'dataHoraFim' => $arrayParameters['dataHoraFim']['value'],
                    'usuario' => ($arrayParameters['usuario']['value'])->getId(),
                ],
                'parametros' => [
                    'Data Início' => $arrayParameters['dataHoraInicio']['label'],
                    'Data Fim' => $arrayParameters['dataHoraFim']['label'],
                    'Usuário' => $arrayParameters['usuario']['label'],
                ],
            ];
        }

        $componenteDigital
            ->setExtensao('html')
            ->setFileName($nomeRelatorio.'.html')
            ->setMimetype('text/html')
            ->setConteudo(
                $this->twig->render(
                    $template,
                    $parametrosRender
                )
            )
            ->setHash(hash('SHA256', $componenteDigital->getConteudo()))
            ->setTamanho(strlen($componenteDigital->getConteudo()));

        return $componenteDigital;
    }

    /**
     * @param int $decimal
     * @param array  s$baseArray
     *
     * @return string
     */
    protected function convBase($decimal, $baseArray)
    {
        $conv = '';
        $base = count($baseArray);
        while ($decimal >= $base) {
            $conv = $baseArray[$decimal % $base].$conv;
            $decimal /= $base;
        }

        return $baseArray[$decimal].$conv;
    }

    /**
     * @param $decimal
     *
     * @return string
     */
    protected function convExcel($decimal)
    {
        $letters = range('A', 'Z');
        $base = count($letters);

        $conv = '';
        while ($decimal > $base) {
            $conv = $letters[($decimal - 1) % $base].$conv;
            $decimal /= $base;
        }

        return $letters[($decimal - 1) % $base].$conv;
    }

    /**
     * Gera o relatório em PDF.
     *
     * @param string|null $template
     */
    public function getPDF(
        ComponenteDigital $componenteDigital,
        string $nomeUsuarioGerador,
        string $nomeRelatorio,
        array $arrayData,
        array $arrayParameters,
        string | null $template
    ): ComponenteDigital {
        $this->getHtml(
            $componenteDigital,
            $nomeUsuarioGerador,
            $nomeRelatorio,
            $arrayData,
            $arrayParameters,
            $template
        );

        if ($componenteDigital->getId()) {
            $filesystem = $this->filesystemManager
                ->getFilesystemService($componenteDigital)
                ->get();

            $encrypter = $this->cryptoManager->getCryptoService($componenteDigital);
            $componenteDigital->setHashAntigo($componenteDigital->getHash())
                ->setConteudo(
                $this->pdfManager->getOutputFromHtml(
                    $this->compresser->uncompress(
                        $encrypter->decrypt($filesystem->read($componenteDigital->getHashAntigo()))
                    )
                )
            );

            $this->eventoPreservacaoLogger
                ->logEPRES1Descompressao($componenteDigital)
                ->logEPRES2Decifracao($componenteDigital);
        } else {
            $componenteDigital->setHashAntigo($componenteDigital->getHash())
                ->setConteudo(
                    $this->pdfManager->getOutputFromHtml(
                        $componenteDigital->getConteudo()
                    )
                );
        }

        $componenteDigital
            ->setFileName(mb_substr($componenteDigital->getFileName(), 0, -4).'pdf')
            ->setMimetype('application/pdf')
            ->setExtensao('pdf')
            ->setHash(hash('SHA256', $componenteDigital->getConteudo()))
            ->setTamanho(strlen($componenteDigital->getConteudo()));

        return $componenteDigital;
    }

    /**
     * @param string|null $template
     */
    public function getXlsx(
        ComponenteDigital $componenteDigital,
        string $nomeUsuarioGerador,
        string $nomeRelatorio,
        array $arrayData,
        array $arrayParameters,
        string | null $template
    ): ComponenteDigital {
        $styleArrayComumns = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
                'name' => 'Liberation Serif',
            ],
        ];

        $styleArrayHeader = [
            'font' => [
                'bold' => false,
                'color' => ['rgb' => '000000'],
                'size' => 11,
                'name' => 'Liberation Serif',
            ],
        ];

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getProperties()->setCreator($nomeUsuarioGerador)
            ->setLastModifiedBy($nomeUsuarioGerador)
            ->setTitle(mb_substr($nomeRelatorio, 0, 31))
            ->setSubject('Office XLSX Document')
            ->setDescription('Relatório gerado pelo sistema SUPP')
            ->setKeywords('office openxml php')
            ->setCategory('Result file');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Relatório: {$nomeRelatorio}")
            ->setCellValue('A2', "Resposável: {$nomeUsuarioGerador}")
            ->setCellValue('A3', 'Data/Hora: '.date('d/m/Y H:i:s'))
            ->setCellValue('A4', '')
            ->setCellValue('A5', 'Parâmetros: ');
        //            ->getRowDimension(1)->setRowHeight(50);

        // Processa parâmetros
        $currentLine = 6;
        foreach ($arrayParameters as $label => $value) {
            if (is_array($value['label'])){
                $labelvalue = implode(",", $value['label']);
            }
            else{
                $labelvalue = $value['label'];
            }
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$currentLine", "$label: ".$labelvalue);
            ++$currentLine;
        }

        $objPHPExcel->getActiveSheet()
            ->getStyle("A1:A$currentLine")
            ->applyFromArray($styleArrayHeader);

        $objPHPExcel->getActiveSheet()
            ->getStyle("B1:B$currentLine")
            ->applyFromArray($styleArrayHeader);

        ++$currentLine;
        $columns = empty($arrayData) ? ['Nenhum registro'] : array_keys($arrayData[0]);

        if ($columns) {
            $i = 1;
            foreach ($columns as $column) {
                $colRef = $this->convExcel($i);

                $objPHPExcel->getActiveSheet()
                    ->setCellValue([$i, $currentLine], ucfirst($column))
                    ->getColumnDimension($colRef)->setAutoSize(true);
                ++$i;
            }
            $objPHPExcel->getActiveSheet()
                ->getStyle("A{$currentLine}:{$colRef}{$currentLine}")
                ->applyFromArray($styleArrayComumns);
            $objPHPExcel->getActiveSheet()
                ->getRowDimension(7)
                ->setRowHeight(-1);
        }
        ++$currentLine;

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->fromArray($arrayData, null, "A$currentLine");

        foreach ($arrayData as $excelData) {
            $column = 1;
            foreach ($excelData as $field => $value) {
                if ($value instanceof \DateTime) {
                    $value = $value->format('d/m/Y H:i:s');
                }
                $objPHPExcel->getActiveSheet()
                    ->getCell([$column, $currentLine])
                    ->setValueExplicit($value, DataType::TYPE_STRING);
                ++$column;
            }
            ++$currentLine;
        }

        $objPHPExcel->getActiveSheet()->setTitle(mb_substr($nomeRelatorio, 0, 31));

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

        $filePath =
            rtrim(sys_get_temp_dir())
            .DIRECTORY_SEPARATOR
            .uniqid('supp_report_', true)
            .'.xlsx';

        $objWriter->save($filePath);

        $content = file_get_contents($filePath);

        unlink($filePath);

        $componenteDigital
            ->setExtensao('xlsx')
            ->setFileName($nomeRelatorio.'.xlsx')
            ->setMimetype('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setConteudo($content)
            ->setHash(hash('SHA256', $componenteDigital->getConteudo()))
            ->setTamanho(strlen($componenteDigital->getConteudo()));

        return $componenteDigital;
    }
}

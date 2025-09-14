<?php /** @noinspection LongLine */

declare(strict_types=1);
/**
 * /src/Twig/AppExtension.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Twig;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\Barcode39 as barcode;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use SuppCore\AdministrativoBackend\Utils\CalculoEndereco;
use SuppCore\AdministrativoBackend\Entity\Endereco as EnderecoEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;

/**
 * Class AppExtension.
 */
class AppExtension extends AbstractExtension
{
    use CalculoEndereco;

    public const VALOR_RESIDUAL_MINIMO = 0.20;

    private static int $counter = 1;

    protected array $barcodes = [
        0 => '00110',
        1 => '10001',
        2 => '01001',
        3 => '11000',
        4 => '00101',
        5 => '10100',
        6 => '01100',
        7 => '00011',
        8 => '10010',
        9 => '01010',
    ];
    protected int    $barcodeHeight;
    protected int    $barcodeThin;
    protected int    $barcodeThick;
    protected string $barcodeBlack;
    protected string $barcodeWhite;

    /**
     * AppExtension constructor.
     */
    public function __construct(
        protected readonly SuppParameterBag $parameterBag,
        protected readonly AfastamentoResource $afastamentoResource,
        protected readonly ContainerInterface $container,
    ) {
        // initialize barcodes data
        for ($f1 = 9; $f1 >= 0; --$f1) {
            for ($f2 = 9; $f2 >= 0; --$f2) {
                $code = '';
                for ($i = 0; $i < 5; ++$i) {
                    $code .= substr($this->barcodes[$f1], $i, 1).substr($this->barcodes[$f2], $i, 1);
                }
                $num = ($f1 * 10) + $f2;
                $this->barcodes[$num] = $code;
            }
        }
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[]
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getParameter', [$this, 'getParameter']),
            new TwigFunction('getConfigModulo', [$this, 'getConfigModulo']),
            new TwigFunction('get_afastamentos', [$this, 'getAfastamentos']),
            new TwigFunction('formatCabecalho', [$this, 'formatCabecalho']),
            new TwigFunction('incrementItem', [$this, 'incrementItem']),
        ];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('toEntity', [$this, 'toEntity']),
            new TwigFilter('drawBarCodeEtiqueta', [$this, 'drawBarCodeEtiqueta']),
            new TwigFilter('drawBarCode', [$this, 'drawBarCode']),
            new TwigFilter('formatStringDate', [$this, 'formatStringDate']),
            new TwigFilter('formatCurrency', [$this, 'formatCurrency']),
            new TwigFilter('formatMoeda', [$this, 'formatMoeda']),
            new TwigFilter('onlyNumbers', [$this, 'onlyNumbers']),
            new TwigFilter('formatEndereco', [$this, 'formatEndereco']),
            new TwigFilter('enderecoPrincipal', [$this, 'enderecoPrincipal']),
            new TwigFilter('formatCpfCnpj', [$this, 'formatCpfCnpj']),
            new TwigFilter('formatCpf', [$this, 'formatCpf']),
            new TwigFilter('formatCnpj', [$this, 'formatCnpj']),
            new TwigFilter('formatNui', [$this, 'formatNui']),
            new TwigFilter('formatNup', [$this, 'formatNup']),
            new TwigFilter('formatNb', [$this, 'formatNb']),
            new TwigFilter('formatPercentual', [$this, 'formatPercentual']),
            new TwigFilter('formatNumeroUnicoCNJ', [$this, 'formatNumeroUnicoCNJ']),
            new TwigFilter('recuperaEntityByField', [$this, 'recuperaEntityByField']),
            new TwigFilter('formatNumeroBeneficio', [$this, 'formatNumeroBeneficio']),
            new TwigFilter('formatValorMoedaPorExtenso', [$this, 'formatValorMoedaPorExtenso']),
        ];
    }

    /**
     * @param string|int|null $id
     * @param string $bundle
     * @param string $entity
     * @return EntityInterface
     * @throws Exception
     */
    public function toEntity(string|int|null $id, string $bundle, string $entity): EntityInterface
    {
        if ($id === null || $id === '') {
            throw new Exception('Identificador nao fornecido');
        }

        $fqdn = "SuppCore\\{$bundle}Backend\\Api\\V1\\Resource\\{$entity}Resource";

        /** @var RestResource $resource */
        $resource = $this->container->get($fqdn);

        if (!$resource) {
            throw new Exception('Resource nao encontrado');
        }

        return $resource->findOne(intval($id)) ?: throw new Exception('Entity nao encontrada');
    }

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function onlyNumbers(?string $numero): string
    {
        return (null !== $numero) ?
            preg_replace('/[^0-9]/', '', $numero) :
            '';
    }

    /**
     * @param EnderecoEntity|null $enderecoEntity
     *
     * @return string
     */
    public function formatEndereco(?EnderecoEntity $enderecoEntity): string
    {
        $endereco = '';
        if (!$enderecoEntity) {
            return $endereco;
        }

        $endereco .= $enderecoEntity->getLogradouro() ?: '';
        $endereco .= $enderecoEntity->getNumero() ? ", {$enderecoEntity->getNumero()}" : '';
        $endereco .= $enderecoEntity->getComplemento() ? ", {$enderecoEntity->getComplemento()}" : '';
        $endereco .= $enderecoEntity->getBairro() ? ", {$enderecoEntity->getBairro()}" : '';
        $endereco .= $enderecoEntity->getMunicipio() ? ", {$enderecoEntity->getMunicipio()->getNome()}/{$enderecoEntity->getMunicipio()->getEstado()->getUf()}" : '';
        $endereco .= $enderecoEntity->getCep() ? ", CEP {$enderecoEntity->getCep()}" : '';

        return $endereco;
    }

    /**
     * @param PessoaEntity $pessoa
     *
     * @return EnderecoEntity|null
     */
    public function enderecoPrincipal(PessoaEntity $pessoa): ?EnderecoEntity
    {
        return $this->enderecoComMunicipio($pessoa);
    }

    /**
     * @param $usuarioId
     * @return array
     */
    public function getAfastamentos($usuarioId): array
    {
        return $this->afastamentoResource->find(['colaborador.usuario.id' => 'eq:' . $usuarioId]);
    }

    /*
 * Numero completo do credito com a seguinte definicao
 * [0].[000].[000000]/[00]-[00] 14 digitos
 * (1)   (2)    (3)      (4)    (5)
 * (1) = 1 digito  - tipo de identificador [1 creditos sisdat | 2 creditos importados | 3 TDA | 4 CDA | 5 Parcelamento]
 * (2) = 3 digitos - (credor) codificacao da autarquia
 * (3) = 6 digitos - numero sequencial
 * (4) = 6 digitos - ddmmaa - ano da inclusao do credito
 * (5) = 2 digitos - digito verificador
 */
    public function formatNui(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);
        if (14 === strlen($numero)) {
            return substr($numero, 0, 1).'.'.substr($numero, 1, 3).'.'.
                substr($numero, 4, 6).'/'.substr($numero, 10, 2).'-'.
                substr($numero, 12, 2);
        } else {
            return $numero;
        }
    }

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function formatNumeroUnicoCNJ(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);
        if (in_array(mb_strlen($numero, 'UTF-8'), [20, 22])) {
            $numeroFormatado = substr($numero, 0, 7).'-'.
                substr($numero, 7, 2).'.'.
                substr($numero, 9, 4).'.'.
                substr($numero, 13, 1).'.'.
                substr($numero, 14, 2).'.'.
                substr($numero, 16, 4);

            if (22 == mb_strlen($numero, 'UTF-8')) {
                $numeroFormatado .= '/'.substr($numero, 20, 2);
            }

            return $numeroFormatado;
        } else {
            return $numero;
        }
    }

    /*
     * PORTARIA MPOG No 3, DE 16 DE MAIO DE 2003
     *
     * Art. 6o O número único atribuído aa pasta, quando da sua autuação, será constituído
     * de quinze dígitos, devendo, ainda, ser acrescido de mais dois dígitos de verificação (DV) e, com o
     * acréscimo dos dígitos verificadores, o número atribuído aa pasta será composto por dezessete
     * dígitos, separados em grupos (00000.000000/0000-00), conforme descrito abaixo:
     * I - o primeiro grupo é constituído de cinco dígitos, referentes ao código numérico
     * atribuído a cada unidade protocolizadora e este código identifica o órgão de origem da pasta,
     * mantendo-se inalterado, de acordo com as faixas numéricas determinadas no art. 3o;
     * II - o segundo grupo é constituído de seis dígitos, separados do primeiro por um ponto e
     * determina o registro seqüencial dos pastas autuados, devendo este número ser reiniciado a cada
     * ano;III - o terceiro grupo, constituído de quatro dígitos, separado do segundo grupo por uma
     * barra, indica o ano de formação da pasta; e
     * IV - o quarto grupo, constituído de dois dígitos, separado do terceiro grupo por hífen,
     * indica os Dígitos Verificadores (DV), utilizados pelos órgãos que façam uso de rotinas automatizadas.
     *
     *
     */

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function formatNup(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);
        if (17 === strlen($numero)) {
            return substr($numero, 0, 5).'.'.substr($numero, 5, 6).'/'.
                substr($numero, 11, 4).'-'.substr($numero, 15, 2);
        } elseif (15 === strlen($numero)) {
            return substr($numero, 0, 5).'.'.substr($numero, 5, 6).'/'.
                substr($numero, 11, 2).'-'.substr($numero, 13, 2);
        } else {
            return $numero;
        }
    }

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function formatNb(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);
        return substr($numero, 0, 3).'.'.substr($numero, 3, 3).'.'.
            substr($numero, 6, 3).'-'.substr($numero, 9, 1);
    }

    /**
     * @param string|null $numero
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function formatCpfCnpj(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);

        return strlen($numero) > 11 ? $this->formatCnpj($numero) : $this->formatCpf($numero);
    }

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function formatCpf(?string $numero): string
    {
        $numero = $this->onlyNumbers($numero);
        if (strlen($numero) <= 11) {
            $numero = str_pad($numero, 11, '0', STR_PAD_LEFT);

            return substr($numero, 0, 3).'.'.substr($numero, 3, 3).'.'.substr($numero, 6, 3).'-'.substr($numero, 9, 2);
        } else {
            return $numero;
        }
    }

    /**
     * @param string|null $numero
     *
     * @return string|null
     */
    public function formatCnpj(?string $numero): ?string
    {
        $numero = $this->onlyNumbers($numero);
        if (strlen($numero) <= 14) {
            $numero = str_pad($numero, 14, '0', STR_PAD_LEFT);

            return substr($numero, 0, 2).'.'.substr($numero, 2, 3).'.'.substr($numero, 5, 3).'/'.substr($numero, 8, 4).'-'.substr($numero, 12, 2);
        } else {
            return $numero;
        }
    }

    /**
     * @param float|null $percent
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function formatPercentual(?float $percent): string
    {
        $percent = (null === $percent) ? 0.0 : $percent;
        $str = number_format(round(($percent * 100), 2), 2, ',', '.');

        return "$str%";
    }

    /**
     * @return DateTime|false
     */
    private static function b(string $data): DateTime|bool
    {
        return DateTime::createFromFormat('d/m/Y H:i:s', "$data 00:00:000");
    }

    /**
     * @return DateTime|false
     */
    private static function e(string $data): DateTime|bool
    {
        return DateTime::createFromFormat('d/m/Y H:i:s', "$data 23:59:59");
    }

    public function formatCurrency(mixed $valor, DateTime $data = null): string
    {
        $data ??= new DateTime();
        $valor = !is_float($valor) ? floatval($valor) : $valor;
        $moeda = array_filter(
            [
                [self::b('01/01/1801'), self::e('07/10/1833'),  '.', ',', 'R'],
                [self::b('08/10/1833'), self::e('31/10/1942'),  '$', ',', 'Rs'],
                [self::b('01/11/1942'), self::e('30/11/1964'),  '.', ',', 'Cr$'],
                [self::b('01/12/1964'), self::e('12/02/1967'),  '.', ',', 'Cr$'],
                [self::b('13/02/1967'), self::e('14/05/1970'),  '.', ',', 'NCr$'],
                [self::b('15/05/1970'), self::e('14/08/1984'),  '.', ',', 'Cr$'],
                [self::b('15/08/1984'), self::e('27/02/1986'),  '.', ',', 'Cr$'],
                [self::b('28/02/1986'), self::e('15/01/1989'),  '.', ',', 'Cz$'],
                [self::b('16/01/1989'), self::e('15/03/1990'),  '.', ',', 'NCz$'],
                [self::b('16/03/1990'), self::e('31/07/1993'),  '.', ',', 'Cr$'],
                [self::b('01/08/1993'), self::e('30/06/1994'),  '.', ',', 'CR$'],
                [self::b('01/07/1994'), self::e('01/01/2511'),  '.', ',', 'R$'],
            ],
            function ($d) use ($data) {
                return $data >= $d[0] && $data <= $d[1];
            }
        );

        /* @noinspection PhpAssignmentInConditionInspection */
        [$cifra, $thousandSeparator, $decimalSeparator] = ($m = reset($moeda)) ?
            array_slice($m, -3) :
            ['', '.', ','];

        return sprintf(
            '%s %s',
            $cifra,
            number_format(round($valor, 2), 2, $decimalSeparator, $thousandSeparator)
        );
    }

    /**
     * @param float|null $value
     * @param bool       $desprezaValorInferiorResiduoMinimo
     *
     * @return string
     */
    public function formatMoeda(
        ?float $value,
        bool $desprezaValorInferiorResiduoMinimo = false
    ): string {
        $value = (null === $value) ? 0.0 : $value;
        if ($desprezaValorInferiorResiduoMinimo && (abs($value) <= self::VALOR_RESIDUAL_MINIMO)) {
            $value = 0.0;
        }
        return "R$ " . number_format(round($value, 2), 2, ',', '.');
    }

    /**
     * @param string|null $date
     * @param string $format
     *
     * @return string
     */
    public function formatStringDate(?string $date, string $format = 'dmY'): string
    {
        $dateObject = DateTime::createFromFormat($format, $date);

        return $dateObject ? $dateObject->format('d/m/Y') : '';
    }

    /**
     * @param $code
     */
    public function drawBarCodeEtiqueta($code): void
    {
        $barcode = new barcode('*'.$code.'*');
        $barcode->draw();
    }

    /**
     * @param $barCode
     * @param int $height
     * @param int $thinWidth
     * @param int $thickWidth
     *
     * @return string
     */
    public function drawBarCode($barCode, int $height = 50, int $thinWidth = 1, int $thickWidth = 3): string
    {
        $this->barcodeHeight = $height;
        $this->barcodeThin = $thinWidth;
        $this->barcodeThick = $thickWidth;
        $this->barcodeBlack = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAABQAQMAAAAa6XZvAAAAA1BMVEUAAACnej3aAAAADElEQVR42mNgGFkAAADwAAE4aVpRAAAAAElFTkSuQmCC';
        $this->barcodeWhite = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAABQAQMAAAAa6XZvAAAAA1BMVEX///+nxBvIAAAADElEQVR42mNgGFkAAADwAAE4aVpRAAAAAElFTkSuQmCC';

        $html  = $this->barHtml('black', 'thin');
        $html .= $this->barHtml('white', 'thin');
        $html .= $this->barHtml('black', 'thin');
        $html .= $this->barHtml('white', 'thin');

        $code = $barCode;
        if (0 != (strlen($code) % 2)) {
            $code = '0'.$code;
        }

        while (strlen($code) > 0) {
            $two_digits = substr($code, 0, 2);
            $code = substr($code, 2);
            $lines = $this->barcodes[intval($two_digits)];
            for ($i = 0; $i < 10; ++$i) {
                $color = ($i % 2 ? 'white' : 'black');
                $width = ('0' == substr($lines, $i, 1) ? 'thin' : 'thick');
                $html .= $this->barHtml($color, $width);
            }
        }

        $html .= $this->barHtml('black', 'thick');
        $html .= $this->barHtml('white', 'thin');
        $html .= $this->barHtml('black', 'thin');

        return $html;
    }

    /**
     * @param string $color
     * @param string $width
     *
     * @return string
     */
    protected function barHtml(string $color, string $width): string
    {
        /* @noinspection HtmlUnknownTarget */
        return sprintf(
            '<img src="%s" alt="" width="%d" height="%d" />',
            ('white' === $color ? $this->barcodeWhite : $this->barcodeBlack),
            ('thin' === $width ? $this->barcodeThin : $this->barcodeThick),
            $this->barcodeHeight
        );
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    public function getParameter(string $parameter): string
    {
        return $this->parameterBag->has($parameter) ? $this->parameterBag->get($parameter) : '';
    }

    /**
     *
     * @param string $parameter
     * @param string $property
     * @return string
     * @throws Exception
     */
    public function getConfigModulo(string $parameter, string $property): mixed
    {
        try {
            // Uso do PropertyAccessor aqui que possibilita a busca de valores em array/objeto usando dot notation
            // por exemplo, buscar propriedade em a.b.c de um objeto $a
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $config = json_decode(json_encode($this->parameterBag->get($parameter)));
            return $propertyAccessor->getValue($config, $property);
        } catch (Exception $e) {
            throw new Exception("Não foi possível recuperar a propriedade $property da configuração $parameter");
        }
    }

    /**
     * @param SetorEntity|null $setorEntity
     * @param bool $showLogo
     *
     * @return string
     */
    public function formatCabecalho(?SetorEntity $setorEntity = null, bool $showLogo = false): string
    {
        $unidadeEntity = $setorEntity?->getUnidade();
        $cabecalho =
            ($showLogo ? $this->parameterBag->get('supp_core.administrativo_backend.logo_instituicao') : '').
            ($nomeInstituicao = "<br> {$this->parameterBag->get('supp_core.administrativo_backend.nome_instituicao')}");

        if ($unidadeEntity) {
            /** @noinspection PhpAssignmentInConditionInspection */
            if ($modalidadeOrgaoCentral = $unidadeEntity->getModalidadeOrgaoCentral()) {
                if ($nomeInstituicao !== $modalidadeOrgaoCentral->getDescricao()) {
                    $cabecalho .= "<br> {$modalidadeOrgaoCentral->getDescricao()}";
                }
                if ($unidadeEntity->getNome() !== $modalidadeOrgaoCentral->getDescricao()) {
                    $cabecalho .= "<br> {$unidadeEntity->getNome()}";
                }
            } elseif ($nomeInstituicao !== $unidadeEntity->getNome()) {
                $cabecalho .= "<br> {$unidadeEntity->getNome()}";
            }
        }

        return "$cabecalho <hr/>";
    }

    /**
     * @param string|int|null $field
     * @param string $nameField
     * @param string $bundle
     * @param string $entity
     *
     * @return EntityInterface|null
     *
     * @throws Exception
     */
    public function recuperaEntityByField(
        string|int|null $field,
        string $nameField,
        string $bundle,
        string $entity
    ): ?EntityInterface {
        if ($field === '' || $field === null) {
            return null;
        }
        $fqdn = "SuppCore\\{$bundle}Backend\\Api\\V1\\Resource\\{$entity}Resource";
        /** @var RestResource $resource */
        $resource = $this->container->get($fqdn);
        if (!$resource) {
            throw new Exception('Resource nao encontrado');
        }

        return $resource->findOneBy([$nameField => $field]) ?: null;
    }

    /**
     * @param string|null $numero
     *
     * @return string
     */
    public function formatNumeroBeneficio(?string $numero): string
    {
        $nbValidationRegex = "/^([0-9]{1}.)?([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{1})$/";
        if (preg_match($nbValidationRegex, $numero)) {
            return $numero;
        }

        $numBeneficioFormatado = [];
        $onlyNumberRegex = "/[^0-9]/";
        $numBeneficio = array_reverse(str_split(preg_replace($onlyNumberRegex, '', $numero)));

        foreach ($numBeneficio as $key => $nb) {
            $numBeneficioFormatado[] = $nb;

            if ($key == 0) {
                $numBeneficioFormatado[] = '-';
            }

            if ($key > 0 && $key < sizeof($numBeneficio) - 1 && $key % 3 == 0) {
                $numBeneficioFormatado[] = '.';
            }
        }

        return implode(array_reverse($numBeneficioFormatado));
    }

    /**
     * @return int
     */
    public function incrementItem(?bool $increment = true): int
    {
        if ($increment)
            return self::$counter++;
        else
            return self::$counter - 1;
    }

    /**
     * @param float|null $numero
     *
     * @return string
     */
    public function formatValorMoedaPorExtenso(?float $numero = 0): string
    {
        $partes = explode('.', number_format($numero, 2, '.', ''));

        $formatador = new \NumberFormatter('pt_BR', \NumberFormatter::SPELLOUT);

        $reais = $formatador->format((float)$partes[0]);
        // remova a palavra zero do início, se existir
        $reais = preg_replace('/^zero/i', '', $reais);

        if (trim($reais) !== '') {
            // acrescenta ' de' quando terminar em ilhão ou ilhões
            // ex: três milhões de reais
            $reais .= preg_match('/(ilhão|ilhões)$/i', $reais) ? ' de' : '';
            // pluralização
            $reais .= $reais === 'um' ? ' real' : ' reais';
        }

        $centavos = '';
        if (isset($partes[1])) {
            $centavos = $formatador->format((float)$partes[1]);
            // remova a palavra zero do início, se existir
            $centavos = preg_replace('/^zero/i', '', $centavos);

            if (trim($centavos) !== '') {
                // acrescenta ' e ' no caso de inteiros(reais) e decimais(centavos) existirem
                // ex: um real e um centavo
                if (trim($reais) !== '') {
                    $reais .= ' e ';
                }
                // pluralização
                $centavos .= $centavos === 'um' ? ' centavo' : ' centavos';
            }
        }

        // formata o resultado final
        $resultado = $reais . $centavos;
        if ($resultado === '') {
            $resultado = 'zero';
        }

        return $resultado;
    }
}

<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use DateTime;
use DateTimeZone;
use Exception;
use Knp\Snappy\Pdf;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Smalot\PdfParser\Document;
use SuppCore\AdministrativoBackend\Twig\AppExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;
use Symfony\Polyfill\Intl\Normalizer\Normalizer;

/**
 * Class PDFTools.
 */
class PDFTools implements PDFToolsInterface
{
    public const DEFAULT_TIMEOUT = 120;
    public const SIGNATURE_MAX_LENGTH = 18944;// 9472;

    private float $timeout = self::DEFAULT_TIMEOUT;

    /**
     * PDFTools constructor.
     *
     * @param LoggerInterface  $logger
     * @param Pdf              $pdfManager
     * @param PdfParserService $pdfParserService
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Pdf $pdfManager,
        private readonly PdfParserService $pdfParserService,
        private readonly AppExtension $appExtension,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @param float $timeout
     *
     * @return PDFToolsInterface
     */
    public function setTimeout(float $timeout): PDFToolsInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Informa se existem imagens dentro do PDF.
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasImages(string $path): bool
    {
        $process = Process::fromShellCommandline(
            "pdfimages $path -list",
            timeout: $this->timeout
        );
        $process->setTimeout($this->timeout);
        $resultCode = $process->run();
        $process->wait();

        if (0 !== $resultCode) {
            $this->logger->error($process->getErrorOutput());
            throw new RuntimeException('Erro ao verificar imagens do PDF.');
        }

        return substr_count($process->getOutput(), "\n") > 1;
    }

    /**
     * Converte um PDF para Imagem.
     *
     * @param string $path
     * @param string $output
     */
    public function pdfToImage(string $path, string $output): void
    {
        $process = Process::fromShellCommandline(
            'convert'
            .' -density 300'
            ." $path"
            .' -depth 8'
            .' -quality 90'
            .' -strip'
            .' -background white'
            .' -alpha off'
            .' -append'
            ." $output",
            timeout: $this->timeout
        );
        $resultCode = $process->run();
        $process->wait();

        if (0 !== $resultCode) {
            $this->logger->error($process->getErrorOutput());
            throw new RuntimeException('Erro ao converter PDF em imagem.');
        }
    }

    /**
     * Extrai o texto do PDF.
     *
     * @param string $path
     *
     * @return string
     */
    public function getText(string $path): string
    {
        $process = Process::fromShellCommandline(
            "pdftotext -layout $path -",
            timeout: $this->timeout
        );
        $resultCode = $process->run();
        $process->wait();

        if (0 !== $resultCode) {
            $this->logger->error($process->getErrorOutput());
            throw new RuntimeException('Erro ao extrair texto do PDF.');
        }

        return trim($process->getOutput());
    }

    /**
     * Informa se o pdf contem texto.
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasText(string $path): bool
    {
        return !empty(trim(preg_replace('/\s+/S', ' ', $this->getText($path))));
    }

    /**
     * Convert text to PDF.
     *
     * @param string $text
     *
     * @return string
     */
    public function textToPDF(string $text): string
    {
        return $this->pdfManager->getOutputFromHtml($text);
    }

    /**
     * Retirar acentos e abreviar.
     *
     * @param string $nome
     *
     * @return string
     */
    public function formatarNome(string $nome): string
    {
        $nomeNormalizado = Normalizer::normalize($nome, Normalizer::NFD);
        $nomeNormalizado = preg_replace('/[^a-zA-Z0-9\s]/', '', $nomeNormalizado);
        // $arrayNome = explode(' ', ucwords(strtolower($nomeNormalizado)));
        $arrayNome = explode(' ', strtoupper($nomeNormalizado));
        if (count($arrayNome) > 2) {
            $primeiroNome = array_shift($arrayNome).' ';
            $ultimoSobrenome = array_pop($arrayNome);
            $abreviados = '';
            foreach ($arrayNome as $sobrenome) {
                if (strlen($sobrenome) > 3) {
                    $abreviados .= substr($sobrenome, 0, 1).'. ';
                } else {
                    $abreviados .= $sobrenome.' ';
                }
            }

            return $primeiroNome.$abreviados.$ultimoSobrenome;
        } else {
            return $nome;
        }
    }

    /**
     * Remove todas as assinaturas do PDF.
     * Encontra o primeiro fim de arquivo e descarta o resto.
     *
     * @param mixed $pdfContent
     *
     * @return string
     */
    public function removeAllSignatures(mixed $pdfContent): string
    {
        $matches = [];
        // Limitar a busca do %%EOF até a posição da primeira assinatura
        $resultadoSig = preg_match_all("/\/Type[ \r\n]+\/Sig/i", $pdfContent, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        if ($resultadoSig > 0) {
            $pdfContentTemp = mb_substr($pdfContent, 0, $matches[0][0][1], '8bit');
        } else {
            // Não foi possível encontrar assinatura(s) no PDF!
            return $pdfContent;
        }

        // Encontrar o último fim de arquivo antes da primeira assinatura, pois podem existir vários %EOF antes da assinatura
        $resultado = preg_match_all('/%%EOF/i', $pdfContentTemp, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        if ($resultado > 0) {
            return mb_substr($pdfContent, 0, end($matches)[0][1] + 5, '8bit');
        } else {
            throw new RuntimeException('Erro ao remover assinaturas do PDF. Fim de arquivo não encontrado!');
        }
    }

    /**
     * Remove todas as assinaturas do PDF.
     *
     * @param mixed $pdfContent
     *
     * @return string
     */
    public function removeLastSignature(mixed $pdfContent): string
    {
        $matches = [];
        $resultado = preg_match_all('/%%EOF/i', $pdfContent, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        if ($resultado > 0) {
            return mb_substr($pdfContent, 0, array_pop($matches)[0][1] + 5, '8bit');
        } else {
            throw new RuntimeException('Erro ao remover assinaturas do PDF.');
        }
    }

    /**
     * Retorna a font ttf em bytes.
     *
     * Obs: Fonte OpenSans-Regular em Deflate/Flate stream
     *
     * @return string|false
     */
    protected function getStreamFontTTF(): string|false
    {
        $fontBase64 = 'eJyMfQdgVMXW8Mxt23vLpm+WTUgChGRTCC1LS6NFCMgiIoQiRaSDyEMEDEWa9CIgIEaMiIihyEMUUREREbEhKg+x68fD8p4I2eE/M/fuZjfC9/3GZXfvnXtm5syZ0+acswgjhCzwj4DMZd17lOr+of8I4ZnT4Gqbsqq+/ZEb6eH7LvhuLus/oOuVlzMPw/cGhLirffvn5O0o3HYK4bIA3B82YsLwSXv/+Z+b8H07QvziETOmpZp6x+1CuP8PcH/n6En3T3hk+EELwj1bIqRquH/41EnIgbwAbySFf/8Ds0ZPa7epJ0I4EeGchjGjho/kjoyYhHC7crhfOAYuGN7TTAX4ifC9xZgJ0x76oaDjl3D/UYSMmx+YOGJ4pv2LXxFuew3gD5ww/KFJYjdOC98/gPapDw6fMOqe1Y+mIFy9DMb34aSJU6fNPayZiPCgYQil8JOmjJrU7dB6GG+/L2AMGsSh44AYrXgQ8UiFHIcEXuQEXoVQTl6OH+f4c/y5bW0ei6cIXsf5isZDY7hZoUXiwRuVY4Tv4OmNt67gJexpI8oO2HmNhhMEkxljPaeX+gb1To5HJSXZFisqjsuxWHGxxe+3AEzs5f18vj/P6bBL3rR0XDrWf/byQ+1LAsX53fEawXujYXFp10BZCSwCquXruH3KCFMCBsSrBF5QayROxQNw/5k8ChcAM7i8l/fAC5e0HJvJZWeOyRQPhq5xZvqisPww2/8CrASUgjYFBqhTEh2CXWPR6zVWk1EnaG02uzMpWSVILgEjtxAnipJd0nr4eBcfp41L9agFvUHfJ5iIDSnIbDH3Cbodlr4G3NeCDRaDRXTZeK2IcvwlfmtxcU7OvffCdLMtiM5ZeWdDdcE7G7HVVcxe7FNenvxOZ+FxwCxs7FXgYS8/z14ODF/5j7viBHKmekk1OddvcRW5gVO6k59xdr+l/XBu9cJqrG78Fud0Jef4BWTPPNIP76Wvebh6Lm4gvehrLtmDqwGjC24tEfSSFaWidNQajQ4UZFh8SS5ByLIbRA9CbtGiEdvk+AySoXeQl1oYs4y9gklZ2Yn2xN5BV5YdkGMXnTDjHDZfa3EenaXFH/WvFbP5+f0MCfIi2SWVw1uQlp5R4HT6LenpBfmFRQV+h9OlSs+wJHOqfHgrxHanyyJJgv69Q/Mmfdit/4XgmWdOPzvv8PP56zZv3VJZH3z0QujLwRNHjMbHF7/k+uGSN2WLLwcf7rJn8YLd1oMNYo8FHXSkT959s0eVB1uR2cm8qteQTLzAfC9CIhpz6xeptXgaaWB3emDmuahHoAUyi1pny8S0tDYttW3NUp6fN6RkG+Cvba7UNs7tc6tsKiA4+Cth06XTisxPpj8gH3+exexNk0RG2DAzIHJMrxYW5Kc3v469+LfK/rt29a/E725au+zJdatXbcF1ldXVVVXV1ZX49Ka1KzatW73iKUIaP17DZwtcfT2uxv1213/z49VLV7672njx+eeefeH5Z555/sqPV7+68t3PfOqNSiBvWNnJt34Rz4tnkA5mWIDuCrS1OVGS5M3KbpPd2mtMdyc5NYVFfn1F0G8zZbQ2thVzeHc6n5WVmmNN1VQGUwVUko3iYKI5rmIgSrqHY2ZKl6dQmZMtrwgbOYfd6YOFbMPJk4OdrcKF0Aenwt4MiW3zbeV3zxt1/8DgxCd/fYr0nDik1ZPklaUNAzq1ePPFnUcWb8Ub2nVz7e6+BGd/+8qMP9Z/+j/Cmh5zBvWc27/38GE3t27Gu7sHR3eZvuTG/HdG31czrnj97mfXjT9wL5nV+dkR5Ou15Mv944Z8BGyNcgxcwjiG/RDieUHkKKc4I/MfmUkwBiHzBvpEP3KU88ATBpQY0KuQVkCC0aTLoI+VRLEXm9la5JdgplaXN53r9+TqnU+sWrt0+5rNXC7W4Pf3Hid5f1wjha/W47cp1E4AVR+GKmgBLjKatLyQEQsVmzmVt9BakM9l+J1WTv/k6u1L1656YicFS/4i7Xcfxaev/YHfP/4iyQWoA7m5glGyA8dtG0jgRQGrDFqNTmMyC1ps4PR6gxb4l9qIUMmbeZEVo4tHZ+5ziTbgmxk2X5HIc6uy8Yp4suD6nn3b9/1GFiXhRdmSnUyfeDCFHBmKx5H1Q3FpysGJeCmdzRh0RcgUTgBFtQzYkCBoRFFv4FVqVVUQmdRYjXLuDW/5CJsHPmbxWoB/WfzccryFjFxKRuPNS3nX42Qgrn8c7wG4JeQ6noCuIjWyHBCRBhgoymFrBQP2uSSGmyI81BRfUzAnoYvhqmsk+XPKGJw3BJ6txhe4Em4yrLTlAIdEAS7l+JVnbQUeRzX+GV/YuBEuM0mFfoPRxwV0EkJ6g4bvG9Q4EaNsRhdFYboFUt3Yqbh9l67F/m7juvXo0a1LaQnFgB1EyEVGV7ZDHBJEnues4aWEycJu5i6GLtVRSmLbkEOjb/0itGa70AVYs1uRXkKSO07j6BnUqHhTzyDvlvdZdiw9eNM4i9nqz7Ni9q+FXRFa//u/v/z36h9X/2y8vH5X3bp1dbvWc1+SWvI4noun4EfwFPIIWU2Oky9xBu4Afz5yCUZ9FEZ9GkajRS0CZg3CaqzTCxqVCmskHIcoLwPGrXQPvXu8lvwiSVJlYD93eqfakf/RILxgmWCdP83RZs8UnA0QQZcRfMA93SDzXXFYZUM2IxbiE8zankGzCsf1ClLA4Wm5lGl5PAW4MydzDFWGjGrgDQ6P4GsswfP2TWq34pF7nh456N2r7/+05RPyOndtJV6wf+MT/acv6dh38u7z+5eSax+Qk2q6EkMBrwkwggzULdAiAdlUKsQ70wxSy0ze5XQ5ewZdLq3Pl9wz6FNpLT2D2iYsU0WEvUWNjAkkJwxH8ChCKdVi9ngLIkwbhkvHy1/d+sS+evIV+c+04/fc9+kwPJsMfWL18++seWRY/YTqwT/P//gXYeiy/clqZ8Pqc5e9rbbl5OJMrF25YeH4h/NLJ5XddYLqINmAvXHiMVgPK+oYSLaKGo7TqXjEi7zNjkWr2CuoMamsVl4l8ZH1iRam/jAL9rK9heGLB9bMC/xWGHfhqVAxd3DfBbJYq26bRYpwFdmHq1bxXzZm4u9WNgwrCc2gHBLwlwQ0EY/aB1JMvNuutrt4ITEBAbIQkhwOWEOHJOl7BqW/oa4JbXmCw468aQxjgCqzJ02VYaOqSyHX+nusIZfIn/NLPxi17wRZct9TA4u4T0OHfFP5Od+evEJI3+2t/XVbcV5SEbdnE6lwUcxMh1HlwKo6UQvUPeCNt+g8oIRIFj7dp9cZk3oHdUajnbe7egbtbl4NGokqZmzFMQqHzK+RJ8/lgCXkeL9Ccd40JIbXVQKZ5ZyO++LyyV16j/j5ul7/4NW3rvz10RXyX/zziq2rVw1eH6xaw03GL+LnbSvd5CJ5e8/V974hN/GAd15+blVd5fzS+/ePofQIa5oN+JRAQzVjUUQcMEae6wVKE4qTNWA2LNgFGLihA+MkbmjjFf5MqF5M2lR74yzwFNBJhEw2+zTUBvUOZLpM6fZWfJJGw0sWu0nKaStZMlMzU3sGMzP1SB/fO6h3I2/vIFL9nbiLm0trWR47Fe5WkO9jWhcfVkxAPuM0ig1ZpnPl7/y0cM3+9eTLnxpx3uMP/Tzz2Q3r6ra8sW4hbj9nxYynVs5cJZ4+suuB/RUD/jn74IUzR28u63Ng0lOv3qx7aOGyh4dvKAs8yd//0Mghj3Xt+PiQUTPp2o6D2VGe4UI+1CWQlmBJ02oBabC2GcZkWFm70cjZ7XGwtCpO1TvIxZKdtTg7ZmEx0BqQHbBGm1eSZwR80mmVJwFzorNIF3yhMdO6V42+9h+dvujglDe+ufXBuksPEfvKLU+suWfToH5r+NLGOvvKeNik/v53//DBN1i9iVzEbQ/veuKZykdLx+4fjcI2jjCSaVLAw82wLRByuoy2vkGjOSJEwjxckXzRssQChk4zqTJjdrRs4f+5aBEVMRyThbXQlxp4QxpQksEgajR2m87cN6gzM8EY00tEPPKRLpoEpWMmdNK+U5O85P5NuymVZflvIMsvAL06AhoBiVgAcgXwOYqi4MMFGIhUyGws4Y83/sYbf8N1NfjSOrKcHKY4mYmPC27+CrPJsgIOFSfARY1aEKuCYDhVBU0YgyS+F7CSEy3ZqEjG8JrJ72wcyu/kBy9ZQu5fsuRvIxIxiFeVWuKbRmTDbEhjeFPjrzCkErwEl+Lp64inhgymI0q4dYUvBvpKAEumOJDkAV6mstlQolHIAFO8hblFct9gC6dZWxE0CzGkFUtX1BQpLIwVUiAMgVM4ou1VT3nN4rIFs/uuH97h1fdf+yij1yOjuuyP2K+F0zdUT53Wb/REX+6iEUefr5g44sGBU+7zkE8Vo5ZDs26VSofFBtDMS8DySPO2KTR2UNkTEMq0G9uIgS5x7doJndQWHfxlp+Tx6dlNRiUYWf5owyOij7N9kBHeDnToLt5hZ/uba+FNEzgH5YZFDsmbikC5aOHJE6wY7ttsjBUeHrR9zMAZOnXLdaPXP/fL69339HDX3jNlLfn3S5fIwb24K8758OvX/yDryMRP8DKMPsV9D93874mzVmP5gPlruAsrfpk/5q67a87se++W20mynPs/3XMAm9e8Qp67TM6SwwNrq/FKPBoLeP2lA+RlsovgYizaGwAX8J9oFo8CJRlh5ZI5o06NRUpZvKASzCYjVxHUiUYjqFtIsFL7iwnEJrKSbWYPtfuxH2NgKBI8Ouxg6GDDHq7rSq6EjKr3eJ2Ze/A5kiMevdGdG4ffGjhn2FTSAXo5BUzoGHBuI3CmVFQRSLcJSVqT223UCioj6ANqU5wprjJoMhmR0V0ZNFqRqzIIz91Zn1BI3ZMqUJbrECx2wQtGuyxzvBaL8ukU3goKo7BqKe5N/rxKuHoc17Bt3+s3cO5LLx/6p3hw79H5z7m1xeTim1/w3ScvnD0htCr05ZLVix+lu2UOcNOzTFakB+y80a4x8q44K6oIWgWdBPiyxapfQB5psObIH17+fGsLfx6Y23yHX8mP2PDXpjc3XiKvkh3P4ZLPvttTXif6yWvkR3KZnCxaV4wX47Ff4+rD1av70J0GGBMHAcZAX6f80ChokICsNtFQGRR5wVgZpKv0d50WeTwWTyri4YPX4k8FGGQmWUnG49fxAPxwA/T17Z9nMUgq7keynswTD5KF5FmcjNNuTgKlE+YM/fLXoV8d1VkkrRYLSI0FvUHSVARBfHGcWBHkeAw7HFvvqLPQQTiUF3+98RyfE5rNDQ3t5GrFgxtI5vrQd1E9aVBeIB5sBcyrMa/VNevI2uTZiunBG+4BT60PgwfgoR83KCsndmJ8CmZhV7s5zpigFhKTUEIFaF6iXm+pCOoF0VURFG131rw8FilmHdmWprsbZtUL/zrt2jbSQFYcwoO+/+ndrm8fIv8hH2EPjtu4irzCkVCxLx0vxSO/wXcfGLi+mrxBviOfkfe9+A157mIKw7I/4NYAF1cLgohEg17NVwTValEr8aCjMjcI7MOoXRiZPCilfvjXL6bUN5L6ep6r5/aFqsDWXs09SDFQDUQ0kPVQFEgUsZYHjZ7HPBhkgiRUBiVeC98q5UUEhS6u5G9qHVgSRZYMkCMqi4sbGCJ79nDcnj01XqHeV1PjuznQi/CtYWQuHsNs76SAQadWG00aHsHCgXLFsBo2+nhZjygsANB2ST+0sKz3iPvrXydzE1bZH5oMhtygY+cU6hN+ZKMGeUdxInI6LcULpxY10TgpjqEFrGLoAMYk/PhO6OdN9fXchlOhBu6txaETgJJs7uNQbRTFiaA/GkEQC6DLg0UAw7U2DZeNl6LA4zhVTwn2xjeb5WclGCiyAbXGqUVRAi3ZIBnsDqy18BJYFkYk0cHR0bmKmy8Y9jod1IyAEfot1PTzYr6KmNXGrRjhs2p8ntQb1UQvmjd/1DhUPHizUgDblt+789iN3xRqoZ4WM2oTcGhAUGKTCRi5xarjVSY1j1XRWIklFKVj6DadKwJqKXmj8bLavPV1PknNGbmnhNOfNTQehw7bJ2BfCV9GOQ/dO8duZ01XBDUCb6oI8rbbcx55d6SiaGtaPEa2kjcp08PDcHewlmtuTg39+vv1P3/7PQRW9Q7yIOyRMXgUXkImke3kU3IG5+EssKxzyRmZCwpjGI+wovxAvBbwDnhGNrsWaFirlVQqa2VQxUvNuERxk6oB1iYz4FKxYmd6hTHkHPluTz2ezbUI6TZ9/e6xU8cE/Vf/DgEeQgmrd6xawbBAdjEsmICH5AfA6HbCUHgt8BDeoDK4K4IGQWUD9gFmecnbEQYSzT3M1CWHVbfHytfv4yF/kitFd8TM92RZV7IeV3L/J37OAn70oDWXB3wGjDm9xqLSabUqDSc4XRoDbM3KoMHA8TysH8/rOEYrdxSpbOQMX4LDLML4fWG0AZ/FqzD0QD7Hy0+RbeTsjw27n3/1S25YaLt48P2z5KvRoYncsNUrV656FGiWWiIccOAWFHvJNlDm42yi4EvXJ/NOJ3Bhp8BrYkgJFcc6CwRvatjkBSMjI13WtigXZtaH0+V0Chz54RppXDb4ozH1ezqtXP3ei+Tc54cKDjy/aGO72iXfvYBrj3/WbVd6q3lTew3vl1/xzo7n3qla22va/b2G35Xb7yjlN1bA32DAnwq1CFgRFjHPqzWg1wBPEMKyJ8IWZcYLDNHDLa0nuUI5yRVTNjB5cwT2jBvgWJAvYDFjrQqrbFaLmhcMAEiZoz96u1jslCYYO5T5ougmH5Lr8Hep/p03XnlHPNjY5wb5Gqc28nsbSw+9+dZh/jCiZ2xIOMG8TPEBHYhlHdgTAs/TgfoV9xIA92Oqo4GVWSToD4V+3R36owFXtU9r0V72mDX2eXrzzh0ADTRhVS+A5qY+K7c2Id5hNIqaOAuIBo3MUvzKCQqDC9wknap9FLqNQlc6sfnFt3aTC4587GxLvt5NZjd839aZUIClBmxr67EVfNfAn+/ytv2xrY1+6HzWsc2HXuRnNc598sSK9/haij0QTcJ6xvW9AasgicDyJcryBcryhViWz4YBI6BKKLD79cdBv5nUgL8jHY7i8fjBA6QDtyA0h2vkjoRe5bqFeiEFa7OZLpUaMAkiJ6p4DUgVrOLFJswpGhwABfLHfq4TXniYxO0h7iPcRe5i47zQKS6HX0S92ACthPGk7IALVBYVUglY0OpEAdgBj9UxmlGMX475JDxCSaONV4fM/GuN1/nkWmHjptqbYwFuHTnKTWS0mBowSph6WdUakeMlnMEwEE0+im7FTSRF+BS5hD3kqHRj+Q0PnS118nyp+E8Bfc38pzbA3RHOWxf6l+I+hSf85ChuDPfMIwz8Xq1BqgzYB817dnmYn9njhy4vQddFB8VLy/+i2hvK5TIEr3gc2HNcQIN4lVoE85ID27KJMm2MNHEu3j8Cf7mRLCP7uQx+c+No7seQC7SJRvIWv/lWOfMzU2sZ7NMcKtXlKRd4oGkNv428tQBhvES4wFslD6yC7SASdFrpUTCF48MebewFSi3wA5fgrQ922d/ydOqUVsKF/NdLHD0OFADfHHrrF/6MMJhSPioL+NLUuuRkt9uq5kFn43TJZUFOp0MOh6k0CFwwvjQoOoHBlvzvbjBP2Co0+4qYaav4Mx0WH2zwAhXzg/H9POp+Tz/89Cuc7cjEh5e84L/r+PA3XyXGJ1+qe/vFCVvur9j9JO5llrrPm109t1Xe3tdD9un1m0aoVBOmDh4K494HXHW6ZActJAV1D6RpXS6TSZ/E6/lUjwHpHVaL1gKLBwOWnMheGgSNIZq9gpZX0kxDwIpIApNWRVUou8pf6Aq7d6iPau9n//71k/EvddR7Z9ap1dPeq1+/uX7T+vXCYHKB/AZ/H/ftt1yyk4VzR+1aeuL7709eOvfJh5QapgKGlwpDZNvJBNJHw8e5JAOMzIysMDJnc9tJVGwnej4Jmjbji2BHcaOvkptY85++21r7i+bnkf1Pb1/yxIN27MN6bMOt0lzLnUlk4LufdVhTDPiBXoUiwI8V8NM14EmSXMhotEiWVI/VYYJhYT2v0QB6NGbeVhrknXdGj+z58UrhMwKXPyMDrnvZWuYrQojfrJn67af/vvbJpZkGlVC3iGyr3/Rk/eonN695FqdjE/y12tm3Nz721y8zX3nf++PJK2c//EQZpRVwY0PxqEMgJU7r0vE8cN3EBJeuNOhyIUmyM0QZYxAVbZ/4o1FmdTg8ThldEp8mqTywbnN/J99j8Yv3r4UM4iu7X3ph0NYtj201cp2W2XFLrMIa3I78+tXY4+9Urk338N/u2bj1WbpqSaAhG6UUZKfeHbteb+M4YJK8w6mFraAFAakSS4M2lYmnu8EfNhyUUw0LFReMOYHGVVBUYPaExTVIzG3kx7o338TD756ePaz70MHYxZ9sLOZP9uzYCa/11qbMebyMnqdmELuQC5jJQkWoC3ow0Dnb3c6nT+kk5tqwTeSy0hJTfG5t126JpgJTQWlQ3aEsqE1TZ5nUJrUzK4srC2aZWpaUBVuanW3Kgs4EBXWRJY6jjq7i4uw76D2OsOs2g20A5oiihxXUXZXe9C8QQ9NZMz2YpV8K8uGTkHs6sdUXr+dmj6sc/Mb+18gX5IdPf3x0WlZxoMeA8Z+9PbAHsaxfdu7UgxtPTn5k8Pxpv/93+iNC+dg47+SyHa+r2w1onb1+5cHXtq8euTreVlXQcXCWd/cDDSfsN1FwyJzxwR4P8B2nzvjlz0dgnfaBDOoOdO6k9qNRrwZ27dQ4XXFGjdUqlAatZi1CGgdl3hG9NLzrKaeyyARsCe94mU3x2x6e/8JTdXVqbe6BaadOcW8tfOzoJ6ETsLszB7Tre89rH4QKqGa8EwhltHgJVssE8tpC/csYmy0GVXnQwJkwpYz3o92n1BHBRzlq8cC6uoZ2WS3bt2+Z1U4ox5nFBYXt2hUVAexbq4idwdajONQq4LTpdAa12h3vNJcHnQGNCQH1IWVV42M6sUUZmObo3rLv69CjW5+Kph6J3b3IPuBuofGmmRxV3RvuXMZpEuDUjApB3zdqtTqdWhJEtWCxgvZqNqvVvErn4E2yxpADPZfIJFSsoBUzjQiHrVG7CldnD8I1b5Hu+OIpMmfOrl1qLrfTCDyTtA4t5aQHyGjJ3niyaKrcNx4IffMIpglsCpQUxDAZNqApEinioA1rLZ2GXeJFpQGvw2KxwvJ7NVYv38KX6HQ4rG7B4AZOm+I220xgOTmYWVqiHG7BJvA3D6iIIogmynBRukh1halD8k2a8eTaukkzt6yqW5Sgznl+HMZ91blHZh55hTu1YMH+V0Jb6Ps/Pw4dF8rXVw0+MnDkax9SilGoFcZrR7mBOGSn5GrXOB16jdkMxGo2a013ItZYWnVFU+qe7XQY/sOT336HUuqRT1i/dwVZpzKPrYE+KSWBFWtDBtDT4t1aJ/AxM2+OYf7RViyQDFdAmSqKjicRashvV9d88w+sv/odNjW+uvvpp5977pmn6zgf+YOcfxxzL4A4yibvk5sffnHx/LkLlMvvA142nc3ag0oCqW6doFKpU6xqa5pX0CGTyVEaNJk1JnUCSmxi8yVNplBkwzJODwLaGYUEKrUpq48S1FRI6+sWudSB+gc//59fr+5ez22uX7Fjh71vv2EDSScpf/3gKvIJ+Z0Kbf7KkdO+709+9+6ZixRXMNIihitZqzAnOfn4eLfZneqJT3CZkpKTnQabTQWc32xApUHD/yY2ZbmZV1gUEZKuiAQtUnwfEtenbqO45bnVT26a89HVa59cnqWJW1CnM0yduf+877t3r5w9e2EpGLk6sDbb1K//6z388cjSZ2U64jNgnGaUFbAbNBqtlrNYdSYD0joYZzCHo2li4j7CQiiMPG5XRXtX9/zaV+uW2NSd9ghD9JtNn+0INQjlp8dPky1Xfir00gL4QKJsuYq2iOVaGnSaJV4ToZ+c29muUtNxbX56hhKrE2O68lO/ef/zh/seqJ6/bOKOTfNKPj/20nMdnlk446HWI1ecWIKzN9X12NyyTf8BgXs6Fw94oOfCJ8sXda/s0qpzu4KyJ2CMKbd+4XaLpUA11Ddht2t0GisvxLm0NrOtLGgMmE0qWCiVslDxZ2KUVXl9HNS3aaGCusjvoNaE3clZW/WLSxibRd7YurV0GO5M3hg63aCaa7Dgvtyyqh4/kXmh2SPG0R29C3ZXsVAOtJ0fcGObSq/X2rQOp95gMKvtJrarnbqwxkz1f3/MpsZhqxcIIqw1W3Av2NRP1S2J0/gPTHvnpFAeKgYB9DEXuHl4zV0Dj53jzoRtOQ56phEmWqzVG0QNNjHl3B+2MjzMPPUXWm3AjbeTYft/qTKqddPf20+GAdAZ33QvwL25tjcPy7aS5AVoiSBt7JqEpGSX02gAq11Qx1tB3Kgd0Vaw3x+xhHl6BKSiRyFWK6UpsIFZl4XQpZBzypavbpl89nXy8d5xE9VqXa71VMOb7exqwfvaHnKOW9Dh3Iv3heYK5WQEqepZfKCAmx5aumd6i/XcFzAoGFUGzFHN5pgWsKhECfQuGkiD1TqHAJONNYcVSxgzq/gwWfAq9uDUf5IFeNVRcoa8e5TL5VxkCN4V+jF0Fh8l3QE+B/tdAvgO6vWAafJOl2BABlDLDQlqkOoleVHeLJCtTXOTZMoGVbOI23+tr01t6P/dfpJZ+s/FvSqLuj9f0QkQvPKT+/x/cv+4mfrKk5Za/etbkGwz8xOgx7/bzGVB0YTVZUEq7e5sM/MTGr/lBobOct+H9nP3TuYHzpvXeESJ/ToOlitdP4c7Pl4HC2pS2XmQ1HxSsi0SBBZ1iMfoH7iSA3Q5KlsKCoEOYX86gEPYVQ6nA1+cPvnYB488NH/aZwe/u3RJP3oIt4yr34xzxgSXc0OG4bwn9yyVjpMLn2boMz4FWX2VmLnZYdnN0UA4PgGotEl2U5cvvroczKQ/G2HM5TBmGimURMfsELVWK+dGSUlGo1vkk1McGRIfPeamo0c63IwCKv7o6ClDs0sqZfRFnPbSlcPnZ8x/+JEzxyZOe3AyV5LxKc44Li3du4m8P/webnlwDDm36XmYyZDROHPKQ3Z2GsxVCZl8NeyCFgELb4pzW/VVQd5qkiRUFZRQTjYyx72Z82Z22KhOo/1RC42xNnrIlUYdc7hfwdzCkU90nxzsWpNT+Gjh6BXlj5T3HMyd6la4YUJiemJCoHj9g57U1Dh2zk+Wgu0+NBzLzAkCWGQmM9CHnsUym/m028YyAxF6bfbIoetFGso8q2PHkmJ/Kbfn5iWx16KyQEl5AGZ1DPj3RXb20zmQ7FQbeF4rgPomqPnEJLfI2bQGDv4EnUpwo0iMKcNzs8NeHwugpeqAj8bNOiMMHMifPz12Te3p43jZop4rcnNrJ+zZ+dSzy1dcK5TeeT8VW27gxs57dvEe1zL/+Qsfne5A4++oDiSeglUvD6TH2TQGQ7zTwumcNjE5RR/njusT1LjdNmQz9wra4pDYs3nASUxoUnPJBuybnoLCWwFYp94CP5AEV3+svtPmlbPn4DoyuEsln3Dz5pm33/6XeKq6tvc/lpELc78cubjV5uU5f1yaizvuP0PXZjaWBJewHTCXgVIDhkSUZkTGlpkpDodPhUBrzIn2BNnyizKKXFSuFblUoGyrXKoMyh1UGUXpRVGq0+HKRUMXjZk/d/SS+xZWVi4YsmT03IUjFw2prZy3ddq0bdunTNvKnX94/LIhtRUVtUMenzxjwf1wsww+Lx7/j0nbn5o8ZecuSqspgD96/uJEAwNtrEaVTmfWixpOw+vtRofKFac1GlUmyWrlkc1hsqXYOJPNZNNjh6SPkxV2GikO2i+L/77t0T4wUYrHSDAl/cN+rhzP3kuGYYmcwJ3IiV3kTdwRXoiM2s9d4j5tJHPr55I/sB7eeA5xUdyA+pTNHFjkoPZpBF7gVTJbYAQXFb3pZ9YC4xCURzReJ4bGP+lq3LrOr5eSUC7qhLoF0vOQTcpKbud2J+sl5JU6lzj4DoWJrSWPxpNRFvQ4NOayoAY10Yzrb2flTfar7GORtTOnHLPkiFVaqNYCq+hyKXFLSwf2qaw++9bQp4YX1CzqNW3a7M1H1ver3PTTR58/2ueNfguXt31g6oqFXVc/9mzukjWvdB/AZw5c4ms5ccCspUkZCzISOgQ6Vhd13zB+8PLMu1Yv29Rlra91r7I27dtn5w+eUtN7bCdb1cT+k4ptIwF/BaKRHyOeZ6dPyQGYqt6KrHFuE+/QAAnGpiFEmXm+aAOzoMCXXlSU7ivAcwp8vqIin69AnJzfpk1+Xm5unvJOvQ0jb/0idZfP91Eh2FIZGS7elJOSYteYXGJRO0mPrCZ9ip5DVrOV01v11oRsr82fIIQlsJx7YIl1A4aDo5iClRY5tcDsEFmwOoDledNaAHKtgj+vhTUcNsLPmbNo7oKBc9qOKn3jgy9fe3RG+wcaN7yDh75LX2+QnR+cJTtPjN2LW+/Zi7Ne3Es+3beXfPKi4N27effO1v+wJ/762bn/dpzhJ4fZM2Tnu2+Rug/O4sFvv0DOv7AXZ+5THqP7vJS3c5PFkzDvFHo67JIStBg7qV6b6knUaq1uJ+LNPMcbrCkw1Y/ufZPauzEbBqaZocyuqIi6PpQoHovP7s9jvk7OHjdk2X0TNz8zfe3IMdlTaucvIVWTTtVMup/3VA8fMXr0WEnIqPUPazd2Ful4dGRDriBQ/10/NJ2v548hCRlQZsDGS8CsBZ1GIxgkwWjSi2oaeZzzt3wA7MJYhX3yWz88cCZ1ic8k9XgpHjSdXMYp08luLgNv6EF2kh1leENC00cmB7Ea5KALiSghoOewgCWVKFQFReq8DpuHyrFYgZC5pPEd3hX6Fdc+jm7dCsdGcxJYHzT7KUxTaoWq7g34M1wIbHRBA5Rl0OitLqmond4MrY02o1VtM1lTrBzwfRtntVltd6QwugCx85aZh+KFEmEP27w8O+rMc/5fxMadf6Bq+MB7x+H9A3pf3X120Vos1j1589L/SW7cjf6Bnl0WV81OJtNxDdnGz55P3vq/CA7fuiGBvimtuaNfX5Bu/iHoJftcoIC5/BDuKuCP2u2egNEAXA9J7niz9nUnT9ES/15ejBAKB48BMUZLnbkH5s8/2G919fwDU6ffVTV1cr/+U4U18w8eWFC9srphQb8pk/tXT55C128MwjTenksHfQQhFZeNUGgaO82o5JaDvI6jPi6VDSjLaENCvFtrTnHh1PCxSFOoSEFR86hrSaXycMtDu34J9vNUleTX9Oi6YONja4Jr9uLDXOWY7wYO71XYvUd63qCJ8ydVbXj8GejTwxWzPr2oOJBmEkXEW5O1Uguf9V7bBBvXy4Z1tgQbZ7Op4z0pBrUnHB5y7733Nh1R4mhmztySf4+w5qb2677v8O6N09bMPv/99Jn3j6jq1nVSux4ly4Yu3ip8X3W/K2fnoyvbze6+c9X4vt07dc/2Dm5dOLPZmYsGtHfhUU30mQtNQwGmp/LiJW0npr7bam9govBb531dHF2O5VPrHayO7mADOFFBIF7UatVmZLPZ1fY4l+Qw2RMMHPUsaMJGY9SpUyQaNcpkhOmFIxsGkuefqlscr5347fpBWm1dHZ5Dbrz6ETMbnx1SvZ28LtHeJ5NGYRjIZRPKCFgFEQxJUcebLWq1yJtEHE4ny4leU7qZvBb5SKfQ7xGGTfl4aufqz05//gPXmjRK+/6q4v3WGzexQGjmA9cLL+EHU7kV0EoIGfS8pm+QRzmRxAdbdOJD53ZyiCp3vmuPHl2VzAcfseMGoEIr1RwsRh2wCBqtbrRY1LwZ5byXF82Fm/wMipuhyK9y4oZN6/2tW3foe1ffCYMeLGogl4aP04xTtyzMLrDsn5oBffTiKvFldoKcGjByCJRjPfBXPlVCydTuYxhQchSUUBWWpdBvZa3kLFzUTTzYuJbrds9gZ+v7hsFu7QqS5BhIEiqtfQErTXKTEEhrWX44NFR+vHlvjNCOyswSozYsd+z59auf271h4zMhcs+YsUOGjL1/iDBx16EjO54+cHDnw/Df7BkzoMcqkBC7FQkBO1OnEWASKk6rkW4nH6hal4FxERblN3432T0dp5DL0/EgMr3pM6cnY8vwfXhoDzI2oekj8IeeQCjnAF8ZnItbAGuTDnoZwiquI58UOoQ0L/OcFRTMggLKS9pA24tK2xK57dfQFk3j+0Bb1X7OTJvSlvEISR7Y6elo+l+Itb+EZKgTFKjZMlSaTUlm8tVgMSWhljSmW21P9uJEU6LXLmRmoSSs55OSLOnpqX2D6WaLriJo8cbYDfcNvTdWPaGMiaWRURPOJS9GZ46G4xZE2Vdcn57DZ3VyP/Zw1fqaDm+9fewzbyBYOKpLw6wOnbsWUZPL//Da6nE9e7cbOTm97aJhR+pLxwb75Qyadm8Kzl7Uo2ugLACzZDGnqqnAW1vQ2aFut97A/ZD6Zc4spqPsgtu0mIFq5RZxd2iBeyKv3CIptkVOpEU/JNwWhi/SyzhUBC20DTzXgjVpaiPJbZAKV6FiNA9xtwis1DxYUxOyoT6BVmBmWDBS67FZFB2iXWOTbBVBM18R1BrgXWvWYokXrdjYFAQgW5jZ2cx+w3I2qj8SJcl7cSRSkh6cSfNC1hC5so87wp8KHeWSQle47jer8bEZLKxWCZ7EH3IHKBXNAY56VjwNY06nY0YPcxyi11mcG8NZSwXvhxhGsCGMkeYtZqASuYXjDi1wz1s35RbxsS18ERjjkJVhlcOeCFY70ihEBqWVPBLycrOR0DjFqVEtZtwaHjsSujbcjqYWuCf5vdlIaC8T2UhkGONuqWJHArsoB9a3nEVKudDdgTyLw6EWDAjZBEmIc8OWkGDnmFQlqhdVZ1WXVCAbVCpBozFXBjW84IiJtlV2VbPE26bwW2p+sxBcJUbVnyqWsyDc2Zs34/34LvyPA6GT3+PZpPYYVyOH4XJL1nMbyBKyiwuEGtcTrYxdsYRRda5C1WuazZrF6TG85Mm4RbNkunffqcUMrJVbeO7QAnZXg9wiPbZFTqRFP7T5tjB8kV7GoY+V3dVawT6GXW0W9MJGkBPugEal4bEG6w1q5M6JlgxMe1f49J5hZDseOoxsI7tG4aFk+0g8XNg4Ar5uH4HvIztG4GF42CiylVpxc26dEI+J/4X9mQCaE+xRZ4orWUi0J2rtIEpNopqqUM5kj9vgqQiarGpR4M0GgXdXBCU+HLz3NuzK5ik9cpI4LKns02CRv8zXwZszsJNlDbpweiTG8ee1ox56YD+N5Tu3dfhD415veDA0bOq+3683Zozito7bHQ53HLm87+a38QQa0Td0UdXad8k6bN7c2KsfDesjNzbzL1aQM3QPs4gytjLtlNUtldffFsZ78xYz0Fm5RcIdWsDqzpFbpMa28EVgjEMblZ2TEc0ZhdkMSkeln1wZipU1UaIQZylRiIWBRINGFCULSGinyyDABoLtY9AYNJISp6nww9gj6XCcJnxLFZpCNWeRc+Ty4fr6T7EbOxtv7P763WPvvsebr1wlx8WDt9DZ0E8rd617nJ0x3vpF2CKloLZURmZmiUle5NLrUZIlS8zNs1qz2rRJLw22QVkOdu4WPm37e3aKrKPIwTqKcyTsF6FKlhwGIOeupLLzUiotBffKQe0SyvosG/3Gy0fHl2wp/7T/g3OH9SjrGVg8l/xS98VX718Wfls0tbSbJzWr2H/f1lHbn++xOSPnYM/xpf1mV5eMKygeXFA14NLNXsL+/f/cCjhn8UmSB3DeHdF4rG4rENuVFmXdmt+fkc3ut7nDfdzzM3bfH3Nf/DJyv9+rt3lePB+BP+5rxHZ0e0uYKuQeaiMQqv6UW7SUW8C+hBYSPUfLRPmoAxoe8GehFpa27dxuoW1xXFxKWx0SO3ZKaJ3RujTozhVyy4LtWmRYBMGYluEsUBvVpUG70YzCQSbsxF1J0GzOeK3FYf26KCbwysUOSh0Oj9UTPu/KiDYUqZsCy+EyQ6+Tw9NmPvF0fuXJmvnPZBY8/+DrP4S6qXH7e7ZWD1w3ilyac9dbC59+Ze/4Qat2bzuyi3911lIdp3oU5+x4WS0Ha2UW3H3foGHkP1+NJ9O9GWvTPT/OHVe/4b7gc5tHqNQTuLy6bVt20309ldhpXBTgrVSW2biUyWwWE8RWrFxe8QNsRRLDK9L8/ow+7H6LO9zHPf9k9zNj7rMVlZ8fp5XXq214RW99DtdHMAi/K7L6LkRhYDEM4zmQs3lRLWbcSpVbGJQWt16GFgOaWoCsflZuYQ23+AVapLBxyDDGkUMIMY7jtoRl9WR6dg92Js0yqwxkeyR9YiICs8JptNjMkiajpSalNKjjnRa30QQ6mIZ32THfoulInxILcO+/pzMibxoqyEfUZ52eIQJTzyhyMpZu5fPDMWeFQg25+edX5Ees+uLrkeriM+TXih+HDO2xZfTVXmc3PrO7YQt58cWdL+7g/OR78iE2fP0dlmYJn7z25AO1nXOnV/R8fPzMlWQK+WFNPdn47KFTdG1Z/APbbXfJu83IcJIcvTY1DGv95LX/iK1d/J3uzxjI7qfd4T7uyeBzGTH3Wf/y/X6/3uZ5tiYy/HEJMm20iezlMahRyBR2s5ifBOQPxDm1WrPACZieqdhsWGUwYJqRiKm9C/uVOqtyoo4rlKTEmHTNps9j+OOhvR0K89t38Od3Cr9zyx9/nPzQsVNJ++JACfeH8gFGMPXWUaFGWKTI+56BTFdKot2uTWwm7N2lEWFv5j2lIOyd/5uwLy5WCCQs7JWDDdHOhLwk5jOhX8giQTZPqp4ydB7WX908rXr68LmNrxbgcz2nPF3HbfCTnLLJTz8nx4X0mN5nwUaMaGhIr5m9ajf+9fxobmHBJx/tGhma479IKYOdo7OVGySv/JuMMpzhlWl+f8bkWMppdh/3tLL73pj7bGXl58d55L2WFeHj7CyYQRgi9yBDSLEosp1FsbBIszTUOZCSKiXYzWaLZGnhtSJLgt6p4TXJpUGNk3fB/osJN/1bNCUOB65Q543LGQ4/8ylhLKmW/HQ8qGv9uE+v/frJVw/rBXVdnYTLd2/kNtfjnLV8Q7Av+Yj8hyJ2R1rvElKgRqRN/uCEw6cyvz+J9316LjwbNl95NuMOyPPNtkRpM6JZaohYi70Vy4ezijGyryYi2Xr/h+2WlmGcypZiUQRCH5QtQzDFQvg2AqHPNwyCLwKBdBUt4tGIrXkXv53JgKGkq4CEwfJz9Dq3kl1neYhMA0tX9Hh1Mz2ORduyVSyVV7E7W8XUcI/NIIAmuEeG4LsDBNyT8SDsi4WQG4HQl0u43RjESwoEaAE29P8+il7ouAwjLRrGjqhR9PqdQYhwKpYVySBkKBAGNfMGAARxnmSHFmXMo9KO11Nc7ue8SPG9KJYgJ6HVCCmRYueBwuMB5shAkU+yGlFyssPtdJsloWVmvCPObDGXBR0mS4olx8JreItFY0MskjiNRRInNI8kZgV9wiZiXEkTG7xNVHF6ATtp9Dv8Du//El2sIwH8et2WLbcNMe5fUTvv8OF5N31NUcZh+5xRekuF0q0ytk1ijE5QE9EZemsZnSZHYRss/KIIhD7ILEPQx0L4NgKhz00GISEWgj0CoS+eiAti/AwyhEsKBNqiGsVqNqdIV5q1J8Ngu2KqrDGR4TSKWn6SXR+NUm7juegVHrUrps8oXanXzVjtmmWPMghZym75UYaQFYbA4pQZhEp5t7xLIfBVsRCKIhD6gBRgEBJjIDDMyRD6vM8gdI2FkBuB0Jcb2WzHyhAuKRAo5lIY5vieERikA82ClWEgFVeMMxjmmo2ea8+4BV8avctS2Pyzlfmvb+Z7YBqNqh5a9FRapOAlbHScIjcol/ye5Uu6UHWglYs3mZDZLuj1Kp1aZ7YKcW6XsSLo1FptLIPP5oI/rDdbRcw3HX6FC7pFRb0r/1OFwu+I7BuLHHNOqx6cormw77xDs2JPCdMbiXDsOvy3/OZZmh8r5C4nj//4o1xliEVQJ6NgIM9pN6vVBg1se6tGJ6SkupzxQmnQptPryoJmPT0DztHzzni9Pt7Ja4zh0GpQI3JoUMFtjZJmcdbeAn8BC7NzxMZb790Gm7oOHycl4ajrbQsfO/Jx6Hvh4twjR+a+WU0Drz8MDQHNGUww7l3miZH9YP3I5808ZU9Ai4eYnve70mJxrH5O6YCbz3iCDKM3CcXyBGoFcHmMJ/yutHhBhqEKw0iGFo8z2pZh9CF/xXKFW+9Bi3aMtn9XWihWgBCGUU260uxkGQaS8F1jKF0WkK54HqPoXvJVM1I8hAPZTpD764uqoucdjp4WyiPR06KgxKE3j54WylnfcwUzUKbctwq15nazXbETLLMHxEtI7l2FeoOEgOs0x5kbxXZDa8WmORS7l2nUOteX7ieuSGmxQ55vEx8kHWheM8BoI+9EtIT1uQ+uJzFp1YfOmCvODsdLJwilyIByAm6DTqVS85gT1aLRxBlESVAjjUlQQhOtUWEe7MgmOv6czzlLavA/T5ENi/bv1wulJDm0BV+fTGbBx2/rJsfwyhzFohvRTEY8DdhvpdoDLfoqLeLxUkZTXNPcFP6cI88hl84sSDrgu9nM+sozJj80+abFQ+EeuXY46dbTIJ+xhlfkM8ye2x5+ErhTu2wqv6Fr5b6Sxa30RzmjELpfeW437GilR+CHXUPtlJyW6yxHvVPAo9LpBKTXIF4wGTUqQctjSc3BHxJ5pGtKBJdrgERw65dzwsEyALz6XCLNDR/bbU89zuxG1nK1uLHDlFF8cqh076zzpONmmskGY8mAsbDse6WKG2dQ6xGn4dWSBDSiwqIWiY6wP4z2hZkvI6wsg/EBy8i6xAOXlJ+re7C0oY4fNnTS8lAJV10+eSafiiJ+4qlhPzHohtzteDXj9nfJuuGsWBsC1o9ml8sQGG2eidBmdyZf75LXdeBt+gPpWtdMsjXrD/dKidUCaZFYGv+YAfcXsRVW/cBWOJFRAM13J3NZvrsbtQskmg0Go9pud7k4Ix+fYNUaHSbeaI0uMhBbDyAchaMcjGKvNZwALxcf4E/VFJb1Hzuu8XUlD9741HFWh+DhicJpcn1b40Xx4M0+6/7Fd7ox6MR7kXPrIUBH7QLJNpPJrEpI4Mx8cgpL1jLbzBaT2RGdmUID0Jt5HpTD+NvlMsCIfAOLOnZepqQ0aPMbHjx9chlLU9nDz4pkNvQb9NqHjcs314c97oyL5yma3UlZC06I1nBqGBeXLf7e1lhvkuyzL4pA6KNo4k1nZjKEbyMQ+sg+BXcshKaTg/7Yrnj9syOe4+Z+if6DZb9Cq4inB6BI34tnQBqnoP6BNnGSyWZLQgZLkiSmekzWiiBoDTodKAo6Xqk/kFAR5K3I1rRVYzK8ogt4yEdsUrhoh6vpnC1cpcWSLn0fQqOHkFfIU3gIDowezBtDL3MZoQtc78buf5FbGP856e677XgJHofH4IUu+fhN2ErOkgs0F98jTE+Wq4CqUoTBIBmzYFcMCLROURs5p9OX43Ak+tRCnj+7pa5labCNszTIiUZdmzaiRWeOSxPBfECiM8bHaaVeqxhdQvFuirLvMpwj2dy1GZ2s4pN9muJ6cnFO53NLLpE/sPTzgjOd27/+yKmroQw1rhy64+5BG296Nj779KYnn6lbJ5TNXaXnUh+z/zxtJs4F41KDW82cMmkW+fPrsWSmN2NtRipXfP7ix+e+/Pizz3Zt27ZL5gOKLVegnAoop08pUXyAz2CrP0DmO18wPhAfLRPPMr5TIPOXOZS/DANpksG4zgBFbvw7LDei+wO+k9DsHGp+TH/Q4tYMWQpHqJ7VJGAwCpUxK5pEG7GpBT+VwRgoj/knBqHNHSDgnrd+kiH47wAB99zAIEQ87+OAm3BMJy9U/L+yTg7XadaJ/By9jj6Wrzfrsdetb+Qe296px16bWI9twz1aAc+DGZ6LZDy7KdwU0oFmkMDVu+Wr8+lVVg+B9Vak9PZFs1MnmnnyKZPOd8vSuYbx7nik8O5wRQUTKmHSFukFjd7IiWaL1mgygqpvMvF6q8TxHC1HES4+Q8PbYgOcabwdq7VgCQfk4styzQVcSN6tJyf5HHxtwwZiXh4ayu1cHpUTk4h6BTJdtoQEvZpzuNVao5SUbHTanGXBeK1OWxZ06XSSJWAzSYilSSsZMuERxI6C5cooqTIsrropXYbp8pxrQMfUcVnkneXLwykzuDU5P90+12jGQ8g8jFjazC00748vm071ciIncv3QxGZndrLH7suIR6/fn7HSmkFgvL+dwvtXNTu5lyHURCD0djMIcTEQGO9vp/D+pdHneREI30Yg9JF9go5YCJ9GIFShLcqpoSPC+2XPZG0ERpVX9sTFRTxxzU8n+6PLChRfMyhN/s3+Y2QoLZugkK608oYMhXkBPGzf7CJdhVFCufwku/4Hu85qXrBe28s7mJZ6prNrGZ49ywlifQ5WbNvjMh/JjvIARp+IAhf4VzMPQzOvKu75AoPQIhaCLwwB9vCvytyTI3NndSZYL50UbtW12V5kWTWsl3tlbvUD68VmaboPEHLDEEAj7n47CMziulexrS6h28GIjAI4wv80o7dmo8C9FkZRi2zNcBelFKRHSQEjWGcGyShoSoOCnOCaDWqvNWykNWWMg7WG7x82MvvuiuFBYQte4KtNnv0w5S8nALMu8TRKRd0DLeKTk/Vqa6Jay4metCStXlsaBCPdjMwgac1CtE52+yyKKD2RZazLsdrhnHWaEyfh8h6B0k739f7qq50HBzzkdIzuWFOF48lP24jdttQ6ejJ/pmfHpSf2Zwe6pI79x7p5jUWHX0QC837YVfXIjOJYndHhgXyLKMRJdpXAq9RelKlT6zRpdg4l8Zq2OenGVpwmTgL9xm6MSzPGxRnTeHWioKaVT3JkLwNLW5dr3McGAVP1gJ1d2FWYVeXHSgSXjZbok1S8LRKWaQs7FmG+/JgxnH3vvtC2ur2cfdzDI0bOSdjvzjG21by0MXe063Tq7q4Tz3f1+7t2LC4IiOtr293MwKvIROHT/MWjpkxvLMT72q9quyiOVAu1HdZlmdvsaE3+U9yxY3FRp05IlP3fMHsNagE2WgdUhu4PFLW2di3s2DbT4dC1aCG1RYVJVlV5hUYjlBT38Ft0ekdmWVDvcJhSU92lwVSzyUdP6bUOAeX8vewGqEpv5lkiwZrR8dAFMFsPnS1uXo1Dul05juhoRFUUevCnbcY5seDY9SjmwgU73A0za4c9W1ZU/cGoc6fCFTtw/ezRo2bNGjXqHxO6MHTlB6QL5qyHUvRxY7v/ldFU0GP+w/mpc7P9sRU9xE41M6eNqJk6c2i7Tp3awYv6G1jEGdtxi+V9z/Vq5uNmJ+tsx+1WbL5a2fPXo0k7P6Fo55uVFm4Gg2/dpN8fVXTzI1FninzrJj2lQ5SeAvqBJGspHaK0FLj6CqJFNYQL/F7Jg4y08osKmU064VEjTdeIV/I1lOBgp9Pl8NIKQl6snzvaVeecMGfmoNrF/acK1xY/mplVO99VtGBh/t8gSgBRxI/yuliIflp7yO9QAbj09Aysnzpl7Px7ZnZ9ok2da/RcyVM5dX4Hd/e1d2c+QtGIWwPEWrFGjkHHAi19GalhTkPwcGtuUGi3WLME2vqg7SrxW7l3Pc/mo6a/1hEOPqd8Iz3Dn8zRGEq/JHEDl23RjJEyKwaMGqYdo31WuLBkQW67+EC3R1ZQnrVT5FhVAx2yHWRF0XmNUny9eVzwznCVAnFyU1UEPBHG01qqDefEWCW9HGWrcdBJvHen8NpoguZbA50+NGvkqH88IG/r/C7i/pqZ02uiiY9GIHfFk2FlTTS7FmkEg4Y3WzgVymkKMHfZ5fqWaTQQBua+f2hWUZeOZweWP9S9x9WhhgnWo6WLCrpOoDhXw7iviV+y/EIYbJzbgkQ9yAG9gwWOvxf18w4yTttwdJ966T6N4Ja3Z/frW5GW5zfWGKbe02Zgn/LU3LamEYbJwkVfa1/HTrOWwFuHzrOW0LOc76HHg3KEOs3lMIo2jWB3GIRHbU3kyFJaI0zRF/n0fYsHU474nm8/HI/KHRVPPw0Tfmt7pIej5M227u4vtafvdFaN0IdZmsqi4AWk094xCr4xoUB/2Dohd6Tw3/Ir/VyV/9ML8aDoGfkFUgNSg82bSjMgATEJCbxaI3jS4vRq+OOTrXRVFeRERTKHAWfcIe+JQ11SXvXM8ld9m5+emVvkTc/HDeFPYu1zC+3dXu4k5LfJbyOnQbH3yImoNnKeWU1Sb22gJ2bWsMdNiW3nJDSLeUQ/Ap1guqRF6ajlHPZE6DP2BDYrT9AW4gFokYGy0Xp0+zbfgN7QnbVpha4qbX5o3gY4Hcd6yuse3YJLjO6plkHpiLWxPRmiepL8FAqeURLTT/hM8NaPYDusoVo1XshgDCDvA1r0B3hOsf2azo7Fo4CHFhGb9Bg7SfQhFFNfEjQdXhCQpBI5WhRMUOoThjOJmEXDXw/NrhcPbrpxBR6hpzIAbQKFxrVE0X5OCcnflbpj8L1T+D6tIQrfs5EcBynXbZRjNEoCqRxvdpq1ToEWLWRVLpHK0FS5UPX3wqfF0bEQwBN9dyjqiK/QjHX+4t9LO974gxYw/M9N3r+u5G/FHT8j7+G2uBXOwm0AvWHPPptBqwg+f2Tf2zB8Ui35fLjanRrpdYKaZsEr5YmjqtIpRemE8wfJwbudnjJy+CBnfonf3jh46SlaT1OGTet3Auy2EezJ2Mxl2Ctnv7Yg//6RDuUHkrQqnpPUkkEvo04raHUVQa1VUNHSgLepyUSLETs89DeFYHkLTnDfh9z9+KWN0/mGRi9fvp4XNtyctXFj2HMmnoGe88JUI+pZ9TVvwIIRrQOrUUs8DU9V6p1GlyhRar96RH19yFIvdBNTblyW6UeplQlw/QxuJXwfwqIyCwKgIgM753QSzyHRYFSpBTWbhjbKj9a8npm/ANPzNE8GBlqoBOs7mz8dGjIMv0dS+Orl3EQue+UmLnQptHljxHPAei9i+FVq7sH3jgy/42DW19mvWaQBvVYFMjPs8ZzBkO02JqtURjvfqrXeHJ9lzqoMprWIa1EZ5OLM8EdzrsIps03mefTvHUWyaB3AB50s4c3r8BbQrDcQUIV+v032i2Xzsij0cxf3lD5/s3YdCe3L3NN6HyGrFzU+322P+9Sl919pv6f44Lmv3o0TTzcsvv5UHQnNv3z5MSzs2HZ9aWOPiyde+fCjQ29doLNTKhbC7DowXIO4E39jOf2dAylalQUhI3bExWEVn5ySxBtgMS0GA3bSYq2s4qC/mcMjkuREg2DT0tOVwpDsYrhqLkyMX1Dfet+tJ/ae/SihPun82X1PkNCLrfGxh//cha8Qz9FT/MjGLW//Ez6nPPPnbDquKhjXH8ATUlBxIJU3JNnibE63SmtQq1M9ZkSrtMc7JY1aUxF0s23FTAx/bBUGnMZ+igeI2sNK41CWYOfkZHOaUch7rt54oSF0ED+wYTuuwe0pJ9i2AY8PHTz03F9iAbkVukxefqx24ww89DQuxMPIM+TkWVL30MbahbjPV42Yu/X/EcF/W5v533+zmanH+aQEei9+hNmi1UnMa50f1moHg2WyiN2fL99/h9mqOeH7zNfP7j8m3+8Q633ZzeTE1IicqCZvyvZyxC9OPRaTmSRZzSAM6KDIkXxFjigRJyeFIbTKMSoLtNCYMhyCkJSWZOKzW8WZMlq2dOqtVhUt40Wt5tJgC32Tk7ip6E5s7kE4coQGMYYr7tCqHVSFcljCOhurXM9VPbd+43Pk3JraT67+cXnWjkJ9XOY8R2J1kqdNl7uCVkOHZ2ee+Nz07UlS9dFHS3Em1mMrzqyuHtKu63+nkYop2pnxdw0ceJcSG7YbLHsbykS9Ai3jTCZzBp+sVvNimlnMyjYneNNKg0av0atDuoTSoE6uSYhQbEU2pfBy8x/SMItyUXcYMwuzplUJLeH09EhSI7fmi7/ISTyLXL86YXvfutA327beu2Pg8y8ce2nSkCd3bpFSPjhEPvers8iqc5dT0kNXpPzL/12255nGJbm+9Vu37lqxZslsWhlaCzMxCuXAxTwBs0nAomCzazUabBRNOEEp+BX5eSdvvrVILjHTmaM1UTjO3hDIJiV7rg21qcXM7J/2kPFtC4h9GE6fInQMHf75/uQV87gJN8//g3yhRLcOk6zstKBDINWXaOZTnE4N4hPF7FZmlMa7JXfL0qDbyaol3imjnxYEuX0yPwLeV5BvxSxvX9b+Oc+yaW/suv/VR8onPXXXgOHjn/3wJXLtlyvk2tfc8NnL339t/5qde/ndfZ4aMX9j65JFnVv17Vo28Z6pv+76mVy//uFLWCRTHz1UWXBh+xPPU2NR3kVAvz7UO5AtmL2uJB22ucxSeobdx/k4sNx9KT5Ox/t8hvh4T2kw3mxQNSsbxVJo/lYy2eFNVRLTHOHs8oLYWTrsHu70WnJqzerqupFLvvzw1wfn14z/1+vDnxlZMnR+3xu4Ezdv+YnBNRk5a3s+t7ZmQaYuce6Q2m2e1rVZiTv30LFPJRP57RLVvdsG4lTuZJvNaOSRG7RvizeZhwalQYxkt7Q/hi7lEFYJcZHEADBUwikBnbkiybOPu/Yp+dfjHebsWbd4bt2ciStPH+0w6dRvLcmv4qLl8xpAU7pUVzOjwpcy4Z51u3aMb9cpvsXFN6Z8cOLbz9mvLYXPGe0oJ7pKmlwiTa/RgqFA9brI8WJ0OaX/u0YaAH8ppkrazQoWS/uLUCllAyeygmSuCGQ4zWbJZTKBfRmf4DRTH8xrTlhJp5PXaGylQQ3m9c2i3cIrGVtWNPrHtfKsNo/VaksDScFxQuUfN6799Z8//rhBrLu2SKFZwvpnd6zXcrW6jbyejCMz8Bq8HG/Ai8n4m5dxLvaTD8h7Yia5TL7HbpwCeGI8GPaqiVZxx0aV0WI2gYXO+EtsFXelzFS4EoMF96m42zcqIWVs3vZVQnnjjTGjWHWr3c/xOuXcOkEoBZl+g8r0W78BbkrBlkxD/kCCGjt4UPq9cYCEOGdySgrS6SToEst1YRkhM+6MlcQhL86g1qxcMMfpwkp9TsrOlOJRRnyhfXJet9z2xd1L7pk1qBCPt2V5EzxtO1X2T9pS7dQkDiJC0c4BpS0MtZaO8zc26nP92llxvfrjhmvDpp0LHURheSWUw5hnRjRmWh3LSH8LTwM6n9kkGh0auaZTcQQxFuBerEKWxU9/FW/Pxfj+ZvVLT9WdvXxEKA/t73a184v4VXwXWUeEyyx3Wt4zuZRCBHfrlKREm17fEtFinKo8f2JbUOvLgm3bmtMzfem+0mA6MnsjpbPv8MsdmHGsZE6pP+ItLGxKrylg/0rA5D0O5fej/H5+4P59H+fdt6h6xaLCblsKuk5d+c7JbY9eWFr36NTu3BH//H/NffbFeu63/Suqajq1sCY9OLjLuhFla/KLOrnT3tm37MMMfeDC9G15+uULqp64b+oD4dNpYQjgrh/DnRu+/8GqYhWC9q9T2cy8TWcQnS6rxmACVgxE5hARqwUcTh5C/mgD3af4RZRgtCIYPBCgMCe9bXb1ReKt+3Kl0Otj/3TrgrgQd66xlD9S2X1FqGNlEciEOljHGlbZLpFWn3RoNNp4MHuSkiXeaLSAEDVrE1iEdUziUiwTYMHrclI823c+JjBlMSnU3EI/vvyQWr375k8XG4ln24aHDz218QnOR34lnwzt9BG5KKRgLU4h53aePUf2jv7gq08+CnPLnTCuVNQm4DKqkMOhSuDTPILVGqeLS4atYNZ5m+g/Jjc9nD2lLC1b23B9X1hUnNJh5fa1C2ApC7v5Hxz+3glsv953W2HB/Cn76jntoseq0lLHD+668b7StXktc9KtPnIRJ+NCT9xy/+eKBnUQ1soAXKttwG1HRpVKj3RCQrzepQE8aUwCYE1w3sa0Cf+IDyowe0CHDf9sqDmDlnkcNvvbzSD0uCr8O/l/lH0JYBRF9ndX9Tk9R889k2SSyWRyEkJIhiSEABkg5CKGwxgZbpD7CsglYERE5BaRQ0BAQIgYI7LIJSKKIoosIqKLiCyyiseKx6rrIpmpfFXdPckkwP73C47JXN11vHr13qv3fr+b+4Nv1u947qW9Pf7+Pghu/ddjIB7B7k++gP7z9vl3DwHD0Y/k886mb2mijSxUe7+DliiNZGKsNmiy4PtbJB1bHNDd3golBGCN1E2enDx6Lep3NnNUjLem48F6dHEdU/pkaMNVPV8rmevr4YJykkyu2JeylCb5TToLR1toh11i8I1bQCQjYZrYhGSTKYwyj1eYD0+KkYIzvv2ubtumF3a4EuoSXQhZbwERDgzte+udnxof+3LpU39bid5Gn1AKNiRbgSWAnGP08HtFJt7p1FFRFgvFM4w30egiuxMnxesA3hiwmpO3KNVWjXAjmneqRMpkzHPgNW1k5RIQglHC8Sag7mC5uXARCqLfzy0K9Afj69Yf+Hzpzd1g7sNjyypfPYWs4HMtdvQlkBoaNOvLh9EsL5z8+nF0+dZ+1PiUgJKZlNcuP/Rg6GnjDay/b+A11Y+pxLKb7/dgl5e1OeP4BIeHdQo2hnPEmHCDTTGcJMNvSuF1RZK/5FwsWYtbiO4mqOWWSP2Nm0ATYwQr9/25ld3brwHdD6Cq4x0HFGZkdC2YsapgA9qwH3zUkFycnhSTxVSmD56GVsORwSByzSsXFkqznwOLQuPpInDsgX7ahSYyq3iUl2CbMJrq5nfbLcBgiJIYUZQsdIwrmuKtEogyRmETymiXIsbXV3j7+Cp61GfGk8xiTYCHW559h4kedOxMdFJdIcJm3y50E/1Rd+AIZ741Qfr2rw/9sAf98ju6gT40omTwuQYk4P3bHbr8zud6dpOC5c0ewzKXglsXJ9LJFosxKj7eSNOpaVi4KBcn2fUU1pBeIoDZEQV7EUksiixin9wseyTN+I+qYMqkrDJvIN4bwe+HzuQVNRTlPzRp2IQdE4dOeiivd0OP/DOHvijS9LCCVLQFgE1bmrae+Gx1LchDpx9d+dmJZwCz9Wn059Yg+tshEu/RECuBVIIonho1kYizcrpsijxdni5/RvbVqIn5batFlAymGc0ZTFWe1pVTirc3o9nbq0qJqJ5Rvs8ek99Xo6AkSkM+kdU6Qz6yij6lTe060TE32J5ydKHYn2w3GCQhJsYKJRMb57brywJ2u2S2mcymsoCZlpTMOEvEFhXegiMsWTZ86klcKRXujKQ0YLsRa2z63IjcvMFjxi9aiRbVo330YdQLrDx75aTVvsHy8LRFj04PxRDeCzTszf2rj1+IzCIFI9WT6kGRWaQqk9ZX7FmsRTr78Z5msutYExMVTXMkCcRgtVJlAeyAacoChjvTMclhCIbEHMwt9FpyVhefTPf8B/otDl4IpYlA++fO0zv+gQ6gnWAuqPxxf8mLrO/Wvzagn9BXhGLrcTAFDAdrBq4bEM6sYc/g/T+XulM08n+ISYT5j/B3MuRaEqrpe94rM356sWef7IyJYQhwnZ5OTHJ6ywJOp9Zux32201pjeUBrZmIikAXulsNGSqMJ1wNsBt/sDrFFbfK1UNrwXvQj1pqvobX5Vf1WTOrtzy7s2CM9r6gHrJsHu4QqFp86/NYXY0H9h1+wblQa+qNxbtTq6O7jboHj4B+hp0L3rFi5/GGljpuvkllMoqlyfzJJPJbMZh1DqG8Eook4QnsmabXkXEgwM+aW1hfeJaIV0YEwY1WrhlehX1AjOhyqxTvcooa5MD9Uufjkaycuy41tYbRSGrmsVs0wkleDhXB7UTqdnmNZs17Cu65FMkpY+EkeL6U30pw+TGSXfltwExBZV+Cum+Uf6sBTZy/V165EtUTs/0AMXXvsYOh38McjqsirUdbrsqwfUGV9pyLr1vDJagyWq5v8MPyJN+QM51yqKDI7RM5vXSRHfLXEBxVYSGsowkur04sczRL+ENYso2D6wpt5ZEKjXOygUH/pUOY+1L0e/lyPFoFawlTFuqi25xRqfX6tnAFtxxo83kxpDAaG4Sna6dDYywIaDcOS297GK3dXWSSoFnhnbKnTr0UhPI1V9LBQxVcnfp0Z/OPn4y/vu3wO1H/+EQEtSQnsRVWPrX/yodtOTSLPOYj2wK014NEpl1trIbzDDGvhjaJBh50bncizrNUmGssCIg3YBIuOkzWIudlf97VhUFdxnGTZM8uLBreegzxbvjMo7Jy++bIOrnkNbUWvmj9cRsYQLYIOQMNvgO/W18yiX0OBf952EkPwcymKOyavk65+N6fX0wZRIwADZeLN2OTTcDT+kEFrMvOMXMyiIAiTYLWvde4J4ZqRWcuwQUFIZ7ywOnTiOMzfBbscD70FB5wMHd0Vev09+As8FeoKTyqPkAFeD8WQB4nxY1nLYc/gduGRcikMzQ6vjktrh+fW6SjHGkdMTnaXB5IZEWsc8TbteleG5hYoyDsAiDFRh3ccfgN9iL5ven7E1XGnzly7+vn5s9d2Lpu6f1bVmMYl15qYEU8dcApRB9acvd53W4fsDaueWL567eOTHs0uf7DPPe/ilp/BMjpAzhPs5HcaIJQ4jUaQBKOJZXR6rF8kM0f4H7MLfeFTqVYBBotMXNBMpuY985xBoDsd3W7SBM8cZQ8FL1w7xJy8VU4nnb7SKJ9hyveTV+V+dVVeU1alsXlVkpUtnzZl+O08TTOURmQYLRY6yDMC24qJKNKd8MiTp7DPXf8wNARrsrXvhOaSGcLKi8xZF+KZN33LXJK1VobfSZvxC2aRttrMNNm1BX15QLAofFNtMZCx9PJY96eoW3YulmLmUj1KcncbuvGR8oLcgpy+3YZ2ps2s+xfkQb33Qqza778IA+DIXU69ms8M255Fqe/r5LU4lbrtTKmpKcyIiZ8nU7ed+hBGKaL7ZJTWdn6bWYAaxuE06AU9ZVJ5AS3NFfrhQWyxRhQ+KZ/824DttJqqoSOmLu4Zeu8Y2BdlEFxaMO4Ayqha8qI1ri59zAqmR/BEVmaVnc5p/Ofr9X2VHZ2cMeHWKxWCbU9piN49IuvdbH+UwGooGkJK1buUIKtAswo/LJuwrVRfki9S8zbsaNxPFO/m0PfNmhffoR7f8T11lu1awUSYHSmrjTXgS7PArFGPf9pwOsqzrPqBqkWcZ/Ix79WvPzXx3NX6BatH/uXFejgkJ+X4q7B36NzcmbF58ANFYvm5cn+y/E4GkB5RLJD7A2mBAUxZBL+j77aNxBfuDj/3z371cCD+r0HpitwdebwERl6jBf44Gjt8gtag12sZijGaDAkafCuZUV09Q1Q1sK9tMN/XciMTGcPT9bCkAZbWN+xsudmtr8k4Eu1PdneHiiWD9yqNXs+YCFMjbXfIFQAahidYTTzN6P/vvcqrsFVFQsqwDhT6E5WgfeDW7wBPSfDfwYMvvnLmS8U4eib01FkUvWLl0kcohbWWE2WNQKoYC/3xtFZLSbzNaiV7ZxRevBY5idmO1by2vPkkVZ7e2w815F+ErpbxtpDqYcVxuv5n0hDSrD/+c7D+1fcvM8/U12PV8dTZcx9+sWbVymXzQ0RTKvv4Ubk9ZiLBpJbJQDGUxcqb8YjwDN7AtS2DcpuQKQNCwKkY4ImXTR/mKPpXI4qqxxuQFkQFXwPpYPLrH6JfQf2Zq6QFF1A8cCxZQJDW8cpehncamcveabNpNHaJZmJcDmxAOBw6VoeboLOw2JBnmbtMCrHhZXBMhnin6hYTT1nIwSIFLjTsLG24cQnk70TrUP1XW+5teAA7+lYhlAXP2tG/WF/FU1X70NEraCmanL/elgA8wLjxOoBkXJpu8KSKhJypYKubRM4tei4Kz56GdscbXeScU8fj9uk0Iq0x0/YW4sFCda5uj1TIB520arWa26ZA8N+il9BH6AJ6SYu21deD0Q7QH7ix4qpu3P7Tt//88Yfvvv8FXkHb0HRQA+5TzFiQBypRDdohJ0IQFkeSCHGOIPzhea2ULR5Z5m06ndbMQY7WMg47ZS0PEPsQajQSASPTtSJwvA3iBHjlY6FE2U40kZCK0eOD89HC02DzjqfxgFrA0/VfHT4O5l+Bo0Lb4ALfuW0nL9wqh6PgiUeeRFeamsLcaFhrFsn22Bw881WcB6+ABKIHos3xWjyyZjrRq9NKruKAVpJsrM1ZHLDZWdCC3Z7fNtXWYjTnJeY5GJ7jlBJYIgXeBIqNNxk9CfhFo3nOJ9+AuM/daPf6NWt4XfeTkz74B7r1z2/RLRAEWeiHilWz4uvgGjAerM3ulvx0lwTPH59UjUC3vkNN6CoYdREsBjMKx+ehP9GPePWSeH6FHAsnOMdl/mSX1ap14PWLF6+WifcwLovLUhxwubQ6XUxxQCdprbgztv9iIbWxkjiiXuJp2YVo/p2dy6TNr1m58NpnKBZcAxsXLdmCLoAs4HbPr0XvgajNj9A5k2osGssjNZvqmNIQDPRHz8DN/XqhMxVq7SdNUFLlHCKItSvPMaA4wCiUWxE5RLIjQBchcx1cRx8NFuPvQCoNLWbGy3HRu9rOJQFRCtvOxQFy1f/Bds5VbWcvtp2Z8TtCcHv1ExtpmL4VvfOHfs1ktJjA2UHxEPgn1iIldO7fUf6aZlQjOcZeKu/ADNbuV5lixXLGIm0SOYYVDZKe0GVqsStkMOhpSpJEXm/7b5ZzK6uZ2NFw1ybw7iGUC/71ForbhNxvgl9Q3iHwDlMc+hFaQj+GzsL00Kfyw0ee47YUYTlfwAzBUn2PPx3q4ywWveQRnJw30ZjgSfAUB6QEdwLU0gkJdlHmfbGLDCEP+j9PMeNlLrAwDHBYVpqRWj3eHLAQ5AXveWRsQf3pNz4/+8jMf3/248LDrz+1Fp3eCBc27HS1W1Lx7BNH8oWkZeM2vliysfqBoW+uUuuCR8mz2wnbL2Qj1NK0jtNJBlrUMXpJZ+NtDOGnIpXPbciA5eAgz8sWc14enldQwdOw/7lnaTa07xwWxLJD+XRd4xG47fHHgkPwTdQTsVGcDtvMH8u1Prnd5NiVISKzn9Hhj8oWs0iSzQEWD50WiqzIU4LE8LY7WszNc+cz0ZmHUAqk3kY6Up0d+jl0mswR/usHfH1j06/0NXx9I7GZAUVLokTRZpNEk8MYXlcS4O3AdhebGZvM6pBj0aWv7UGHY0Zsmp0bb3fNWOqFC+mjaAsa9rZuPge+AT8rfWEzsWRaqRx/FM1xrFmSjKzRbuMNlEFLmyTOaJMZbSL6E+mQJOFBJWPLk0w0OWUBG7Pwe3c6SLuw1toFXV+BrudY150B7vTtQ2fDC41H6WVdNoXK4ZEn+gTnYDEtmxoxw1oq3x8HcSuARoMHU68DQnEgExQCCIBIayVOtDWjP9929ipbWsrCBdVwbF1daDMeW5QDzjQeAT8hs1wF3/b0i9y5FN9ZzkKjAIutR0ED+eIAlFhgu1sWGqiuozfuCR1iShsJQa16IjlLvu69YVwzGe+Mk/HOAJWG9dta+T6JfjPRbywtaADLscUBLqzjIs9J1OgMvRZtqUPb6YlE1dFHnwzX2Mhnn0PkDLPpyMoWc1asW5zYDqjwp5g1LolhbDoWWwIssQT0VIxdZ8fCozO6bJwNaz/OzkbG01vhtURml1kpUoydTMDDSAm4nWJJSB3PjzHJlJvLFqPraNdN9CVwN21rBHGNl/a99dphej5ae/wVmPQnumRDieALC+jw+58g2UpIeBzok1/e+txMV5nOvQ8WpAT3Wj5XM0AFuUeDlTnB2vyKLA3Y2uNoXsR6XMQrXi8wkqiqb3A3fwV4W6xu5kpdcG4dLK57lmhqphSr6NeCJfgOW/AdM+S8F7yCOVHUAJPGZLXQBnxpWuTNkmi7o78ClMMrWc7Ch+s9Lw+um1BXN+HV6buOYnGb9lbnXuBTLHG/lBY0rAVbVN66Grk/WSTuRWkAqYwn/grkJOKAFQdY2138laQIf4W+Br7Zc6tHHbhet5p+rfGI0iFlxJbwWEsrHotOQ+kBMTcYvYZ4LFCUSO2/rjigsfHhYWt7p+Z7Rbh75H51W/fcqlTueDhYFroJ5jLFwTL6MLGQsEyzsbhfOqw9sP1Gmc2CnmEEkbbZKRPJeBK0WpEYSoK5OCDcZmG0DUsqhkWOB6iWBcwA+V9evPoFSkI7IQMrHt6KzoJ8YIGLNj9btwcvhmBw6L3oGUqxedi5skYmVg/2WAhvIWPRabUWgXY4dTZKktuj10iCsaUtd/FYWlrjI44KVizxxGsB1SD9/OlPz6H8OsZbV9d4BUQBYd5auOjZzbtfJJoAq+8G9MxrQ6pIjphqhWkUn0XCnhJ2GnmrhRJJS3hB4rXFAd52N58FOypKC0zEtlJvPwDsunwBWz7A//jKhevgInRmZ4Ny0yFDB9+H5SAfa51avLdHU0XYH9BonE7RDLG3QqZB0rq1eFtvIfiluNtoVtWUlvCGwobDYXnEVwkjTMpHEKD6hdldVw7e+4aITYs044s7xj2TmberH6CAiK5a89c6Eo++tqphe+7KaC+6jG6g39DfFB5fzofbRzyW/v6M2zwWOeWHJ5rKrcNt1SnpP7Q9IgNAgZi6m++CXZQU1XchpnYrMkPOh5q+/PSWAV2rqwMea/DcFwAEf3p6x7b1T+/Y8TRMwm38BGj/QzYKlPN1IzqHbl24/Omljz/7DLc8CYvZCdkGkWXdhCXbwAIWiozdpsciBQW9HggCNqwFSdu8cdzJV/F51Qw2ZSexksBywrdvg4IFSz44CL6rQz+gP4AVaNGFX8E51455GxqwMjkEKo+DB5vugGcptMKzJAg/ci6nFWvVsf4usTEOSbIa+aSkKGg0s6lpcbEeLHfRZou5JOCwEMagTAutoWM9FosnljbaqLaYDnIZHkFTvyvcz+0oD3dC/bFydGbfTl3yFs577OWtewj4zxpUo4L/LEPWqGXWenrAE48fu9AGAyh4aEu9WlUvo2uAs0rdlozlCKSwTUb223osWVFE8iWzmdY4TTQTHSOZidHklrA0kfQuTicTDNv+b8lvYWUmRq2nmWkYzr2JfgWWxgHb8rNWT9lVb4RpoU819S+sfGamFZtARuxptouPWuV7/sVVxw8WrM0PV6rLtkC1grhDyUyy+HmFXFfyf2KDKlg76nfukb1TbCNyPeV8iFhS962xUjExvJ1heKuBiXNT0UTJ6KxWTXHAKulMpMaQj2nRN3dRxCq1ih07z/LJW4slSXQyXlXx9L7fL5xBfdAYhFIPvtRZNSv7gRu12779EdiILkT76aOhW6i7amMGg4Pvw0pa1ojcLtkHiiKnbJJWqwMWi1Gws6ygkyFOJKylgCQJJmLSy0vJ9j+cssnHt8aIkzYLbidZ8YRJ/tIZVIlGILGhAdyq+wVYH93EFKOCTbiRskVYTHjkQ3sVpY1nRkXHwqP8PRX2BHSy1H2iSJ1Pljpr+BRbqXfWheudYe7hCBxLZV9minGfZUuGAaKWFQQtiVRqEzgoaQDAnhVo2fpb511GhCq9MBMtrIPldWh1nbL/Nh5FS9QqCkbgvlLtV5rF5pFG4EkEVIWdPtWK6FW5mokeuzM0qZ49BDJQF7kOJ+yBs8vwlVL8ZpYiNqpG4BgGlDR74ZTTeCryauqhnOyNg5/qCLU1Uwp63KHenOoFHG0q/lvXm3NUrxmtc87bXmE22KdcwXuXinX8iRglyyC59TWSmq8xEYxWz7Izm3Pn5avIq2+QmolwTl192abWOfgtWOC9mtYpbRHvXPlOPjFSaYsjLAsDwCV6L5yuVGJChqVASyUmSbEdQA8BlzZuVD7J/9dP8vInSW2U8knI4dkiPNJBlEVXNl2Qq3lcfi1DaVgABJaDKj8IkEUrW6EfIv/oyuBeegB5oKwVK1fI+YD0CVAk1+WYDwFW0FCk7Cdc5mdJySWJLHySzj6q8/yeSfQJ+yCgm4v+0/abLBA0LI+/eSpcIOjgIO8154EcKXpUzrziePaQfTT6z4Mg9jMiL4XoJphC/YRXnkSFr1WsXgtikab5yFbYHd5kmJJ3Nann/IwpTgN7qPHWMOAaYyeaEX8THpC/KVHRfh0DZCuYEmgdOZRsLoUEKbkWH26SR+0R2CB1njc/rkAfutjSt4tAmIGujmzuY+TVjeTqGj12GU1mPWsk7fOdar662luP/Ds3otswxeAc0bk2uqe+ZQDsg9Ef80aDTkPJSEShP+EB6ifAQy1FNXam4g8Divfr9aX8kaa3XtXplN8aTSlPmdLTlT1/Pl67y2TcrV5+jwZCgRYB4Bk8deRYkWJZLcfxAoX9DpEnx8YdiP6U0Ud8pogIU9jtS7J4LWwOS/voJHoZ2p51MgsdABX4FxhBf+tuvMVw7vo4sAMNj1PvvRnf20bN8nfR8TYjg7001oqVEW3QajgLABzLM3YHto4g1kkcpbFqtSaDRaczawx6Hv9jJYptRsjH/2vhKmz5X4tvGnZQW1prC7c33Gj8AswHI9FzWaeyUB0YlJ5O/o+fkNfSwbfuPbHEIYzd4wbpIMa9Jw48jB6P2+NG10lOGvgdDqO/aqla1tB3qVreNX3yhJkzJk56EN6Y+8jD8+csXCRXjTZ9xe6lfqWS8U5HYSvMRApKsbyYmCT59+imG5oC9gz2DKzYY1eQAoqpwf6OlJEVO/bM7RqV7k5KSu9q6yk6jEJJqcvnSy2MovUJ+fkZRXr8z+HkHE7ezMvM4S08niYSNTS19unUQ2NiBJuMBM1ZKZM24i0ziQQQI6zjiLfYO798496hBw4MvRf8uGPL+udB54274YTgp+vodLj93qGDA4HB5K2dz657HuRteKGxc3/ltf7gh53Pko9veIGB9fUEdWRP/Tff/nQVnP7l8q1y9lAQvvTiCy+/tHv3S9988/MVcPrGlZbnP/0dPydjupiuA0rVokipz+E+vAo5ipW1xQBqFnTL7EjRfpGBNAc5gWex0osk2rDcxpMXyY0nV43NgrHhq9AUAyl4+1VUpg4Ye2cWJVUC6HIZaZGRZ5wBBEkFoLlYgayWay0thykWMjRNDjUKw4xweELg6uDc1fQS9hAZGVJfR3vgO+x72F9Kxru7mfhLZo3VwkhuSiO6sULMLvSZ/ktJhC/eZoIVlf0eHDxzhEHsswZA2gN2VZVMmId2wU0Dx9xCVfguo/Du5pXzOvr4Uw0ybJ/WCFmeNVtoXuArA5RgMFUEBINgYEVRXxEQOdYZPvGQS5nuBCAEFAAjmRZGwQ0CR0An9Ne6fftoU2g9LAodAysWYl9HXMikPxP6nsxAEYnGsaextzXN79drTKaEuGitZINStCseW+IpRlesqzKgj9XFe+IrAxqP5HF7Cj19PQzxYGI9DsphrQg4bueRJY1s9mFMbSxJtblhCBabyobamllWfnTKhftOb+u6ce28R8AuQi6NhsgMs8EP38c/X7Kn+y0qWbLw6MK6hR6ZZLbjb1789znCMktTm5tuyIxadspDtaNG+PMSXK7YZIfRatVroFnj0MZy6e1jEvoEjJbKAB2jNRoMRm0MzcbGxbXrE4jjYp3Y9g9gZ7klE95B+pWpsoq0gQRJaq4mUsGbfHJafphwxBPfksbEViZUa7tOnbJw69buo65c6YTWEB/UvxtwgEKhHZ3ef63h8IBZBqY2bulycK5f7zcvLEQl9wygn/nqn6ET7KELoZ/W7FwjIz4UqqelydR0f2Gsw223R2shz0cnmhgx2sGlpGodTkdlQHDiXsVWBmCcFOeOy4zDDqgzzhlnSUigKgIJvMVQEbBE/a/T1yz68aQImbc5Wnw1bwJF43lLycnD/ycZlCRtMgad6AtQ6HcIwTnUI+lM/ZZNT7/0I7raoW4jhBt3pYDEH0/vLqljc9CUhfn+jIUH3u25oQfQA7CwXft+CwELEkeuqMYre1XTTU5kT8h15w4qnertT0yKiW9nFK0MQ4ntYAzXPsMbx5l5p12f4tTjf0QvOVu4l4nCLmwbg5MJzVtxxclkhglJLZSqpBpM7h7rlvUvWPDiuhX1L6xbVY9s+/ft2x/6oO7QsZfGD/T07/flF6MGVSrapHHjc4df27rtyNEtP/30w+W/7Njzqnec+6cfPGOSlLNOegueNxfej7r53d7oaEkTa6ftGh2TlOyV9FEiq7NAHdRxbgtWj8SWVDrRCodVLQbxKZHRHMKanRfmO4A8Fj5LJ1k3JcLavebhwqYnN2wfMaLywQ1LZi358JUBexf06g6omk/r9h988TevfbotGfy6/Lkdy19DH5Wj6+PnM+lvV09GfZYRtlGSAfKOnKERI2MtDPdnaQj4jV5PuewJRmN6qp3i8fi7LUlR2lQLHSVYoixRqUJqWnkg1WwQaMArSU0t8mUilfXhlKu2O6jH2xpP825sfjS2zpn3Xg9VCMZn3ob1gmEjDO5Yu2IneaAEvCX27Tt4yIBqSDFn0JeTg3jxNHb593K6JHj4+SOvb3/+4OtbH5w9a8aMadNC8UuWkKgTvM4ulq1NJ9XXn0IBSafTm6xWi0XPAZqNinbYNaJoMhgYkrhlFASrHhBz/WR2tkKcGraffG17pfA8kkIjQHsdpGcWi3xG5qXB9PnnofsCHHha4Oa8fvLMgQWccAFeh+MmTgxtgpkL0QB4PnQeZoYywa5loQtkx1PrrSEHZsvRibFNV5gVTCleF26qkz/ajRvo1OlsDB3v0Vsz3YVu6HabXIKJgMtkyqc5+a3D3mxCslLHnm03483DrrL2cRaTHIY0wblb/7V4+e/rN/62JPhuVt2ARYf7l466tDb7+YEvz/OuBezWl5pWPolCu9GWnOIJC9OefRiuALacnrPQ97i1JVgT/6byFZIzlGRqfiPJjfPTI0KvUtoDDA2joggNoZxBF990gxPwnkQ+vZNYdPhzs0KvYo91FnYMtQcADQ0tn8bX5hj12lvItWE3nXLt6eFrt2/59ET8aUr9dLF67Snk2rAwq8215VElWY/vUdEKI2Iy75aws2Tl6bR22oSSgIHRap2M01QccNJMTGQZ0J1zJoyEJBcLsVkuM80jyQh4xebKpYvYY5GLa0nFApPzArrxcsPrS797ZuTT6Qz6pvtTHTOfLf/4s9DO4XUP2aMe7Tdm95gxdB26hXK+P/3Wi8C3Y2ifQWB9e89zjiR0o/HUU4DeMHLy47cenf0Y7oWK94EtNx2V6rdoWJFhGYOeYwGrIZloJHe0TZ1XVkeSG4cNCLzEPSA5BZyrA+cbj0JPt1QUhOcRKhrPVt7aN5b5/drcIIJ/fqBkvRlZTxhfguJphmc0goJLQjExbU/2gHKwZ2LmBIP1zEBk5PU3f+Prbg4i3KHIyFxR882ieQ32BwQocngR6/QqLAZueATiWqQ+tKhoEgRMogLOCW1i/MF9sFdoF12PjJPoLbRv8jPBYHAV8fvHw1UyHzNN2fwaSLFMM7VMi+8/Hvufq5YvJ5hFTb8yx7iNlEA8fUHEeygJ1hAm0ExSgxImGmax6srxWIAP1ixA3x8Anh8gXGagDUtCVewbxC6ZA9ayGQzJO/NQGYTjxq1Lp6ikKB3TIRObKCZOY5FSSSEsJXuLsuaP1JJh8KWUCN5vIlFYiPLCBdqEbiqcV8tmvPDo0E09um4auGjPnkUH5z382uKdwdqeU3Kzp5RWjB4Jd/SckuebWlIxegw8t+i1iTPHT3/5ySdevXzxFfT64NEjh064/96JYO6QUSOHTaq6bwIeBx2ywpvY/7SS2k/BDM162mY3allFt7ScqGY7bGTrNLVwBJuMsLZv+6zu+3Y/X17QrcshzmpcZv/t1rV9r1uW2H9j3WAiWXWjwBImg3FjXZZJrNJUKYaW6KyOKXFxzn6BZvslrp2/XWK/QEw7v4RdXPwG5+YyOTIpZr9Z1y9AEzL1eDN2vjOHnkynop3Gs/IxhtF5MvrvbV6IFEt5TZL1aW+pD09JTqbv8jrol7sgf/K6XtMG3jM4I+fR3DGbetUMLBuclf9ozsQNvWcO6D08K2+hb/Tm3rP7Vt0HKX/+hqmuJE9Uz/xnpsQmxUWBDUU566fEJ8RG98zbMDXek+AkK2k8VceksZCykDocFhi1Wo1oYCgLEKw2Pcux/QKiIHEA99UAAewXMJtIuOmkwoiEO3XK17YS0YMlkjwcvIodQ6eAWvQDqFqAMqBlSe0U4ED1C8HF0PeLasGJZcC4DLjzv1mGflmGruc1hdmrL8FkvL8Q7aqwV2teBRTZ1xXd2vIZz50/g+e2Buuibuw7WBdpqHi/XqA4mqJFrYaHMgl6ZDIJ8R9J/oaGrgFvl4KTqGspGIXeQe+AbvAq/DrkCnngNTnlRvZYY/GVXZwb7y1lsg9YAQ9R4dd1Ea/3Ja/jMb7MXKKPczpKT7DXtLRkEJlH+TtgrynTTbDXSl+rF7doBk8f94B2q24/c2n1rvEDBo7atp7UcJPTgmr5ZDbWr9cKgkGvkSKPdZqjGc0wkD6TMX14Qe9elWV1MgT3ffczwUYjOkbJ/NdnIeFC+r/5ry0p4h7twMxBzPW8F/qbfceK8benowJY07So+duaR5k7f3u6y8fv0Y7KG4Sq/EdzzL4TPfG3F+IZXBDBvd3m2+SLBPvQBxfE5nB1hgeyhnBCtxNZlm5v5pO9BubDbTJPs43wPuPxNelM2GayuGmDJ0xm3jy/kWEPvBsqUY99A4YNq64eNmwA+Amd3LYVFGzbxlB76na/VL9r94so78YV8O53+E5WWA4PsMcoEeuhrn63ldViiwwrcchibQRYViNZ3VZotdIG3k3Hq8kFQ1uH31SzzIb1dQ5R3p6cTnkGmufhgdo1aCrYuKIW/Yo977g47R7a+sEHY/A2cvadJ/roUjIyTH9gGSqAeXAZW49lLN1vM9Iuh8YRTTPuOAtFcTHOOD3XTJ3eajGGw1kRpQ8kjYvAOvG5oHTNvvq68hVpnVfmDp86ZWx+hbvAA35Gi6zdmfGr56981u1YlJB8b6ByoElIA9vHf6OTMwPwuO/E4+6gvIQZM8bsUXInkxLjtDZKMkpQkminzSPQ7uZ8HnN+m7RJspHY5LaoZ4hyK9lwC/G2knb5cnlWp3tXbRCF2On31a6rW7XpZVDU/4GB1fdNm0i/A9gm6UHzprr0gnWPPLXr4weGPtB38KCCwMNkveXj+VotR5Bcfj2eHwrSvADpOEqem+Zdw0JUlQ3sBMNQPqxG2/G3bs4cFvwV75s+3Me1uI82KgHvm4X+BLs+2ZJOu3ieZo3YPu+QyRrj47VpMWkwLS1K9HixrpLrOvC03wG4X86ZU/tIYADkM2K6uX4Z764WBeuEPAWjH35m3b7dy59+ruy+8vytOVXVg6qG9neVFjcU9Snqv7KSfW/2+McqyrdNX/bozN4zO3QYWjT2kdDYrmVlXQv7usSEKvBz1/zMEk/uyPyueLa8uCdbsIVLuM6w7Eab4kVsz5tob2KsQdRqrZLBbYAGA+2wugXaEzFhplbiS0xKdW+Nt3hbrAF7UhiNkQDXbPn8k3uzsoYCq030zuyxaNOLSzfuQ0f6D7/v/qoZ45hi9GuTNNMKin2dnl60dPcHg4ffj9/qPvJBCreuCi3kT7A/YW2tpUxYtmLwiHpl/y+T8lF5VAFVSPWiSqh7qcHUJGom9TC1jLrpX9LD361rVkcJ25tVlQNLZ0+aNmLeuMVP9C5KS01KtIoah+MJzlwQs2DciM7OvMxJndqXet3xLtpUqYtavkKk2vUc1P+JITOHpPUo7j561qzJvbs9VlvdZ9LsScnZSVk2PdUT9Og+pNtkfVY2PUk3ZPKkSZOH0CCjfawrymkxS7r8zjmdPPEkmZWYT+pPy19hlFs8hPkKpG1YJIwns0/KcXB1mPELyvPwgKs/plb/wj9Yck3Yt7Ml5RBiZmAi4R8POZVXSv6ITJOHV0738YX/Jr996t85XiX7hb1TMEJZjeEUT68lHEmy2eS/vM0pAQTAqwpkvsV+CDpO+ZOqeYs1vDMlVAZMjdeAAf0KDMzhKfin8W1gDHasqYFvAeMcYLq1pwb/cAi/8dySBQuWLFnwyFI0Ywn+Adcapkzr0L5bhynDzs3v2SGxXWZBeVbSIDCskys9qrp2AnMcdKw5caIGfXTiTwgy354y5W10ntyBQzU1t/YAA4RAulUwZQqow/cuJ7cJZgLjXCCFxgNj2ZotW9YQjrF33z05ste9WT3xzlmU0z8ARiVgm+irzelJ5eiWOFaoBk+jB4c/QKznKnSCr8d7Iy3jGnXwRwGtI4mJc5toTXr71DirISHOYIhLoCHeyzOVyQ7PYVZHDc220OpiPedQ1RwB6bXRkXEFRDcEq4LV9A/te4wre31336MlA7YPKuyeVVpVEFNS3LOstFdpb84arA7eT7/YmJndI2vAIwOXbO7cbn+/Lpk5fXKLppUXoB8LS8u6+8tKVeae+yOYeyrQi1RrtHT1LA9ykJc9ehH7nle5dKzpcqmx/vwY3qHtlNTB7U6HGTBJy5u5vM5WQ0mA8nTwlATSO0gd3B2ghrZ2sHZgU1OzigOpgI1twRC5Pap3hwgmIIgNpGAjr6UKT0ktxuqE0CamJCcqgklZfLakZChvCgxTzaAvUQjNMUqJ8VG5kufe4QOHSsbvwRzmKjq9/dCejwDVbjV6z3V4/tCRwEJXduiBnsPb68D8+fMcmV5JMztl6FDvose7nIPTCtvDCccWPfU8OosuLlx4AVDD+6zarOLNzMd2ow17sFaTmTdbabuDwv83m1mxvLkUREFrVP0Pszk3vKPhTZ0kqeIdZN6qe4Z06Zga7U4tqH54jLsBeY/Cs8AL8iZ3lJZwnXcEb9wX+gpm0kvkGqGmG9wcbNVZZdyxFCdt9njiUvSiPo7m2HbpFrebEmxaTuBIElJiDBVTHDBGQBOouC2kRuE2gAI8jtggJ9AtatK2lwMcVLV2ji+svlNg2tH3lqCvnmQv3pr1/mtXioQidK6w5+RZQyfvnDR01pQa67e//AL48qIng42/fHvoEP3h4wiN27tnde2xY/OefvFl9DMVrs7jZ2HLvKN6br8FVBHpi43g0ODOyKfp/dRPnFXksxmnmQp9Ktf6qlyScDDIUbgkQ5/KVRwqlyQcTL1HXkdXm26wH7NnCaKqlXgDQ1AJfl/zKg2j6bA3MCv0KczBlmobvGEbLWn+V7xhmFM7dsxDD40Z88jUMIw42jvyoVmjRs2aPZIghRO8YUhtZMbDc+y72HcndzIYNLSgs2hYZxTUCjYq8+QXp1rkphmxtxP28gh0kM2Kbw32FbveSJuTVYm++3GuO8k9NK8Xs+TgPGtlQ/7s57uVpuVYLcZoV3mR6nNwF7DPkUKVwX1yvEv1SdgpYZ+Ee+cO7/cl76tsNaPlE14PsQ/snGQyuSjKquWYBK9VS2s07rKApJG55cMFRqdaMc02h5yagVUd3WGOz4H7ZY8sL2KPoa3olIytOhL0Ar1fze4Sc8gQu2j8iMZZt37/7Y9//yrzyZPyIpWb5qKu19gJ9mdfTtmHEPob+hBkgXSQArLQeZXjko1ifglbNg7eaDbj3tp0PONNtOloUYzHa0Q00tEtGYZ3azlBGpaRfFqaHhn6lQly1379MND9dGmZr0v0Ack9d2xJ8I1tL7+y9blXXt6iUOMuB/Blwou7SurxwFTHlleSl7924fLlCyTHEBBmCG4+HmeZaZjleaC1mrSmWJcIDDGsmTY4WnAiTvkifVOlibZwuxTk2haUERMb1aXYfdDgaViPrqGbuB2f1P/11JET7KFTQvdpU+2HjkSFVuHh+ydwh+i9weLDJ08co4+oqDvsBuZXLCFF/gTg0kuSxcrzosVliXPb7KLZTlv1QqwUxRDcPmu4JsFHmIZNYdcqUqOHG+Sz4T1Paas3DFhvAhXzHmt4rm5A+X4pft+qZU5Np4M1Z95nikMFTzx+7FPYfa720c2Oo28agg1rB9x3/Dw8g2U3eC9zKfQFdmx4qtetD4g0h/TMJbRBfmV24+PklWAUc6kJsFcoHgxoJGfyICQwR9AMbjW2IvE6hyyrx64/ZdEasW+JlznxvVTNCHB3vSnqBk0iLvFoRp04QVdd2ause3xyb0CxF1cmTN9nyE5PT+NGi/jaBWg7gk21xEY9yFGUmj6gBuMisgdCBQUK6HnBr/m5ufl5eXnNNfctLJ5Ur6bPlEwjfVg3KhXdM8IV3djiHa98wnQXnk+q1+HWLFhKfYsnXN9C9fqidS7l4qYb/BrsD2SRM60Ee7uUTI2GSYmxs9k+yh2F17oxNdXCRTHtkyxJpPrR0pqu/nbQCXXrJitfxeKUcUzM8nFWchgxT64wUFQBu6vrsIzej2z4GgB0vWlyw71CvxcnNVH47683PVycMbTr2t3nXl8xtn7sk69/uJvNTHM/3Q8tRS+jVWhkhi+7A3gWzAH3gof6Pe1OQxfRH+gC2l8BTqO8CtAHdARakKrs4RzJXo/FezjevPUGlo1zx2CL3iwyjN3ASoxNgax15BeGs3o4jvYqEJi5uRbaR0WAydHJdIpoffVTtKDhj/gOgqX8yYb9QJMhCi9vqnvn8qFaDzgd7UOPk4qjoW/kPA8SwafBpqfaHwGvggFoA+Kvg0FDsMZy4LkJytUc0QRbVGDMZiDZtVpOopgYF2+iTGUBhhKiywJ48xAkSEnm8oAUSd93xzRdgtJsUg7iseWkoFk0n/86QGED2ImGnYZZ6D+oEY1HJ8APn7518Ojnh5hbIBp9sw4bVKM/+pwZG0xf9tTyeZTazi1sipzNWeJPZiiLRdLaeR5q9Ux0DK036IsDjBk/GK2RMkhaS0uNIVYPbaFJIjIGwonvcg6xKZx6vwcMRHtAITh49Qvk+eGHOlJZuGQLU7oSvYvO1u2hj628GjyrpHECuS7WI3OllBNGJIuVc8bqWCe+richhioLxDCc2WHGe5XDYcVmtjkSgKeZkslZ2Oa4pC0OT0uNbJIsxuzqr1DQDd8MdROA5j+b3974d3QC7RbQTlImGwemg46fHiodvVUB5vkZ/YMA80wDVWqNrA9UwiFPd0yWMzGx1t3CDMG7LeFojYoSaQ3LcsZ4JzbzErx6CwHEimI0WOnaJY1bA8kOzPESZ5MhvVonyt+hL+oBG9WcKJ94W54zCSgzc35Av2uVZHkn0F/tvzk3e/mY518wQmfoG82Lu7rNGTXVCsSbcr6874c/SNLzrvpVxw7ak9bmq0y4U+RTEzPVyR8DaV5H60XWagGsjsU6RNDpeIHW0vJJrJyMZs5vXQNF8hS8BOAcPzwpBO2c3rhuy3p0dT8cvw2OOxwaAioGdI5z+mF9qJo8yEk3XXjuqdGjdsk+C5bRuXLdjZlggbOcqOP0NOCsFkbQCcUBk1anE7USB0Xb3ZoAvLTcCrzsaU8yUV1wDzjfcG76MzPONoDzHiCld07QuEj1B/geOcgDj0bG5OUTh6gINPwwGRchmir2JxlEUQtpM5Y5gdfyMS4WaIjsWZ06g0Fr1NEWntbKo+FTSfF8rbEWW42KOnvK6EBy/MIuXr15LfrqKEhAw7HQXQPcNjj2WGgs6D7bpbN4CaxQ+KHAC+HRKgU51Zm9xqv52VYZ0S+KVIaa9FgjciCKc2pFAx8dIzA6ghzntFkyTYUmaDIZLJIkiRw02JQWF6pBvDs0uWUULWqrLQ68CfAephh8WP/R9I1TzzecR1expA17d7wgxgeATh7Rn5A5/CAyBs7OSBh+iYyrFcvWXjyubmzX9fGnxgPJ6ImLizVCwHOJ3jjgcbs9eE2bY7FyTICxsU43bRSczYLmC7ucVH4bbGEbiIMOi5fuAL1yi0l9Nm51d5gHclgPYNPQoNmbR2cv3rvMWzQmEFMfPfiBHvFLXlua+cD62WgMfTwkgvnsocXIxJWu2IUKwOmaEcmhnvB4yvAalAfefe7JMh78vHgTyie90BG2JDzi8ZSXqvS3w8JkTHA73XGkG3xSohskxMcnYDE1mOPMJQEvjItzxku4H7bW/bhbR+g79INW+sGsQHvvrynLerZhcUKXqgpxk7bPgM4pS+oXOTuWTb0f/YWODx0ASUzpBLSd79hvAja3+91f4gr9AcVo/zC0F/TrNqFvtgBGTFiJToTl/F08H4nYhxjpz/HExsYleyUjTEkCPEgxJsR4nXGatFQvSMQue1nAbHB5XGWBFIj910TaTTuNQpy5RYru1q3/a5Zy88JrgvSR74aGPrRpTMbSPcvdxROGOxuiho3u6Vmyf0leZzSgvh5eROPpE+H5MnClT20h8zX+gdRQETyWOnwynq/jR7qo6vl7PGeEk0ZeJUfxnJGejvN3+e89LVZ6Wox7mukp9MjdldwS6W7kovlf+nuHyWzdXS4L7R1YU9ZRntLqcnGz2KcqLxlPaWYS3iHrYA7aR3vCs7qNz6qcgB4E/aqLXaGbUIgpHEJm9aFUZJQrZkfhiX2P5HOhSiaHO0/1oR7330NZPIVRxcViYV6vru1cGakWbcU9ZfbyQJklv1MWCfhkdqA1dId2/l6xrlhXeSA21ti1XbvE8kA7RsRaTjQaaaz2vTIiRUJzHYrxVBgktBkyN9ppvIK3rvyT2W32YAdxH/LCURQ+HCrH5rnR0xqtKS85JSXHp/CFqocI9paQJcNy3C/3B0Y+9FDxsyX9thdNmDDh1SOXvvvrQeRX8Jwmvj61es/x/FlZ84csGrLIn93Rmd6+puP+E2/WHz2DrqNvlj+xLC+b51LqfCxbu/iRhRph+Sqgk5Ge8no/+KCr59xOMQVLZ1QbposDShxvrF55JFpw7n/qo081bJ6c3Y7m0wvYSqqYWuSvdKTlajsYjfFa2iB0z/Z3TdOWlBZ170GxJQFK6uHukdkDD2uP7snYR/XGFgcMvmxfdnEg2evzeZPp+O7dOxcHhO7G+ARL+5KAJbz7+1ryA+UxTSdD/Qke1ZNtsR2aj8aVIcIjRMbJoAR/8vLIe8ne5rFOVJGxmov98UgrAw1v+jKcaZnjMuatvHDz12tDp3TsE6ie90ptiq0q79XHhjz13PEDoHLLc+vmgLxg+eqBBQ8/MmTc/D4rBz7BVkrTxL7dXcsnHckXSj5Yt4eGjnbts9JZNmW1xcmyc6bNrKWZ4yMHrWrYmZC4JE+aNSqm07Kp92FPRuWfgByYpbIfynii+Pn6VtwwCs53iT+pNc53WUCygzDKt0yzq2uJYPw3lO8IfpgIlO9kyB5vyw0TGnn9P/pguv7P775stNAXTL/DK60JYoJFwAR06N/oJ+YAQtjHAQylIpQTZGSdbDuU+VMcJhPvNBqxWxvjcpiKA5LD7cCWn8NBi6KV4DPQ+v8NobwlkEGRFA1s71mI4ZecHBHB+AZISNi2SwxN0GzfvmWbHq6VdrQOYKAPQ/ccefPoG/TAl1/Y06BicLOlckw03m/k7BaHiSoPmBiR5Wg1u18lPVQcQtmuI1RmeHOV1yhzraAyHx15N/TTATCg1BtfvLOgamqgXd/ObN64g43X2UPB6vdWP3UVcm9u7GFZZVWjsN2YAdjijfNLwCE59Xib1BtZIMno5C33w0aSxYxdNUv4XkR0abABTdx7bbRFcAwYkVQyuCojLjY5s4vrJnbRnkW/j56N/cScefM7iQuEqiHBqyq+YCnxvOGjxPOmZzMX78jW0U2OrC9UPzNarSZKUCs0sVQiq2anLJUOqoNcqcuZKU+HFCfFcZkdk9qVB5JiYl0uOt4pqezPUoRYKnXLJl8r6AdwF8qipLCz6gPNMJq5eXlsFXoNfYFd4zdAL9AeT0XvxitXL31+5YvPPr/G1Oz7o9IqaO/9eh9K731saUV5Xs9hs7zdsOBuRzWy4I4FS2Vmo4vorBxvSwOZ6OMeoRXnxrZvhIsa4w9tMy3WjQtsIbEIZBXmYzmWkWSx8A5hZqkxxSWckcvA0p1K9fe3s4rJet6UkEC73BTl4h0im9YuMSFZj40Fk6SnXdEO3kEytxRnobmAvbWt2eLd4OFgSXcJ6jKVRPYAhgJWs7IdyOwtKeRtDnJGdB7djJ7zsBUL93ncFaP14bl2LOAZjZ4P3jCV5U51v/HBB2+4p07Hv/EIfIxmtK+pSQPLQS7u+eL2kye3RzPQp7+jc2kdk6vTQNbvv4PMlIHVKeiCEn9cy5xjDuB5zqD6+ds5dZImPSbJRnvxEo+P0ei4DpmpSelSenHAZpaM8XExmpjiAEdrbP9XL5XYFOQ5Je9HxhfOVfKBzASjQeko3heTODU3izn35V81/s753TRnrn/5kdA9v7Nf88E/gv1ql+m65oxzPvEwCuq7+XK66pbVwqTv0dWk3Iz0zokg+fr3ID6xc7v0/GT0xbW/fOxul1Du/vgVMKJ7u/j49u7zqpblFwgFeNH1pqqo+/wdeuW509NBcnGffqVddXnifdU2CHneqBvgL+2S4O/iLwukdmFc9k72skCHThHRI/mX8W/RX3xyNrttloLNq9Dk2WxeOeweB31EfdntYVlviTF5lGS0eKWEtyXEBKx8DjYROuDRIoeZO01VTz40Nj2za+3MaV2mzVlcGUod+FK3vJeGHvt499quw9OL524m8acvUWh8fUWfF6aiIPoKgK83zy1OH9Z13e6P4aM/PuROdA/p3It/b25iemInn9E1bmpi+8SbR3sW5peCSpBBQk3p7qcHyMGplWhUaseMdLAVzAL3gdkDnnano0tE7NABbvxOOWZvNkW7yopU/c8xPEe1owqpSmqI32fhXamp7RP8vfXlPXN5sW+/7C6FGRm5FT1Fv9sokgp0B34wSaIx2saQ+mamFcO6HG/BhtXfv/gk2xSuggvn2pLFEKbOUYogjJ5sMrQyWppS9S+PMq3OQsQksK0GlUlfN2zI6mu3zh3tPic1P1AycwrImj+te00vkDF1RkkgP3VO99c//E9/Y9Xqh8akT6qdMa3L9NmLKtEvP82JS4zDI2nt0QNrIwNwgNT4qBW+v/0NDevXLhns/NvffCui4rHC+SfeKT/ndj+U2C5xrMk9uiYpPenKju6lqZ2tJlNUDMGKhwTJi1/AM5Se8lPD/Z06Jubndnc4gDk3TZfI9+hpyEjJKAvE5eV5UliNRu/XSKV67Hj7rSlMgc/jKwtEeyJkMvrv2dln08nJ+51jmzbem+JVHznhSKdZlULvncRQRRAJhzpX9IvPPprlyTqW6elH9+o6NKP44U1fN6F/oNDMhgpNZcM0Re6ub3hECXx+fLzHY776sWuOfbKbZ2IaxuGfhhiU3hwDXYEe6NgxKxNsBw8SMWuOgWIxS05WgqDlWHeRIKgSpeFi2V14rLCk+RMTgSE6Rmfm09un2gxuO20yRw6RN8YsmZSoaMuoRIRHW48F/19ipcyhAXHZx7LcWcey4/qBhpR6OW6akibovEfbxE3ZXeFOBpOGow0kgFrzkrc/WHqnACrB3EZW5iv2DNG5fictaUSbKNHOKAtVFrAwOo4ErMWYO6AngQQFWjuPuFh40jqZE5WCkdJ/oy+BCGBNfr0VHUTPvQJKP/uxvuQl1ofeQt+hz9EnjtBM8BlB2f4SVB+perpSZpCwygwSTsJqI9EOu8ZO01HRjhgVgcAcgUAQiUByG/SASgyEGwVnIRQEpsa+uzpmPTn+Ly+99FJOv/w6K3ADrNlAeoJjle/slS/eN0WHpoLPyEhMbAoys1gO77GZfg8DBZqjTLxo1Jt1BoPNaqFoEyexRl4vanRaAph5Vi3mwGNBp1iS8lj54aBZPgk/IDw09xC61hN4zqKrvYDntdlHgKcIXfsriOsB8tE7vUDPuhl7wOi6WXtAt17orboZdWjbnhkE85ZZQzewB3A74vw6ihONnNFqoym9nFHc6lS1OWnI05zxBZZw7Tr60trlghNceqYvo30u80tGdnZOTlqHjlm+ziSSf5O5Tl9mL+Lru/xa3myzUgS9W+FazfaZmnlWZdfRLusub4IBO9M5gHlow/r+NTNrJ6aPnftklZEdVdClZrTbNBar87kUAFeZi/AslyMz4ooipWMpWsKGNpX515YQIdGDNivxl7BhCa726jwEq7I5P7FHOveuiIkymq25qeV+fK1r+FrnwtdisU0I8bV0sNW1WulSeC6sFdkjO/zlqblWszEqpqI3JVBp6AR/gl2DvQNSC5BLFWHrajj2hh71lw1PLRqU0F0XzVsH5E6p6DjOOLWG4ruUlvbo27uoe0X1Aw8MmTh2+KApce3bJ/kyE1LpXKOD71sxcYov1+HI9U2ZWNGXFzR6q5zunanmJ8k/eBwdzUbWbX+EBUfO+Oa9silCh5/Iz0CrZ57///duf4upYbv2rsxm+2jfHTqd76s+YUePGzKDA7+zXYvw0wp2DHnamNnqKZP1X9/9hTz14euews/+PN76zXPt26dlvj1m6J+/kj8CE8YOAfaM9JSsQRPGDg3/pi+3fQVFtW+f2vHt8YPkF8aPxbqKzOMCvpa9INeqk1qZFOwP5FDdqU7+JJsjKb57FxaktXe7snM0emOhvyCd75AbZYs1ZaZIiT4Di8UxM9PcvGhZcp7qzc2jU0hCLPaw8gjxdHIKi3cebPg7ALYQ8xz4dV722+mU5JQ8/KrDLnOMpTB5WlNx4sgcU4HWUuYe2ckMvYaS93tcWdg43tDzzb5XFtJ1sNfxXr3/8lDwn/DZF/HvdNOA3r3mV9ATTSmD7L3cjyV7lQ8yG8kXv3gM2eUrkSsqVwb1xpSBUUVTBwbXGwOF+Df8O1x5uPjUA8HvYe+3uxZnv9NJjiaiRZzAD6OsVAIV8GfqRR1vMGgsEFqtzniNyCZ6rWaOxVuT2UDJZzdmXYJbcgKny8bL4Nl/zz47NDtcn0qksw3Ek68ZSlvG0vbRXouKp906x/r0DpER6E5Hjqx+d8t2iyZ4+ijsdXSP+Kw4dOqEBzTbdAexU3p+9of3Mqdu/SP0A6ilk175T2MOl7561+TKoUOfWydzBS5hSSKJCc9uX387kdcw5DAPAKPRFsvxrCeeNhhNkkyqZbRpElySDdiizATOUO2FTz3Zu70HAHcgovnAa0pq1XxQIcL+5/YtbKh9VgjtPQtukIxxftDMcaNFkjFeGpq4dCldFywhqF5w+7bxwUFM6ZrVu8ZWDRqxbT1ue6aKn2+STwTSlLNIG2m+iYmOEaLKAs3nkfifBJWjSEhHlga3zu5qPoxU8YGaDyOxpSDbmt7MzfWgGtW/BcvRDfQvVLMZFF0B84+cvsgEjx7dQM4hP/6MnEOCqMcfVU8CmAoOa3ysCfv4UxnKajXqHIIAdQYmxsVYigO0QSKJaRKD/+lMlM5aHNDZIs5L79xCECYGucNhZDVIQZeA/+rFL68gD0EyygLux59j3QtD1zc/u6ue2bbw341fKoeRKnrxXLlW0UWV+tOMoqh14pdY2s7ZLFatGBtnt7iMOld5QGMsx86fTqPR6uRzIOVAipjut8f3rRzv4LHdrtTyEpznvJQ8h0pLMHfG5IdGrx+79Eg9WIaNA1vwVt39FZMnr1zw9gfvM7oFp/O3Vr0VQ0p3L4Z+FVcN2Fe6f82TKq/lLNlmScNeXFasluF5IcVsttmtSY6ERK8gtktPSoiKl6LwEGq1jBV71ZJRIwkxVHzEwaNMJHR7NNunJJqpVF0qYR+2gOV+kHoWJeCo9gLunfSXrjpd3ZIowf/ilM9/PDxj9KKSKXs2wM31mWlzJy4cs8Had8C6BNSN67RhUD/0N/Qb2rtmS7+9Tvqro2eSvn1Pt61f/bg/L54ndhCRY0HGGe7qd9M8rxMoVhAZg8SJWrEsQGu1gl4PywJ6WjC3APm2qWhXuKtaatoZITQJ7EED6zdvZg9tQnBDaA5ctgE+ozB10D1lrDCC9spxWp5i8Lo3SKxG1JQEoCjyOh0g0Lu87b/dL/J2HronsoJBqK5u2zb4NH0M7VmAYsD1BeBrpdaNYi7jHsYQrioNTTNRBgNvZRhXrMlRFjC5tNj4xRMWpruI6GVrK1gFqyYyhe+sQr155NIAsj4pmDV6UD1dFho5aC7wg/uIk3JvbfH8ZWBBEwVX60JYW2wKBUXshZxC76LdNtfGLID+TSKaBAtJpG/hUZHbKERZDQZJguYoJtZl1xKUWcloxKvVKEmtAcjujBfYjCorV4NGNhfC4jVjZs5fvHT/wd7ldXAz0g0oAEbAf43ErusLP7h49SMTSmJKn0SHdeg69r+xOylzW+A9hyD424im40wm2qzX6QSNmcJOjd1htpktRpngQgISFhnJrE9oZrkI08D5ZArhVoGaVmQXPvng9nbCC/D5gZ3D7kh5sYegwzfTXhAcYbSEc8uoX1jXQb3eZtAIAsMazBYTbqVN0rHQbNbSlEUymGzynoK3lHBW1u2gwq1hhSPa1wpaeNqJncNagwuXKuDCaJC8c2SELsiPHBlguKmJKkc9CTsHTAZ/ITE/egEYKEfni1BPgjyMXz+vvi408xOkq3j78QTjgoYQL04NizdGHUezeoJeLkOsqzQFCiRYG17YJKX9fDMpxYKGnWND32+qr4fPnA4dkBkL4LtLQ++wh0Lp8NPQYhVptJuMld3F7yan8xzLAqhlJCMkOZmUTtLaBJoAkXF3v3GrsVOhfcGC/c9VhcF90cDQH2B+M8Cvitr1Pe6xWeaFgpAWTSbebNCZacZiFUSOMpoJDDot0Sr3hqNVPqJ8V6WjeSafLYwrDH6pra+vrQXPngkdgKPeRxW1cM8kub9JK8DQC+BUaHHwXTgEnVNxbwvk037cc9ICg2g28xZJZ6EZqw03AZgspAnY3bHd3oRwG1Ii22DDjbB5wdk5dXVzFgHDIdQV/PA2+nQh2D9X6flcUH4WuFHSpBACBVfCmvl7OT+ixJ8sMEYjMGkNOpEz4JtbbbyFsmDrQisYaBPeSfUms4YYF2pyhCP/jmlOxLIgUBfKoJBBIjbFlStwvTwqH4b+tal+M8vs3/8MmgovyyPy7hOhE8wU0h5iTcRga0LOgBAFxm4HDrORMzmiuOgYqyhZHDY+iooiiGjYnsvExiYFsRajKPUgtzncFjlWkfYONjixFR7RNuuWXYR+h8ZbT14qmPYeKgKX3kO1tbu2sIOOHl0Q+m5wnrO8I1iMMkIrIDMRjWUuE4RoimIXyTtajj8G7ydaVuAYQYu3GAFSvNZMY++6LEBs4TB8XmuRtXgixsdjBEG4QR6d06Ebm+vRXiIx4bEhCyZWRTBmvpJrCvE9WUAQmnUiwzEGPbmnaKMlShfBKHj3e5JbpkEILqsQ2OIutJOIB7SFfgydIcuEPqZqhROyVijyJxKtoGdZmblEQxtNCq0Ba7izZshvg2rYoh5a6LhY986d9UFUX09Dhc2kJS1GRdRlbspsu5X+dKDXG/AoswLPswZ8d8L5KAE31hQ0waWWJINNxHuWaBNaNaIVpmRLDmwYZTcCqnpD3YZmsGrFHldyXlRdwdXIc13o9xC0cS1NQwry2K4V2bKAYkzg0aAFWjkeueOeGYHXpwyAh6tB+xpCFB4ACgTZ2FtfRQyAgguu4J2W+VOAVquDgsAzvA7fVS8BMaL/tKCzyZjMUguororQ2vb+prBFE+44ZNGOFpjulcEitduyRSPEytx/JLuQJUzRDr3eDFgmzm20lQWMLjGadJ7lOLoMW82AjeSHaGvVRGJ3t5g1qlUjnx0wl+pDRfWMsagWZIP+2DJAW+Y/sQDM++a7dgucZHTwyIzUoT3offQeqssids2vgOVZ1bLh9uBWu0hbTWYblhY+OhoazExsnAMPlcNhtliMhBfVYG5l3PjuOlOKfWNqNm/UhpPjDcg6Fo2cMvmXuoZepXtCV+vomj49/wDcedm+ATuufmRDafSRYCke0xQDuoi+Rb+jy/KJWwN7jT1LpVKdqH7+9jGmTG8adttoe7zXxOfkJrcvD+iyk5MNVmsUYSWUZctgiGtOjTSejDhZzm8LJmGFfBhzJyWSpdAbr+T2ykdU2N6nw8USjtxc9sjymT0HVXbrPHj/urFPoQ/QDlAGuiwYklk1CPmafsjtUD3k1CsHT+wbN3TYuKH3jp0B6w6iM9Vx9k1RoxOxY1MNHgLLvWtcoW3YLfxQHAdc238HIvoDob/89fS+lei+Xk3KGdx7zAXs1YhUDul3nIVK5tp3ap/pTEy28Ll5aUIJNvzT0iS7THZg1GbJ1B6EB8H+v/SbdETOhQDyMcr/Y+1L4KoqusBnuW/lAY/HDgIPHquACA8EXNkVwQ2XwIVAQMAUlMU1c0EzcUFzKzM/M+MzPys0K7PVzMrMzMz6rK/MbM/2PeXyPzN33uWB+vX9/78/et+ZO3fuOWfOOTNzZu69c1i0X4s9qcfarfhOhL1WIfaQ/S5nWM741SMK8IzNK8oWb/1k0R+Xp4yKGDBm56b9eyNStGv3bd9y/22lydlZiVH4p9IHUuJ21X2zpLNiVPRaeS/2xP19Wj3PfvHm2wa65dIjx547uORsRoDbGjceqQp8LxapKpJFqmI+FlGizTWC78X2o4/k+9Ez36uF+15muYVFsuI7lAZqXcD3MrhgyeTKIh66WAxh4A5B58KG3eti2yrf8Xsru+7b8EU5URopJ+7Zc0ATdO+9V5cokY1g3JBX0038LczkHhQU3GyjfcVR7Y4W3GM5vAeNNfJuWiPvgu6SPrtu3bVzrKdEjv05+f5wg5V44DCTJbz3isnwQtiEKfRbep2rjm0DTLwxjyffIz4Df7FSPK5I9QTvd0K7XHTgynhPfWjeVwfkIph8fSkVdt73UXnrE6Tq6mFH1HGgCqPxwIxgSa93NRCtwcVd42HRuZhc2LqIu8lkwO4wRokPvi3p18/y+M4m3mK//xRsJ0PwnUdkPxwrnz8gn8Pxsv9R8gH54NryQffIO3HlPXa62qmGnmyO6YGxlpg9iaTTeHnDzFzjrnNHkkYaXuKh8SbOO/1bumusPGxQyDpVPNSbVf1r7CVP6K7+BGxZLm/Fty1n70YIERxhLzggPdjPeT5Kp2ZYud9u0hqMej0M0e4GrcR8d/1/891Vv12ZdDC/3b4H5hvz5TmH8efyoGfxbbjuCXmQMkS3dC4h18jRzudIdmchUI8C/z2Vj9LpMDYaDBotdUWYhdNzhzEJuUomzLYTN3kbwyTsro6NvumqiSkK6HbhgY0jcstzOBRbn5FbcOzW9lz5tPz6sySR+LId3zq/kkuZN8+iGYj2o93H65+REWbSIFeQgasRBgazhxt10enBT9FTZNKYegVZ62XnqiBCHTFpwUOR9++5elieJCXIEeQHEXKNNy0epQzaleYevuf/sIww4qKlMBWkyMUVKGP++j1MoYgLUeaASniUbtI9O7FQj+6d/9mmsAvl7SSvfWf7tQXyC3QsPXrtvGPzf3p0HY/3Br5ZDdTaF2ZsITCkUeJuIhoT9fO3GPJLLBaNjyvtpfTeXlm3S3aDCHAPPNAjBpyi/N6B4FgcB9A/TEeRD+MDexu0bmD9bt7U149aWCgHN52Pu5sBROFtujEfjnVU8Uy2B0MisgN0Nj1jO4CDxgygO76DI/qdD9vXWkNcvL31Pq4eHmazDwFOvGCQhb5U76M3GPQ+lHpY3CifxYhvv+299OFQilXEL4dfR8QJcAVIs5yyf/WOJXc+eODAvuVLCMJzmUjmz8NT5f2ug8mZv0aS4kwPPsdjUS6C+C5cwBf20ZrN1ENvAgl5+ICm3D2pJ3vZxINKEvyYDL7uJiXqxX/ny8PL15klb1uKkNZr8+euam9fNm++z+4jTFhHtdB5fehXokTDmBLwx9f7t3J5OSJOWlBChi/xgGmdXm/x8pRgykIskmSwWNjMhe2UoOxZ1b29pafdI9yuRrANYwI5uR+jl7ml3HZ0Kwsft3fqCwc7j5LEBfPCkjpTHDFesni0D6CHLCy+r0Xv5ekGE3sXNze9wR3pvUXE3F70Qj2SFSqsxso+9nTHheKdMMW99WjDg8/j9ccSR8rxUFu33KH7tsszwN8Y0nVFOwz8LCuKYm82aSgK9/DQB0eYzX7BSIqO8QjWgVMVriM6EmgK9MovCfQ0hYL/LvX6NPoG2xzy/WdT7ezzZ3sqj83moXyRwr7X4N+khEXiMKILNUcOCdzkNTdME4RHYV8cg6Ov/scNa36ven19w4cr5FfkBz033EcfO/uDmdadoqvzByQNro7FffFweZXcliv/IX8fErszKhhX4sJ5xCb/+ic2XGW+lL3rivShNBmx/WsKM6KNemjo4d7eQdDaNVHRPtQEIrWZdCYd8vCw5pWAE+jfa6f962vmYRefo4SnqHVTwqR1f4vC14dDkyPpyJ/lK1r5N//1XjO9tWF/PXXmx06T9sAjVf+YXnz/7ju3u5Eh67xw8DXpw9ykhEHVttc/ln//pPaV49a+WyJD6eW9u+5/iGkI+mymoTCY007LSHQxaCN9fd0oTAh8fILD+LfUYWFWT1/qClOLSFe9qx5ZLDbo1yQUMFKN5dmzOn7DhvVuK6xa4UwlKTfVGViWp6IvuluW5Q9AKzfTW8c37YWKvkxBeDJOnNd8Y60N1chbtYeEykBjMI9lGgsFv784I4FrzBoaCToLBaVpY/qGhoX4KIqLEIoLUxXn/fc1VaoZSf6b9ngtuf7I+RfO3Vh9Gx5qz1H0ZwzBHh/fQIGZknyvZgRX4Q2ilvPYxLoyEok7lDi32Kx8U25xfBXJY7DxqF5viUi4e5Vvyi0eanxqpPGHfqkPysqIYBsqBri5GYxekiYoWFlG1xHoyNHIEhaBUi/W0ZVPHm+2jM5XIlOdl9GTLCnJ4aR03a3795M1pztXBeAxeDpbhy66PW/RPc+8Jn9DFod07hUhdCcQ+Rn5DDTYvV5BMOd0/fHLTjHjlEL5jBM4ZavrvkazUavDwKmbFwx3gVKf4SW6gLwSSQeZxB17/xdOQx0LphYPL/GSaPfaOp6wdy++/JScitG1ujdW3XVrv1nDV9/zzPNFU8EV/Ito5WBJ/lT+9eC1lYM3+UdeeO3cGT/unbZoNnH/ID0jhLKd7LDGDcYcHx0LAm/ygKlmb6ew1955zDvgzzVvEAWYPY69QSRgxUG6Lhyw4qWtltp4lLNuftg2cZ46Nit3MQ8HJ0mDWBimv+enZ4S1i+zxarv8RJ/Se+YN7OPTZ+6aMLK8jbtKR+R75MoXjUu1+AL+TTylYVGIlWiXFjc3d08Xti2SO/Xxtbgyt8ndy0zdLRRcKNoH3WwxCzhxeibjvKAlfy5fLVo0avFqvE4sbG2TX5L3eQZvS8S/E5vz6g7Yz3T+HGF4RhSLxGHW6z09iQk4YUE41IApJh6Ko8fzmBsubDk9kXEKeG8m5NzKafUzlszr3NneTir3nZGNA7cOeeUMfk4JsoNPXf1G/kW+LFb73uux2ifU9H+32ufwUhzK6hbOkU0ndv339T6NhXvTQJ+t9+k1xGjQao2O9b7/ttB3PX2xyqcywVf5uKmw1S4edkRxqJUwJMKn5l6kL7MOcKept0aDEO12qjE41TRMjW58M+vg2zvbbxhXec8eZ6+aB31/6broysynjuc+NfCBvV1MWugFDaZup9oITrUpzOjK5PBf+HCOlebEkPCqt7Zv7R0xjUXSxOWqVw0WoYsBefihERkRbHdfDzeTp8Xd3VPH3qPzgamNp97TLb/E0+IRZoKZj2NHX/W1D/t1WgnFNl/uPLKvJu1O733QsbJZr9PdjxE+q8dn5SNtr+7a76aXTRrz0hcTrpVqnrw6UnpSidZMH135n79+ctgMi+rpxyKOeXrpNdxgvIA9FwuyuFNvdy/FXNwVe7F3L4z3egKnRFFU+LlRJEVmOT2iKcbE82iKYlLWM6JifN0Nxkf27YKZxYFn+6Xw0S+d7xpA3B3jI49/y2PZ5CixbL7kuwZEqDuucAwRDgxoJhrCv36gJFz9+oHj4LFUcpToVD8r0akGqnGMZOjwlnO+7lL2awFvWYmNtA91739Q6tj/APg0K7sbmHrubmBy7G5ABpg4n4HKKE5Qc9cVTTGPiuzPLNjXzezjYzAQNxoQ6A6jOHRqWpMnewtUCz2J9mZBm8UQnuKIdxZq9UgJJYgmWVJDYV53z+VPGt++ExfgerldPtV5QErwkP88/vaf5mur39CkFO6KtMpz5fvkPfJc8k0eNmK3q6Ox6+/yH9cc+/hqpamI7fQB81Z/g58LTAsNtE+QEbwuo1GLWJBGrQ9yg37XfEMGFf7EjgokJUW8FwfserJverx88F+/Yl+58/23fia/aB5vn/7QrSV7dqzaYeo8vl6aKr8jXwV38SQ2Xap945nQmM3RYdcCH9p5/16mAS4/pmdySeh5s9Bzd8wcXgOmZ/KJouevFT0nqhFzFH8swuGPoZnYLL6ViVKxcI+MYVE8MqC0R3wrE6XiGcn2POZ4Diplun4WeIaqeHhEW47nrMLNFgVLmhoTjWFJhb7EH2Vk2JCnt787pf6ePnpNQKC/DwvljSzg2COtL9WaRpZoPbuj8l2/WCKxUOahVicvyZai7BJ4DVz2cPm0/LXc9WDZRzNfff3SxXX4u87RdDUdZjh37M0vx+zql3Tvxjtb1+OP7uWxdgJlL9oOfW03X5IEfBkEX6B+y3AWLdDXXQdDkC7wv/HFNgZCNqcXIxxsHdj6+VL5399/3frUaw+cePf1hfhE5xqahs/oGl6/6yP5u7wdk57fvXd/A5Y3LEaO/UW0hx37i+BR6LDSAj01zvuHaKY79g/BoxK5H+3h0WOHkgh1h5KZ6HmhswhVZwqOc+oeJDOHKDqzqZrn3+7x/iZJYDkr7DBexcK/kuP9TZGCJUixw349sbCdyckdjrisSvxXspSfq1TYdScqEk24ngq7h1EJRLxEf5UKjxLPa/yqsNJvRY37q1h4bEZe4y8ULBuUGndjCQQsf3DJPyckn6BIPqBHlCkmeSXeGB71CJe8vxrvimHgfDwnajNI8BGn8qHgOOfAgWY+pfAR25MPvsOMwsd4tF5g6dsTixrvSovHSwqWGBULXxXjvBwTvMwUWGJULDwOK5fJJYUXXwVLtNpywQvg724Z2Nv8koG9vWpgO/SCF2Sw6MIQdXrM7fxowlN9aHCSDuu87YEH9muedDyU4CM3zYKR+zqsIkYqdXqQfWOsHeRu2Wtb+zYpb92182wQFvuaGXmNT4g+L1DYUryz3WsW8hp/JqRyAinWFKdKLkFuZnUGe7uPP6tZhjZzW7XIzey9MshnO8tr6fJmlquH0id5tJlBSmmqvG0TBfnJ0LdE8qi6OrqcHOfPRrQg8238rc5BGWHuxAObXSWjzqB1Ae/KyN/n1Lm6gzaJyd2i6X7fIt352QWThLJszrY256vmoUuxXZ7PlutnPSXPwYPlo/i5Y2wZ/0n8gnyUTiDTO58BP3PXPZ05fBmf7SzsiPx7U16Gd/PiDdPY/5mXZmyRD4tF/EPYT/4E1zzLl/HxDPlLMh88PBa8JWG5bGTr+Gx2RlAL8MLGCCPyZDELJE9PnTvSIW8v/lEw1Wv0MEboLRqKejxgd1qcTA4PZclQazgWD//sLTgcW1/ZL1fIv8q/UOPRj1568tQFvPedN8mcv0aexx/cvqFtpehhUnncweH868gWttMfH69sKC8jPJj6GY06m7vFYqM0PEIbmF/ipzVrzSyomRVZR5Z4s0Upsc0ff6nV6TGl8x5/CoNeYsdnsW27p/jga0AKGf3JV7gvDtu0//PKhfvPHJn3D/vcmO3vvATKXW85+9aFj8icq09dWVyHP5f9Hn2zaPJ+8uLabfIv8+eIvda0q7Xsne1+aFxGTJynyWoNCoikWm2kJ03oH4xczQE+kgTeWF/2nBXZzDZobebur9TUOAWWdOdY1Ar7Ec4s8x3DHB+s2SPFNIfVgI17VkhL8rzb9r323PJdsfqCp5e/9eUXZ2b+Y5iJ6Ntndz695+4te+RP1s5feRdOl5977cSUuXOn4Bhsgn8JoyZ6zAnEz19dt3vN02/avjgpj373bbDT0VC35ZpTqC97707rGe0fEhbm4++JXbWxcb4EEzyyhFiJNcIc0Se/JMLTbBhZYu69nnuD7+577lngeEbsLXn6+PRQUWQkfvTksRfP71hWfbB6wrTfVrz766GdeLqWzLp9/px9bz/3+luEnCYH2javnLU0NbdhxNiX/7n+qQC9XT67benC1fi0HPPKq2df27aJxbiC8e4E+J4RrCaWYGM40WjCg61uftrIqFB3N3dwOd2pO9UHBnrllQSa9SivRN97/fb6mkQkeSjMWtk3H87bA4gdO719wJ2Rsj5sf+Zl+U6zZv2WdfU49KeC1aPSJz5Yc+TcixWLNYb5oABQyOvH8aiK4vUH9oSHrY4Ov+/OI+l6r0UzzzEtwFhwFrRgQyMzov0Dgm2hVqtPgE0ya8MjAjSSRhpZotHAFCo4v8RmCQ3zBFfOU7ruNdLeSlD2M7iRHnxgrsg7e9ry2ScXzp6+tHd17fHaCeXyyv90PvfgkeMH9hzAO1et5ds+DMxqGDn65X+ue8pf7//4Fll2PP0eK6+WzNJkFMx41ln8g/sEBnoHW6ir1hriLVGJsi2ygt2DwfEP9u4TxldfzH/Pc6giY+XjTFXE3t6cXXJePrWtGaevmn/vtiEPnHrh/Kk75tzfvhXHv7r+QNHYvNUjd6x+Mt0lYs2Mnfz5OVuv6/pKx2Jl2NDAjCAvW0BQkM0AHU1goM5iwfklFupr0oE0rTrP3q21x5N6tlSnfmU+wGLxDFU3/FMsOUUyySNCYgvHr6jMzUgalpgRlzYylXxf3zhn7r7Tz778Glu9e1TOeXuGf1vA0BpoV1/hR6+eWbNQMeJjb7xxgq/mQWuMgdE7iHGL3DR6vZsPDQnWar369DHnlfRxp/5e/sNLXLyu61ucueVB8VTGeq/rnamZf779meefPSWfcF7am4tb1rTAfG4Qs9OXT5Eh163xdV3hOz75o+wMmy9y1xp8QA4Gd0+YfvrA2Oqj89F5+nlRT4uJskmo09tyCb2WUBxMOomve0FL+q0OhNb+2gvHX+uM2r+fXNiPVznk9MKbp17B9h6rWyAx9oaSHxqV0VdDPI2uGBqkq6cv9Q/A3t0vc7lgF1/q5+7rbdbnlZh9ejB33aKfuszVU5IpYr1rbPtMJsIXj57u7BAveHUOWbPK0chfOdnoeMON+eA7ePSpgRkhPvpAQtz76KWgYASdKbhLJj5dlzT++SUaz5tM1/kHG8oeCPy7Tf5Jk/j01uoRSmeQZPkN+aT8jLz5cTzx6wXfjs+a/O4tMCh/hF1+x4flpeSHSfID8kG8CZd9hic8kRi1JbKffEK+DP+O4/eQEmFa6uARpoFLi86PEJO/TgoI5E9KkGQ0urNdONin170C7/TmMtTp7Sh1Z/RQcgjLnbL8nfwezsah7zw2aF/76c/eOPHwY/J+Miy78wccBTNoNxw7tmXkR2+fubj7ftESpF/A2ti6dpAG1OpKPZHk7ePiYsZmfX6J2ZMFQc4vwer7O9c3BE/+vkGv1mCWBssJ8xu+3HfuuVdOs5cOrtWsWYwRPiPHvHj6zeN0N3+zB6iv5qvqAzL6ELPOaERmC1DHFk8LDBt6V093V01eiauPU5D7XnFAetNlbx8sl9tnz3uv/bkXXzwp76PF9GjnSWhzEs5kZnPqZTJsHdCuB9rFPNLc8IxIb6sJhds0gR42ZNVGRYOjxD9Fcg+iQX4jS4I8KYzH9LqVnl6dKrhvdqvylbbiu3k7tJPqrQWfju1+kxIZuRPrL2FytrT2iap1/zj72oXzH335+lNfvfqYfJW9Z3fm2f2HpakPv7JxQU7y4oZlm9dvaN2wfuquUf85sf1FH73/IfD1JsM8cDWbgeIVysqc3Ky5jfnv+Anhv//F883gvzcybx+fV/x3toc4L7+7x87H1ZISq7pU3t1j5+NqaaHTyt8ldeVvhnRNWfnz7Lny1yZW7ViJdv5kjMT0XPs768BB50ufibm4t/Pan5SpXerAAmU2I2U2HuscR7kHngXSh9fhiefcdONZcDM8+pEqnoXShRvy46v9Q8WzUNp5EzzTVTyL1HrF91zT1JlVPIukTQJPdjcephO+a2s4j+LFdQIz5wIUy2VNY1RZMy3xeXOOKLmQz58L3mESp0N7SFzvqXK2WLoiOPPtyZn2mMrZYmmH4Cy+Vw278dwuffW3eG6X7rkJnmIVzxIVT2gvSWlVPEuk3QJPai886jo0vUO6LPBE9cJjUvHcofIzpJfE8x0Sx6NRPK7EojX0XNdmEtd8JiQeiUf/G2HROnqsb5+Ecn+wWTdbeebt55h4c3K3ZOFvTo4Q+UecVqovqSvVM6SzymqNuedKdZtYqWYlTio0Q1SaHEeCAwcu6vpUweHdA4fmQ8dqNy56i2MId8Ig1fMWFS1a3TGxymLpsX41lbeoEaLMo0Keoao8p3FOuvEskI5fh2ckr083ngVSx3V4GD/pvGVGi1b3/A35CeItc4Qos+dGeOgfvGVGi1Z35Lo1KC4b3jJHiDIOPOndeJhWHfsp8/b2vGiZROkFI1RJMz07dk3mJTuUlvkjb5nJDonzJytc73GKVunJXnpfDPmfc73/LEo0Km8a6FUc8m7+dIbjYDZFD3Obul/ejWdoLkJ+ocifrqxSgrWzVUy+FsryNUtV25zObbNI2OZJp1XNS461UzJD06bUto+zXU3nPBYJ27yg2GaQg0cFx1kHDjpfs0S01OAeGmjmFlEkLOtVoYGQXuu43XgW3ABPOeemG8+Cm+HhlqXgWahZfEN+pnPLKhKWdTM801U8izQLBZ4BPfFwyyoSlnXiup6M64RbVpJiL0wn3LKOKX1+orNl8f22uZaYZZ1WLMuNW1aSk8S1lXx/sVuREvVAGT1IrJPWNP58d7Eyvl46bzbXWezNMGSj1QoG280wZL/Tsz9RMFxyYOi2nMieGNoEBifLieqJI8GBA+7ffaN6sF5N4QIX/cox2FQM8m4WcUDBwO19scPe2X7+yn1O9n6U5fP2lCbKjxT7Ae6W0pkPhYp79N1HGX3ePtJELScobdjHweFesIB03j6KRS3Po55r7Xy3fi7rckVb6DsFR4QTDs02LuvpirYWcgyRN8OQjSYrGIJuhiH7GY4hpCeGSw4MUI8yBYO1J4Y2gYHV4wOlHmE9cSQ4cMD9S29UD66t6Yq2ujiGYBUDaGsx11a5kH6hQ/oseoByH5f+IYcHy9/Wj+Rv67P8nco7XOyZAtfWVIFnV1dnl3i7n2trsKjD/l574itPI9rE0whWYqZSywAnf0ritVRwQB1eUnBYeuDgI63yRKOotaesG+UT0jlWS3SO72SXhQ6ztdqrn0kX5MNKhDq2v41kdINm7e2uXebCQj4FnFbiCVj4DjS2SL5bpo+vRk3Jh8uSD0Yc7VORKO8sS3s04hlISRf6vj7Me8ijOf5xr3EIdK7NlS50Papdilx51BkPb6T18PClWqM3/PN4UcuJvaEEZU7nJAXFKBYiL3VAqt3HN4rHEtHZ5HOlcyoayktT5iR3ZG0fmz/gwPgFZdKFgilVmf6DF48qbX9pyhzvujJWux1Qu/cE1b4ZFq2bhwdCjKw7/PO8EVnL9XQjbMomofJ7peUNM2sF3c7vbl0w4UBygXQhs6osz5cTPlA6x3tWuajtYu0fQJdFEPYzeyLPQKrV+ri5hVKj1mK0GP2DHdSTnMnzbwvsvTnw7B3HIamnDPYt1Y41pg9Lj+0X4J963FkUeHF10LgWgzUywl87Vguc1YBEvtCZkS+KQAMzAv2CDQZv76jA4LBgfw8D0pjCkMmEwqj3i/5s/yHxLrX5ZTbL7CEhZTs7ZSubYCK27PDswbUcHzE8e1if6L5uU1yrCiLys4cGRvc1TXapKHWWozYlOCI4MWnm3ODw4MTEmkZ5b7c8gV+YG8mvao+BxXqh8Ax3nWQ0Wiw+NEPnYVzmpmcC/DDpnYDTTpwJK/G00u69RDpLxyYemDS/bKh8xX2GoXDm9En6Wpetml9qqrwbpsnXsDQiPTNjwbqb0pMy8E3pCeugPeidLZ0/CYwjpSe9D6c1gH3In/Wmp9OyWB6oX4a3HiYYrtTLq08fq4tZkvz8Ar2WWVjIyCS7R3rCy6I1qvst9bKKHjzIiUu1Rcb0rPS4iHCfoS9u7cGKdmFlUMxkgzUiIkA7Wt95zokjxUJMYCHhqH+Gd0AYWEikxmQKDvbzzgAbCQ1Y5gccfZj07svQP3gwvtgLmEkeN7eLnnw5mUVloZNZfNiTQzsYRP+kGsUuqhvkdpVHjBLlLMyi+7ijgAwXpNEQIzV7uOopD4Di+DTGNznKLuJB8SXdi2sX75pRkZlkHRBh/6569YKVU6rNs4wsiBPyl5vxXLQDubD4pUakRSaGTGBLVx7heJjVyLt47qdpg2OGlO6QL8rX/NZ5VmInLG3/t1ja5K8Flgg2jrBNhLJ55GevrgVIPJuSfKVGvuNqdIYX22+VRXYyeBeUGCKp+6gS6t8dvVndjYzvoMqfMlmwc6Qf398/uvDbn+//+89O6b4N63ftalu/gyTIv8qv4RTsht1xinxK/vGtL79+840rbBkAxQMLqVIpMqJIUD/GSI9cTDpJJ2GDGfvxb8d5B6ZSDmVvpWl1uihsJ6kP6LOHbpuMl64lid+vy+4/pwHblK+x6XtQI38Ul+HHPpT3dcOagECzsaDEHOnni30LSxjqntEfAHNoCh5KeoZT0+o0vqH0vWtFeNP9c+PWzi/eOXXyiz+98v6Ko/LThDy/CifMrxmfeUv1kJGz9+3b1vjMsi+0rFal8lYpUGpCUWwFjkqByFOnQ5JPmKs2Okby9fH1KSjx9TVGRAQXlEREGj0KSowRf7MCh9X9uUNFNFKrR++NvRnL9Lv7N3bslz+Sf206NuXW98rwYrl0493/em3zHWX7Z0+Y/M2K81eobt2hYL3P4bvPXrLF7UpIxDHY2Lb9ztsWJefNGT7uOI9gIu+TZnCtWFBWRqhFYyDERUcR1VBPL6yxaEaVGCxu7u46i4XqzDTcsR8P49X5Iz7HIzD+QbcSt5W9U6jVYWnGhX2dSeT5IxfkO03GmBw5PVBejxuX0Feu5eCp7380aWrnLibJVSDJIOlWFMBWltnDC72XL5X6BCIQGkJab2+/whJvs9ZUUKK9ToROYVwlx/s/3FTNoWFKDFc6gMR/gQ3Q0H5fkfdWVcdxeU3p7kmp5L3OpyLA9/zs1cuyPGZ3vL39fpwUlEoO3Cvn+4r3wKQEzSmiBRtT9P2wFAtcalFIhtkRMZWSwhJqRuFomJORYeW9IxxESq9dpqc798sPa4LuXfXXGcBaA1hjONZA3jJnQt0jwIrYaJqZERboEWZkYTs8aGSUW/CoEqOXmxvb9qugxCuS6EaVkJ4CuD6mfajjEY4jACl7RmzBjnV2ZkiRUkRnTVPO2Bk//OpiSn2y4aVPu97aenGB7NW2c+PmKfcWF23G319r92oLAKOxj7/ly7c+xfp75Q9w/yN7Nz40clle7aEZImqa5mURNY3FcNbyqGkETSUfavZrjqEwZEeTM/rF9e/fr29kZLQR9fHQePig6H7alOTYxHAanxAfk2CzRfcxeukpjY7x9/KKYW9bJNjNp5MSxFui3GvwvWFQgO445lClqEgRed3xTIEFBsfU5sueF3l6sseCqTZKRmy9fdPImfO3+A6YuGDchrolU8Mqxw8usMTeunwsbj+p185/9vipw0u1+nP0wPTZobOiK0OjQ0PiphROqo2qiOgXHRTeJzxpsnyFJCyXi8jZzrMkoTMB713TeY6/neLFviQG/ziV+8eL8DDu1YfIXmSfJg/yb1Hy+RsvBM2F8ql8h/O+rAcxegfQvn2Dg8O9aWxcaFReSXhoYB/3AHc/i58ur8TPbEF5JZaeT6KVXe57vEcLA6QaHVjDWqGjB/e1p9odod95+yCF+7dLu/618d7t+AODy5zPL3z/87mLi138Wq78+dPWgrfnbXho/lOvx37yxsdvnb1wW3jFP3E8NmI9Tmy/++reK1fydvXt9/DdUDteCzZDo5uUOb2Wz2TTHbMGfp2tPorro009Z+zK9Yviug6PwZOU2XRyjxLdFMjA+fx6gvrVCuQ2wmhHkceTSNJgithnKWw0SezPbOXkfhYWh2mCbVawgc036TZlvinfrsyBNI450OOgmMFOJeZ19e050+p6FUrUdpfABfIzveZRQIWksbegBI6Z8lti5c7fsb7S9S8os5nNxgSWIvnBXnR+hhIF7F04UWKU/IpSQuco8QWUGK9LVUuMlsWMTnJwMgl8nD3MIlkJpMXjEpkUYAqNl+oS1fvGIP9es8mT8lL6IHsiw+/ToXgyiFly16dAMUhrRFGQv5/fO6Hzy67tSHeIbWQ0LEWsCjNtEC2793+Txv+Xe3qf3ws4JDZnVnCQGXSTUku9o5ZH5N14qSodtta4RPl2nu5HrcgHTQI+CDKjBD4fdkN/gIWxb95DxL9xaD7qwovxFTKTtJBHyQXqTTPprbSJbqOP0R+lAGmK9LR0VZOneVBzQpus3aR9VXtVF6jL0VXqntFn6pfpPzD4GyYaWg1HjHpjunGWcYvxuPGSS4jLJJedLhdMcaaFpjdMP7iGuxa4trm+4Pq5m8VtgNsYtwa3zW4Pu73mHu6+0P2wWWeeYN7iEe0xw2OLx9sWsyXKstTytOUvzzGeD3q+6xXtVeP1kNcb3r7ew72XeR/w/s5H61Pgswr+PQku0njfB3x/9Qvw2+p3yn+4/xb/M/7f+v8asDRga8DpgAuBnoEVgSsCjwX+2MezT26fWX0e6vNhn5+D9EHBQYuD1gXdH/Ro0AtBZ4M+DI4NTg0eEVwSPDP49uD1wbuCHws+FuIf0jdkUEhhyLSQupBlIY9a/a19rYOshdZSa731YKgf9KppoSNCi0NrQxeFacI8wkLDEsMyw4rCKsKawlaFbQ/rsrnbQmwJtmG2MbYyW3u4CTreuPDB4aPCp4UfDj8R/m745+G/R+giPCNCIvpHZESMiyiPaIhoidgSsTfiiYhXIt6MHBhZEDklclbk7ZHrI3dF7o98MvLVyPciv4z8I8oQ5R/VN2pQ1PCoW6JqohZGnYwOi7ZH50RPjJ4RPT/6rujN0buiH4t+Jfrt6I+iv49BMaYY35jCmGkxdTELY1bFbItpjzkU83zMH33j+w7tO7rv5L5Vfef1vbPv9r4fxUbFpsWOiC2JrY1dHLsu9vu42rhFca1xW+MeiDsUdzzunbhP436N18R7xAfF94sfGp8fPyG+Kr45/lA/qZ93v4h+A/rl9bulX3W/hf1a++3s90i/p/ud6Pduvy/6/ZGgTwhIiE0YlFCYMC2hLuGOhIcS/ug/P9EnMSoxLTE/sSSxMrE+8Z7EY4nvJXYlhSRNSFqcdDjpc7vGPtp+q73avsDeat9pf8T+gv0t+2X7N/afky3JAclhyX2Tk5LXJm9Ovi/5WPLJ5LMp2hS3lKSUgSlzUxamLE/5MuWHAYMHbBywZ8DhAS8PeGfApwN+TdWl3pb6Vpo2rTrtRLolvTC9Pf3t9PcHxg1MHnjrwNcHvj3IPGjqoH2Dvh0cMXja4KNDzENuHfL0kFND/jPkypBrQ12HhgytH7ps6OtD3x76/tArQ38ZNn5Y+7DPhv2WoctIzRiWcWfGhoxtGRczvsiMy1yTeSzz7SycNTgrJ6swa0LW1KzKrFlZTVmPZz2TdTzrl+zY7CnZW7M/yLHllOc8luueOym3NPdonndect7teSvzXh9uGz5l+G3D7xt+doTXiPwR40Y0j1gyYtWI/SMO5XvmB+YX5rfkH87/fmTyyCUjjxToC6oKHi44WhhRmFLYUri2cHPhQ4WPjNKOKh5VNmrOqGWjDo56d1TnaLfRwaMTRmeMLhpdObp59J2j7xvdMfrI6PdH/z7Ge0zQmIFjasfsGHN4zE9jJ49dNLZ1XPi4uHHJ424Zt3jc6nG7x50r8iu6s+iP8XXj35kQN2H3hF8mXJtYMHH6xIUTN0785yTjpKGTKiZtn/TopLdvSbpl+i2Xi+OKhxaPLV5SvKZ4a/GzxZdLSEloSUZJc8lTJdcmj5pcN/nclKib/5s6d+obUz+c+tm0WdP+WepS2qc0vjS9NLf0ltJ1pcdLz5VevjXj1u23flyWWDawLLussGxR2b1l+8ueKTtV9kH56PJ7y7+dPmj6fdPlimEVyyrerYyrbKv8paq26uMZ66r7V6dVZ1TfX/1bTUJNdc3CmrW1uNa1dlntzzPvv23QbWdnDZy1ZNYLs+ls++yy2XfOPlC3sT6wfnn9xTmJc1bMeW9u9tzFcw83aBsmNuxruNqoa/RsHNu4rvF0k7mpuOlA0+fNwc3Tmvc0fzgvaF7jvFfmZ81/ev6J+WcXkAXJCwYvqF/wyIIfF/ZfOHfhowuvLOq/qHHRC4tdFmcvXrT4y9sLbj+2JH5JypK8JbcsObDk2h05d6y+442l3ksnLt229P1lwcvqlz237MSyd5d9vuyP5YblHsv9l/ddPnD52uXvL7+yvHOF+4rQFfYVOSs2rnh/xTcrrra4tgS39G/JbMlvGddS0dLUcmfL9pZ9LUdaXm/5oOXbFnmleWXoysKV81euWXnfyn+uPLTy+ZWvrzKvmrRqy6qdq367s/LOk6ujVi9f/c5dsXctvOvlNZ5rZqzpWPN9a3xra+vdrbta97c+2fpS67nWT1t/W6tfG7A2bu3QtWPXTl/btHbN2vvXPrb22Npzaz9b+/s6w7rAdfHrstZNWjd9Xd26pes2rntg3cF1x9edX/fFuj/Xu6wPWp+wPnP9pPUz1y9ev279Pev3ru9Y/+z60+s/Wv/DBrLBa0PkhrQNIzdM3VC3oWXD9g37NhzZcHLDhQ1fb7jaZmrr0xbdltyW0VbYNq2trm1pW1vb7raOtmNtb7ddbvu5rWujy0bfjbaNyRtzN07aOGNjw8b2jZc2BW2auemlTX/dnXT34rtP3/3rZr/NgzbnbG7Z/Mjm77aM3rJ5y0dbPt9q2OqxtW7roq2/bSPb3LbN3bZ826Zte7Yd2vbqtn9v+2rbX9tN24O299s+bPvI7VO2V29fuH359s3b79v+yPYntj+3/c3t7yvjLsL+H4QMeWLJre6Df0UGtjUiQidTEtcyeD72jfNXd3SeN+zRs7V0g7JzP0yUTqNjHHrBeM8OG7qHpqBVmlhklzajFu02VKN5D83FJ9AqUoqK4BgizUST4FoN/g0NI5vRBBKK7iE/IC/ImwHHs3BUwlEKRywcq+BoFuc1cMzk5UPRMHHO3vOoofUoUJeIFmrYznQJ6KTGhJZozqOTUiMcoXB+Ds6/QCfJfqBn6yqTvoL8KHRSl45OavVwDEJLpLMC/gTXKtFMaTaywH1HJZiu62pQoLQL6aXFUNdNUI89qB149gVolyahRLqt65q0C68BeqXSF6iDnkGNABulJagRZmdB0nQUBTQ7YN65h2i7Nkl2nu7QzUUdLF86z8t3sHtoDtx/Fup5DoXAtb0S+5YpHflKiYBDD9Pe51ER1YMca/B3AEew+jtkD+nn4WCyWQxHCCsD9V8MvKVo96FKch7l0b9QEb8HZM/yJNT1F52NlvK8V1EiHKG8Lr+gDs0QNJfJG59FEZBfSBHKgvvHaoegAjj6wREAsrdzud/g0F7rkpkuuB6cDtDDYK6L/V1dLK15FSU49ND7YDbAINOF88F1cRnw/QVyY3K/waH9EJVyXSzpeYAO3gf5PwzwcTiuSCfQXFUPvQ9mZwwyXTgfoAuuM4CsrozedRDqzujfFDIbBZ2z+nN7YfJZ8veQ2TOzqZtCsHVWH4AIoBfI+ROo50aQdV9e9/0oGOAbXAdalCJgB7TnCZIJbBTaCLdT1k7AVqUIOKC9QJkHBSwRUMlvAZiAgohv1zWmR0a7N9Q0wvTRkcf0CjLtDXXT0RLdGtAFtEHWDgQsE3AFa5esbdwUQpvl7aYX5PYCOvtfIWvvvM0xG2N6Fu2etb3ekLQBn7vQcY2XonNm88zuHHVSeXsWm/hxCMcDjGB9AbSzerwZRcC5HnB8AeesHwlk9sPanfRT1zvaJV3v0Hu6PtUu7/pUswrOAZInu75y9HVSDByboW9gtgE8Md0z2kynXH/noP8S/RzIYwRvS8zuoL+T1qCRTEasftrpwCP0cdpSNFubhcaydinaWCM9jSbz/utJtE+6CLYPeRqme39klPaiuew6/ZC3kQ7pWd7n7eM2k9f1E+tHoI9EUGYuk4fGjvwBttMPlD6SngT6gFPzPJyz73G1aK82geMwcFpMhheVPFZHzUUoD3XWBIF8QbZKPwL9Cdisrh7quluU+Qzq9wcKZHXl7RHk4pCXZj5yY7i0x0C37Wik1h+dYge7R7NHkZdDjg5Z8X6MyQpwOmSlYeXb0H59Fjqpz4H0cmTRngMYAYcRDdHPAOgLYwTrj07AWLUe+vbZII/NKIa348tIArnlaCzQPiy8LXWADs2aS+Kc1f0PPi7wMYU+C/ex/uYIyBLGAw2Bax+inboQGEvi4Z42FKON4TrooD+gdG07pOeCfS3huvFitCG/kY8vylh0hbVz7SFk1vnysciL88BsntFNhXYH9zjaQ2+otg+EivTioCO7ruGpyJ8fMMYzSE4rBx0CupdgPPJCiykBneahewy7UCXzD2gE+Aj10J7q0Vh6GcYco7yQRqHZIKvpcORoA9EO6Qgapr0HrQcdztdDv6NNQRHMVsHGZsBYNBwOq4AzQa/Mn4jSfMV1V8j78UoUodmL5lMLMgm8NeqxH9WD3IL4sQt9wPs/BOMgwnPhWA7jXxQcXnAMgiMGjnQ47HDYJLZoxvwgAcEeuYvEVtPYm37QlhFmb0eeRuoflJvADkaHyQjGMaO0CtrhEWRh9sd8A8c4oi2SLwLOZmpH9+iaURA7oI5LoF00aiNgPCxiu8lcG49QpytAf4CAunMQt3Fm18zWwJ70vjCWrod+Ywby1biBHieDbC4DndlApwUZdaVQPhTOTyAvjQ2ZdKfg/ATYwT40DOzUn/cNrH0OUfo2sD8ksTjCAhoOoCV6oAU0lmiyoD+XYQyfD/Ai1IHRhTZLv+DteabUDHztQn9Af3cRjku6r1CMrp3bL2s/zOYTQBdefIz/gttiAmU+mU0Zk8DmCfAhsfbL29BIyJ/KbfiklMNhgmSDerQgs3QFxWj+4O2/Q7tcqbN+DdTtijIWaUK6x2Ro42bezphts3o54C/g/01CUdr3oH3Cdc0atB/qsZO1fxg3GTwpPQr4DqMhmjxkd0DWP7F+hrV14IX5lh2adsC3GfCwPvg7OP8NeErn7Xc/b8+DFB7Zvb19Csf4ovoC74F/vBDNcEAHLYdcwK5H0q+gvYIdMBtQoWPsHdITsn6L9R2snxP9QA/o4NHRN7D+jvc5Qj9QTi8VwXi0C2m5z/oFamH9PPjnLbpYgIPQaO1MNFaD0GjwGcbqLoDNn0FmvT/Y/GjAZxL6eBTkEw96gHbJxhfNDj4Gm2/qaykw/m+u/y38H3ywaQBH3uy68KUWC3j/zXwdActvet3Rvv8G9vZdHP3B38Eevo0zBF0hdPUz6EfmAtyhwGs1cCx0OmrwXpQIhz87aDxKBl+ikfyC4qFvj+HztTYYH24wf4O+Jkg7GU1VfbQvYCzrdRCwfzg0cDxOBnW9qqS7/gXHz3B8AeeT4HhGgv6KfAb+EJRHGnGcRPeiI8qcVr++cx/rla7ukBcb9ih7pnb/0QcRlZ7EG9mKt2YH+CUIhygQxscZ+Ec9IS5aLdVIhEgfI/J9BrKyb5nZm7woMXN8NoKMrk5tiOyFVurXk2Irwv/gs+ERGvZEzsr2Zweayrq1CUb62wG+gpYhLcpDI1A+ugOdQmdwFE7C5bgCL8V34wdwB/4Bd5FAkkqeJa+Sk+RD8jPFlFIDdac2uoa20nX0HnovfYA+TPfTN+nb9F3JVRojjZWmSrdKd0pt0ibpBelF6U3pjMYzGAcPC14V/Grw68E/Bv8c/GfIKKuL1dsabA2zRlr7W+3WgdbB1hzrHOtC61LrXus/rQdCNaGeoT6h1tCw0MjQfqGlYSRMG+YeZgkLCAsOiw0bEVYWVhXx+lVJ7hLvxlmhHiPRbvQGegtHYzuejiuhHrvxY1CPv4gfr8crUI9/Qz2QWo+VvB4boB476INQj3/Rs1APJLlBPcZJ06Qy6S5po3Q31OOYdEZ6KxgFDw1eFrw7+LXgU8E/Bf8C9UBWT6uv1crrkWRNF/Vogno8CPX4V696TBH18HCqRyXUA0E9rnV1dV2mo7oud71ETnW9hBDA+K6XujrQC+gQKu9iz1VR1zZ5lbxSntdV01XRNb1rGqruyul8C/l2nul8Exk63+w83fVn52l5hbwUxt0dckDnvQjJJtmls0Y2yJ/D8W/Z8Gnxp36XF326BKFPl1x2+3TxZddPsi59f+m7S99e+vrSpUsfX/rg0vuXzl06dem1S/demnepCaFLfpdcLhk+qftY/vjqx798/NrHER+HfRzwsf/Hlo/dP6YXv7j41sXT/6kFex1Dxvey7qOo99/z1+X8t797BDzwtyX+7m/e/1iuSAH0PvQN3Ym+pfej7+ku9CP9B/qZ7sb5eCQOh1lTDd6J78e78D/wbrqXPoQfxHvxQ7gd/5PUktvwb/h3uo/mQdtoJn3oepJMUsg0UpoxfMrkkuKJE8YXjRs7ZvSowoKR+SOG5+XmZGdlZgwbOmTwoIHpaakDUhL7J/SLj4uOiowIt4WFhvh5eZjd3VxdjAa9TquRKMEoztqBy3I7aITVI6/clmsrHxEfZ831q8mJj8u15ZV1WMutHQCkSNuIETzLVt5hLbN2RAIod8ou68iAkjN6lcxQSmaoJbHZOhgNZiRs1o7TOTbrETx5XDGk1+fYSqwd3/L0KJ6WIvmJK5yEhsIdnCvGrTW3I29eTWtuGfCID7oYs23ZVcb4OHTQ6AJJF0h1RNvmHMTRQzFPkOjcgQcJ0rsyslDT3PLKjrHjinNzAkNDS+Lj8jvcbDn8EsrmKDu02R06jtJay1hHa60H415sXXfEjKaXxZoqbZXlU4s7aDnc20pzW1tXd3jEdsTYcjpiFn3qBzWv6oiz5eR2xDKsBUUqnYJukrhDE2G2WVt/RVAd27dXeuaUixxthPlXxJIdJLsDFxWHsr/APJB1a2uezZrXWtZafqRr2XSb1WxrPWgytc7JBXGjscWA4kjX0bWBHXnrSjrMZTV4YImoel5RQYfnuCnFHSQiz1pTDjnwf5gtNC0w1EMtM/Zml8FFzQbhgIRDQ5kY1h7JQNPhpGPZuGLl3IqmBx5CGQmxJR2kjF150XHFeyK7ssxxRb29zAa6LRhf3NohReRX2nJB4mvLO5ZNB+uayRRjM3e4/RYYamu1eFjTE0p4WStwlV9Za+3QRIKQ4C7nG8Bu2C2tZn7i9psCvg0EApEeFmu6DdAwPLm23DLxf16NHyCwgqBHxCqGMKG4IyMHEhnlQmO5B/snwB3lZaCw2hyuzI4E25wOL1uWql3GVm7t+GJ+i7itwyu7A5VViLs6EnJ5u7LmtpblKCwwXLZxxU8je9fHB5OtgY/bUTIqyWGFfbLByiJzW4srZ3SElAVWQrubYS0ODO3IKAENl9iKq0qY2YGEYj4O5MZRwm1lQnHBeFvBuMnFaYIR5QJDJ0Xk9kJjKw5U0IABdugj9NZiEkhLoKAZMqx5kLBlDYbfDl2EHg4zCJznMsPNGmwtxoHIURrY6Iix5lbliHLsvAdSDTOn7BEObFp2CniyRwSGloQqf/FxBC5bBWG4Q8+EOsJxCbopuKAH+8wewbOYLP2Y0VuLbVW2EluNtSNjbDGrGxMPl7IQBpe50NWEHmdOwgIxoVC47DhhwuzIiw10Fm7HcH6uno7odTnfcdnaqrcVjG9lyG0CIUyAIvI7EDPhjDSPQN4XsAZtg77XaoYmzRt068GMDNaYawYyJLb8ylbb+OLBvDT0J0sCFzFaFlSACyZkxcdB15Z10IbvGncwA981fnLx0+D2W++aUHyIYJJdllVyMByuFT9tRSiD5xKWyzLZiZWdMExFcKLn5QOfzkBoGb8q8Qx+XnEEI56nd+RhVHGEKHlmhVAkJ5SBCFyRlCsZjtIS5OmVvGU8j/8dRExkGUZNhj7DkGEiriTwIGZZhyDnKIz/BoweN2FXHHgQ7iri2UfwsoOGjEClxDIokaFweNfEbtITJxc/bkJwG/8FQlnsD8zFrwaUDcNKrrWSGcrtJTWtZSWssSEfUA38B3fVNhTUZBsKjGhNHUZbVVaHiy2L5Q9j+cOUfC3L14GJYh8Mty8D3Y/twMwCphSHQpO0BpwMbDV/yzRVAp1Kq/mz+Ix7DaEjrBOtOHhiyAg6MSQYu4Mz+1gwHVUQGVJYYA8pyIsIiUw2T4ywh0/09+wK0UldIVraFTIy3x6SD9c87ZaJGkwnSna4m2J3Oow+RunwPP+Qb/KwzR42sY89cKKP3XuiB3afaLa7T3R3H+NOQtzPuBN39y53ooXxfiK2o4n1aCl6DH2PJDPCy3ywBh/BGw9OGB8bW3BE1wVDhGHslA58V0fEePabMW5yh/auDjRx8pTigxhvKFm1fj3KCiroSBpf3GENKinoqISEOeigD8oqaWyMjS1tbGqOZX9NsY1Nsc5//NSvFHwirRfyRaP57pzNmic1p3s6TtII5IU2gY96hZ11/8pe7JfFt7q2petbeVXX1/LDkOMnP/s/emS9/pRngmgtWo4+BO+vDW2AmdZdaDl2R638iivaw2Y7kgHSDcyV42kWkbBBpAlyQytFmkLXfbdISygMZkZKWoP80JcirUVW7MCjQ3uxVaT1KBq/LNIuKA1fEWkTmkrCRdoV0stE2g3m4v9BOagWVcPRBMciVIUqYfZSicrhvBxSFagezUELgVdWqgZyrehhOJJQf5QIR7xIJaI4yB0Opeuh3CzAY0XZkG6Au9lvOcdfj+pQPzQG8qogZUXjIb8OZtZFcF6NmuG+ciibCGUY7kHwmwllsiHluMdxR3yve67Hae1VYhKcNUC+woVVpfL/grmW/zL5NPG6VUKJ2bz0bZBXj2ZcJwt2J5MsK7UQ4HSe28ApMWxNnDtF+rWcWgXPYVpQzmcCRw28bCX8VqjybIR6ZMJvBeeykt91M9kz/TVB7kCUAP/m83/94HrPuyvEvf14ajaU/H+9rwnqOofXqopLvhrKKlrox3HOBukU8tpU8Zoo9W92qkcTlGOSygQ85VBOOet5D7O83tpNAgr9b8p3N65+nOdquDqrB85GyCmEWXw2ykWjQfO53NIZTuR4zt81A/R8gz/K3kMkUIpCC9ZAa9VByzQgI7RJGEmg1bkjM/KA9u8JPZQ38oH+yw/5owAUiPqgIBSMQoD/UGj7NhSOIlAkikLRKAb1RbGYAhf9gDPW4pIQc/NS0ACUitJQOtRxEBqMhqChaBjKAHllAe85wHceWOIIvh5RADUaBbUZg8aicWDl49EENBEkdwsqRiVoMpqCpqJpqBTdispAPgfQg9ArrULPoW3Q89wJ/dpadD+0/b1YQq1Yg1rQZvQT+hmtR9uhvzuOPkI/ol1oP/oV/YJ+gz7vEXQSvYoeBUuvQBtBn6dAqq+h17EW67AeG9BXIL1z6C10FoaRavQD9NXvonfQedDKN+hbtAbsvRZaE7OQOrQbtDQXtMY03AwanQd6/BotgP5qIVqMlqDb0RH0AAxId6Bl0A9fQd+ho9iIXTAb9t2gJ+5EMjZjD2zBnqgLI+yFvWHMxdgX+2F/HIADcR8chINxCLbiUByG/kB/YhubWeNIHIWjcQzui2NxHI7H/XAC7o8TcRKMge/hZJyCB+BUnIbT8UA8CA/GQ/BQPAxn4EychT5Bl3E2zsG5OA8PxyP4bL0AF+JReDQeg8ficagDHcRFeDyegCfiSfgWXIxL8GQ8BV1F19Cn6DM8FU/DpfhWXIbL8XRcgStxFZ6Bq3ENrsUz2bZ+eDauw/V4Dp6LnsENuBE34Wb0OfoCz0PteD5egBfiRXgxvh0vwXegf6NL6H30AfoPjFQfowvoIl6Kl+HleAVuwSvxKnwnXo3vwmtwK16L1+H1eANuwxvxJnw33oy34K14G96O78H34h34vu71BvwA3tO93oD34YfxfvwvfAA/gh/Fj4HLcxAfwo/jw/gJ/CR+Ct0HfsLT+Ch+Bj+Ln8PP4xfwi/gYfgkfxy/jE/gV/Cp+DZ/Er+NT+A18Gr+Jz+C38Fn8Nj6H38Hn8bv4PfxvfAG/jz/A/8Ef4o/wRfwxvoQ/wZfxp/gz/Dn+An+Jv8Jf42/wFfwt/g5/j3/AP+Kf8M/4F/wrWwnBf+A/8V/4Kr6GO7GMu6ChYvBnKTiqGvBudOCcGoiRuBDmSboRd3BMPYiFeBIv4k18iC/xI/4kgASSPiSIBJMQcH9DSRixkXDCdiyNItEkhvQlsegQepzEkXj0JHoKvUz6ocPoCXQCrUAvodXoX+gVkkD6o+fRCyQRPUuS0O/EztdlBpBUkobWoX+QdDKQDCKDyRDwKvahHeBVfI8eAt9gJ/oneBlb0Fb0NBlKhoF7nEmySDbJIbkkjwwnI0g+GUkKSCEZRUaTMWQsGUeKyHgygUwkk8gtpJiUkMlkCpnK1oDIraSMlJPppIJUkioyg1STGlJLZpLbyCwym9SRejKHzCUNpJE0kWYyj8wnC8hCsogsJreTJeQOshRc8eVkBWkhK8kqcidZTe4ia0grWUvWkfVkA2kjG8kmcjfZTLaQrWQb2U7uIfeSHeQ+spPcT3aRf5Dd5AGyhzxI9pKHSDv5J9lHHib7yb/IAfIIeZQ8RjrIQXKIPE4OkyfIk+QpcoQ8TY6SZ8iz5DnyPHmBvEiOkZfIcfIyOUFeIa+S18hJ8jo5Rd4gp8mb5Ax5i5wlb5Nz5B1ynrxL3iP/JhfI++QD8h/yIfmIXCQfk0vkE3KZfEo+I5+TL8iX5CvyNfmGXCHfku/I9+QH8iP5ifxMfiG/kt/I7+QP8if5i1wl10gnkUkXRRRTQimVqIZqqY7qqYEaqQs1UVfqRt2pmXpQC/WkXtSb+lBf6kf9aQANpH1oEA2mIdRKQ2kYtdFwGkEjaRSNpjG0L42lcTSe9qMJtD9NpEnUTpNpCh1AU2kaTacD6SA6mA6hQ8F9z6CZNItm0xyaS/PocDqC5tORtIAW0lF0NB1Dx9JxtIiOpxPoRDqJ3kKLaQmdTKfQqXQaLaW30jJaTqfTClpJq+gMWk1raC2dSW+js+hsWkfr6Rw6lzbQRtpEm+k8Op8uoAvpIrqY3k6X0DvoUrqMLqcraAtdSVfRO+lqehdfj19L19H1dANtoxvpJno33Uy30K10G93O1+l30PvoTno/3UX/AaP3GfQGOo3epLvpA3QPfRD9xdYwaTv9J93H1/P/RQ/QR6S65lmzNPn9ymc16Zrravv3z8wx1M+ramisqG+oMuZXN5TPq2IXjfnlFc1NPOmeX1HbUNE8e8asqgXs3DW/sraqoaqxtpFjyZxdXtFQX6crV6A2c3pD1bwqbTkHusz66vq6qtt05Qp0ye7G5VLRnZayK+ubpAr40eZUlDM0lQrIAZzlTbpcQaRKEMlViFRxYMyF+8orKqrqmoxValKXK0hXKVCbq2Cs4sBluBMj1U6MDGeMVMOPaXhF/ezZ5QoyU7XTicsIp3trnO4dMb28QaqBH2N+U+2sSi49bS1PuuQrnHOR1SppYz5nXynFky75Cq9KKSXtlq9WiZfMH8kAqZ3pMtKJjZndaVOBM+O39TipbqiqqptVXldZW6Et5BrWzuLAVOhcbpbTibZQkdssDqRCJp9ZTFGjlfvrlPtHO99f53z/aOX+OkXudeVz6hubGurn1FTR3LpqWlVXrRsjtFsvtDtG0W49B65japrrqssbmmfPKm9ucq13PtMWKTw0KDwUOfPQ4MxDkcJDgwLGK3c1cuAy3kmMjU5inOCMrckZ2wQFTZMikQlM603wo53IVa1t5kA3UdSqWdRqolKrZg40Extq66o1zezXdWKPGjY7n+kmCituFg3oFidu5zulS5zSC7vT2slKXRdxYJzc3VIWqUnNrPq66kZjJuNFKVauJnWZuQosr1KkNaZxVnljjZKu706bxjtLq9HpRNNUX1ff6OroM/iZMXPWnJpynjSU19U3Vc2qqi035c5prAVWeLY+t0m57ppfL1LM7E1jZtcyYfIM00SnG4xjZldVKwU9auGWHvQ0nJ6UVdVUrhleDqzxvs+enqwTNKXJcIkCTc2EGkjpGVFGT1NQPmdOOTSV2dMry8moZjK6mRTX6gQXZGwtLaqp14yvrZ5dTieUN+sER3RsTS3NhmNsY61CKjPdnO/EFe9XRWFHnrFcFYqpylkUVUIAhlqHKLyae96qVJLfL01nlaxmldRUVs1qKtcJXNIiVkV2sYlVUWLINLfx6s3i1VMYzcomdc1kQS00Rl5H2lBTr21kFUzUcECboJ6CPp0DdayAA0419UwBJmfZu/di01TvrL1mZ+3Vq9ozlM+orU3s3z/J7kilJKqpJDXVfTXZ1ZFK4cOT42wAO1NLpaqpNDWV7kgN6K+mVFoDHLQSk1MceYkqlkQVS6KKJUnFkqRiSVI5TkpWUyq+pAFqSsWcpGJOUjHbVcx2FbNdxWxXZWFXadhVGnaVhl2lYVdp2FUaySqNZJVGskojWaWRrNLolkuySiNZpZGs0kjulrN6xwD1jgHqHQPUOwaod6SqXKWqvKSqvKSqvKSqmFNVzKkq5lQVc6qKOU3FnKbWN02lkabSSFNppKk00lQaaSqNNJVGmkojXaWRrtJIV2mkqzTSVRrpKo10lUZ6dz26sThoQFpNJaop1Xb729VUsppKUVMD1FSqmkpTUyqNRJVGN88p3XVL097CHUftfAXcoow58zkw3OJo/ob5jpS2RCm4kAOOhzWfNNfmukrhi1ZOn+U6txmGBjZaNjRWVWpn19bxUbuqAnoUQ9WCCui2oJShrrF5TlVDbX2DgictPTlRO6eqkfVxuc0N9Tx3QGKSsEdICf0MSLRDf1LV2ATuWFNVpQGG16ra6pqmGlNTDbhJSrrRZUbtPEfa1Ai81IkT1l3mZfVPFDDJtWJhQ+2sWbUVfHzXw+g6q6qxcaaZOwnO3qNT2sMp3cA69yrToqqGekeFTDPqmxu6T4ARx4lLY+0CR9qVc6WecfbUm+pq69SbGKdJ/fv3FzBRwCQB7QImC5gi4AABUwVMEzBdwEwBs7gkcvPyOMzLyxYwhw8siXmcbv+krGxloMlJFDBJQE4nMdeeK2CeAhV+AfLyiXnJCr6kPLuhsryxtrx+QW250IFdwGTNmJr6hjpNPf+dyH+b2a9CScEIUHDQ3y7V1NffxlQ2vWpW/XyemyxKJfdX6CWniPMUcT5AnA9IFDBJQLuAyQKmCDhAwFQB0wRMFzBTwCwBswV00MsVME+BqYJ+qqCfKuinCvqpgn6qoJ8q6KcK+qmCfqqgnyropwr6qYJ+qqCfKuinCvppgn6aoJ8m6KcJ+mmCfpqgnybopwm6aYJumqCbJuimCbppgm6aoJsm6KYLOumCTrqgky7opAs66aKe6YJeuqCXLuilC3rpgl66oJcu6KULepminpminpmCfqagnynoZwr6mYJ+pqCfKehnCvqZgn6moJ8p6GcK+pmCfqagnyXoZwn6WYJ+lqCfJehnCfpZgn6WoJ8l6GcJ+lmCfpagL9pjcpagnyXoZ+W5cJjdX10sSM4WPGQLHrIFD9mCh2zBQ7bgIVvwkC14yBY8ZAsesgUP2YKHbMFDdq6gnddNO0fIQfQbyaLfSM4RPOQIHnIEDzmChxzBQ47gIUfwkCN4yBE85AgecgQPOUIOOUIPuYJ+rqCfK+jnCvq5gn6uoJ8r6OcK+rmCfq6gnyvo5wr6uYJ+rqCfK+jnCvqi/0zOE/TzBP08QT9P0M8T9PME/TxBP0/QzxP08wT9PEE/T9AX/XVynqCv9Ocw3PcXMFHAJAHtAiYLmCLgAAFTBUwTMF3ATAGzBMwWMEfAXAEF/URBP1HQTxT0Ezn9xNxMMU4o7RRgkoCO68kCpggoxhulnQJMEzBdwEwBswTMFjBHQDFOZYpxKkvQzxL0swT9LEE/S9DPEvSzBP0sQT9L0M8S9LME/SxBP0vQzxL0swT9LEE/W9DPFvSzBf1sQT9b0M8W9LMF/exUFwWmOdoapNNFXqZTnuAjW/CRLfjIFnxkCz5yBB85go8cwUeO4CNH8JEj+HCM+zlCDjlCDjlCDjlCDjmCfo6gnyPo5wj6OYJ+rqCfK+jnCvq5gn6uoJ8r6OcK+rmCfq6gnyvo5wr6uYJ+rqCfK+jnCvq5gn6eoJ8n6OcJenmCXp6glyfo5Ql6ecKfEuOCvX+eZ/cyDvekwVmc7pTHfWeWZ+nOY35Mr2J8MYrluXHHVMWknKpIXPmp437lonqr2eHbsuuzqmaA4+pwdsHbhLyJ2b1zCrP14FD3m1PXPFvHfNp+9Y1algGgaT4703MvGxI65uVyCA4uuw6+LbvO3VqW4B4tK8CcWYBkxgwuqsSk/voJFVWVQLZc3yQSmmq+tlLdc83aWK0uterZ4i5LmJ3XdJWlVWXhTizVapXlWZ1YkyX5Iw3qWpTEUibnFR11WqRO4FIcg6DouB0NJFsoPDtTeYNfj4zIFem6upC7eJtf+SN8t5wUhEkqSYXfQWQK/E6j8xCmK+jDynf1cBjhDjdxj3JehycjPKu8qQ5wmxEZNabQiqKLxoyyoiy+g46BU3WBsm7IA7H3fwJ4vhFJSIvYmy4YeLEgb+TH9jLjVzRIx/lk++Z4Ih/kz56yz6qtLkcdPX5B3+jJHr/1FbPQs8pvPct5yfmX2Qd63fm3ETSB3urx29g/8brfJPTvXr929JHzbxPD9qnzL5eP4f8MTLKDSfB2OvAKAxCPBbomARauIJIRGg9sQL+LMdgw1DBMYTjD8IDhHSMTox5jAFQtBzhUWBEzNEAxVgYvhvsMDxkeMzxFUqUHDG0dhj6GfoYJDBMZXoHn2D8wfGL4whjFmM5YDpoFBu1nYHZldgeqZmSeBoxnBrh+RgY25gKQODAGZJDEmYDxx8XcjDFz1AL2mzAQMgDjUgacbpoYOFHMywP7UgRJzObPBGQVfyaBVAD1odjGIAwUQV91AXJxM3MPisv4GFhAs1egFQGgMAavBvBk9AKHNsQGMzDNCkx1IkAzlBg0mOKArvZk8GeKBdM+zL1AczyZQDfg+jOZAEkfsJs9GRgAKCt74A==';
        return base64_decode($fontBase64);
    }

    /**
     * Retorna a imagem em bytes.
     * Obs: A chancela tem que ser jpeg.
     *
     * @return string|false
     */
    protected function getStreamImageChancela(): string|false
    {
        $imageBase64 = 'data:image/jpeg;base64,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';

        if ($this->parameterBag->has('supp_core.administrativo_backend.chancela_imagem')) {
            try {
                $imageBase64 = $this->parameterBag->get('supp_core.administrativo_backend.chancela_imagem');
            } catch (\Throwable $t) {
                // declarado no config/services.yaml, mas não no .env
                $this->logger->error($t->getMessage());
            }
        }
        $imageBase64 = preg_replace('/data:image[^,]+;base64,/i', '', $imageBase64);

        return base64_decode($imageBase64);
    }

    /**
     * @param string $texto
     * @param string $nome
     * @param string $cpf
     * @param mixed  $imageSignature
     * @param mixed  $pdfContent
     *
     * @return string
     *
     * @throws Exception
     */
    public function addSignatureObject(string $texto, string $nome, string $cpf, mixed $pdfContent): string
    {
        // retira acentos e abrevia o nome
        $nome = $this->formatarNome($nome);
        $cpf = 'CPF: '.$this->appExtension->formatCpfCnpj($cpf);

        // Imagem da chancela
        $imageSignature = $this->getStreamImageChancela();
        // Dimensões da imagem
        $image = imagecreatefromstring($imageSignature);
        $imageWidth = imagesx($image);
        $imageHeight = imagesx($image);

        // Faz o parser do PDF
        $documentPdf = $this->pdfParserService->parseContent($pdfContent);

        $byteRange = [];
        $beforeSignature = [];
        $objectNumber = $this->getMaxObjectNumber($pdfContent);
        ++$objectNumber;
        $objectNumberAnnot = $objectNumber.' 0';

        // Copiar anotações de assinaturas anteriores
        $previousAnnot = $this->getPreviousAnnot($documentPdf);
        if (!empty($previousAnnot)) {
            $beforeSignature[] = $previousAnnot;
        }

        // Criar anotação para assinatura
        $beforeSignature[] = $objectNumberAnnot.' obj';
        $beforeSignature[] = '<<';
        $beforeSignature[] = '/FT /Sig';
        $beforeSignature[] = '/Type /Annot';
        $beforeSignature[] = '/Subtype /Widget';
        $beforeSignature[] = '/F 132';
        $countSig = count($documentPdf->getObjectsByType('Sig')) + 1;
        $beforeSignature[] = '/T (Signature'.$countSig.')';
        $beforeSignature[] = '/V '.($objectNumber + 1).' 0 R';  // referência para o obj /Type /Sig
        $pagesKids = $documentPdf->getDictionary()['Page']['all'];
        ksort($pagesKids);
        $objectNumberPage = str_replace('_', ' ', array_key_last($pagesKids));  // pegar a última página para vincular à assinatura
        $beforeSignature[] = '/P '.$objectNumberPage.' R';      // referência para o obj /Type /Page onde está a assinatura
        $beforeSignature[] = '/Rect [0.0 0.0 595.0 842.0]';
        $beforeSignature[] = '/AP <<';
        $beforeSignature[] = '/N '.($objectNumber + 9).' 0 R'; // referência para obj após /Type /Sig  (aparência)
        $beforeSignature[] = '>>';
        $beforeSignature[] = '>>';
        $beforeSignature[] = 'endobj';

        // Criar objeto de assinatura
        ++$objectNumber;
        $beforeSignature[] = $objectNumber.' 0 obj';
        $beforeSignature[] = '<<';
        $beforeSignature[] = '/Type /Sig';
        $beforeSignature[] = '/Filter /Adobe.PPKLite';
        $beforeSignature[] = '/SubFilter /adbe.pkcs7.detached';
        $beforeSignature[] = '/Location (Brasil)';
        $beforeSignature[] = '/Reason (Assinador SUPP)';
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        $beforeSignature[] = '/M (D:'.str_replace(':', '\'', $date->format('YmdHisP')).'\')';
        $beforeSignature[] = '/Name ('.$nome.')';
        $beforeSignature[] = '/Contents <';
        $pdfOut = $pdfContent."\r\n".implode("\n", $beforeSignature);

        // Atenção!!
        // <  > devem ser incluídos no cálculo do ByteRange [offset length offset length]
        // <  > não devem ser incluídos no hash do conteúdo
        $byteRange[0] = 0;                      // offset posição inicial
        $byteRange[1] = strlen($pdfOut) - 1;    // length até início da assinatura
        $byteRange[2] = $byteRange[1] + self::SIGNATURE_MAX_LENGTH + 2;    // offset imediatamente após a assinatura

        $afterSignature = [];
        $afterSignature[] = str_pad('', self::SIGNATURE_MAX_LENGTH, '0').'>';
        $byteRangePattern = '/ByteRange [0 0000000000 0000000000 0000000000]';
        $afterSignature[] = $byteRangePattern;

        // caso não tenha assinatura (seja a primeira), inserir a propriedade DocMDP para proibir a edição do PDF
        if (1 == $countSig) {
            $afterSignature[] = '/Reference ['; // Na documentação é chamado de: array of signature reference dictionaries
            $afterSignature[] = '<< /Type /SigRef';
            $afterSignature[] = '/TransformMethod /DocMDP';
            $afterSignature[] = '/TransformParams <<';
            $afterSignature[] = '/Type /TransformParams';
            $afterSignature[] = '/P 3';         // 3 - As alterações permitidas: preenchimento de formulários, instanciação de modelos de páginas e assinatura; outras alterações invalidam a assinatura e criação de annotation
            $afterSignature[] = '/V /1.2';      // versão
            $afterSignature[] = '>>';
            $afterSignature[] = '>>';
            $afterSignature[] = ']';
        }
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';

        // Font true type
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        // $afterSignature[] = '<< /Length '.mb_strlen($this->getFontStream(), '8bit').' /Filter/FlateDecode /Length1 8184>>';
        // Length: número de bytes do ttf em flateDecode e Length1: o tamanho em bytes sem compactação do ttf
        $afterSignature[] = '<< /Length 59356 /Filter /FlateDecode /Length1 96932 >>';
        $afterSignature[] = "stream\r\n".$this->getStreamFontTTF()."\r\nendstream";
        $afterSignature[] = 'endobj';

        // Font Descriptor
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<</Type /FontDescriptor';
        $afterSignature[] = '/FontName /OpenSans-Regular';
        $afterSignature[] = '/Flags 32';
        $afterSignature[] = '/FontWeight 400.0';
        $afterSignature[] = '/ItalicAngle 0.0';
        $afterSignature[] = '/FontBBox[-549.8047 -270.9961 1204.1016 1047.8516]';
        $afterSignature[] = '/Ascent 1068.8477';
        $afterSignature[] = '/Descent -292.96875';
        $afterSignature[] = '/CapHeight 713.8672';
        $afterSignature[] = '/XHeight 535.15625';
        $afterSignature[] = '/StemV 228.0078';
        $afterSignature[] = '/FontFile2 '.($objectNumber - 1).' 0 R';
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';

        // Fonte
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<</Type/Font';
        $afterSignature[] = '/Subtype /TrueType';
        $afterSignature[] = '/BaseFont /OpenSans-Regular';
        $afterSignature[] = '/Encoding /WinAnsiEncoding';
        $afterSignature[] = '/FirstChar 32';
        $afterSignature[] = '/LastChar 255';
        $afterSignature[] = '/FontDescriptor '.($objectNumber - 1).' 0 R';
        $afterSignature[] = '/Widths [260 267 401 646 572 823 730 221 296 296';
        $afterSignature[] = '552 572 245 322 266 367 572 572 572 572';
        $afterSignature[] = '572 572 572 572 572 572 266 266 572 572';
        $afterSignature[] = '572 429 899 633 648 631 729 556 516 728';
        $afterSignature[] = '738 279 267 614 519 903 754 779 602 779';
        $afterSignature[] = '618 549 553 728 595 926 577 560 571 329';
        $afterSignature[] = '367 329 542 448 577 556 613 476 613 561';
        $afterSignature[] = '339 548 614 253 253 525 253 930 614 604';
        $afterSignature[] = '613 613 408 477 353 614 501 778 524 504';
        $afterSignature[] = '468 379 551 379 572 376 590 376 245 577';
        $afterSignature[] = '405 784 502 510 592 1202 549 304 923 376';
        $afterSignature[] = '571 376 376 170 170 350 350 376 500 1000';
        $afterSignature[] = '592 776 477 304 942 376 468 560 260 267';
        $afterSignature[] = '572 572 572 572 551 516 577 832 354 497';
        $afterSignature[] = '572 322 832 500 428 572 347 347 577 619';
        $afterSignature[] = '655 266 227 347 375 497 780 780 780 429';
        $afterSignature[] = '633 633 633 633 633 633 873 631 556 556';
        $afterSignature[] = '556 556 279 279 279 279 722 754 779 779';
        $afterSignature[] = '779 779 779 572 779 728 728 728 728 560';
        $afterSignature[] = '611 622 556 556 556 556 556 556 858 476';
        $afterSignature[] = '561 561 561 561 253 253 253 253 596 614';
        $afterSignature[] = '604 604 604 604 604 572 604 614 614 614';
        $afterSignature[] = '614 504 613 504]';
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';

        // Fonte alias F1.
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<< /F1 '.($objectNumber - 1).' 0 R>>';
        $afterSignature[] = 'endobj';

        // Imagem
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<< /Type /XObject';
        $afterSignature[] = '/Subtype /Image';
        $afterSignature[] = '/Width '.$imageWidth;
        $afterSignature[] = '/Height '.$imageHeight;
        $afterSignature[] = '/ColorSpace /DeviceRGB';
        $afterSignature[] = '/BitsPerComponent 8';
        $afterSignature[] = '/Length '.mb_strlen($imageSignature, '8bit');
        $afterSignature[] = '/Filter /DCTDecode';
        $afterSignature[] = '>>';
        $afterSignature[] = "stream\r\n".$imageSignature."\r\nendstream";
        $afterSignature[] = 'endobj';

        // objeto indireto para apontar para imagem e criar um alias Im1
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<<';
        $afterSignature[] = '/Im1 '.($objectNumber - 1).' 0 R';
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';

        // resources (imagem e fonte)
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<<';
        $afterSignature[] = '/XObject '.($objectNumber - 1).' 0 R';
        $afterSignature[] = '/Font '.($objectNumber - 3).' 0 R';
        // $afterSignature[] = '/ProcSet <<[/PDF /Text]>>';
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';

        // Criar aparência da assinatura (sem chancela)
        /*
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<<';
        $afterSignature[] = '/N '.($objectNumber + 1).' 0 R';   // N - aparência normal, aponta para o obj da aparência (em branco)
        $afterSignature[] = '>>';
        $afterSignature[] = 'endobj';
        */

        // calcular a posição da chancela
        $x = (0 == ($countSig - 1) ? 40 : (155 * fmod($countSig - 1, 3)) + 40);
        $y = (0 == ($countSig - 1) ? 40 : (40 * intdiv($countSig - 1, 3)) + 40);

        // stream do texto e imagem da chancela
        $stream[] = '/DeviceRGB CS';
        $stream[] = '0.0549 0.2196 0.502 SC';
        $stream[] = '/DeviceRGB cs';
        $stream[] = '1 1 1 sc';
        $stream[] = '1 w';
        $stream[] = $x.' '.$y.' 155 40 re';         // x y largura altura (bordas)
        $stream[] = 'B';
        $stream[] = 'q';                            // salvar estado dos gráficos
        $stream[] = '32 0 0 32 '.($x + 1).' '.($y + 5).' cm'; // scalaX 0 0 scalaY x y  (tamanho que irá aparecer a imagem da chancela)
        $stream[] = '/Im1 Do';                      // desenhar imagem da chancela, alias Im1
        $stream[] = 'Q';
        $stream[] = 'BT';                           // Início do objeto Text
        $stream[] = '0 0 0 sc';
        $stream[] = '/F1 8 Tf';                     // fonte e tamanho da fonte
        $stream[] = '1 0 0 1 '.($x + 35).' '.($y + 30).' Tm';
        $stream[] = '('.$texto.') Tj';
        $stream[] = 'ET';                           // Fim do objeto Text

        $stream[] = 'BT';                           // Início do objeto Text
        $stream[] = '0 0 0 sc';
        $stream[] = '/F1 6 Tf';                     // fonte e tamanho da fonte, alias F1
        $stream[] = '1 0 0 1 '.($x + 35).' '.($y + 20).' Tm';
        $stream[] = '(Por: '.$nome.') Tj';
        $stream[] = '1 0 0 1 '.($x + 35).' '.($y + 12).' Tm';
        $stream[] = '('.$cpf.') Tj';
        $stream[] = '1 0 0 1 '.($x + 35).' '.($y + 4).' Tm';
        $stream[] = '(Em: '.$date->format('d/m/Y H:i:sP').') Tj';
        $stream[] = 'ET';                           // Fim do objeto Text

        // Aparência da chancela. Usa o stream criado anteriormente
        $streamStr = implode("\n", $stream);
        ++$objectNumber;
        $afterSignature[] = $objectNumber.' 0 obj';
        $afterSignature[] = '<<';
        $afterSignature[] = '/Length '.strlen($streamStr);
        $afterSignature[] = '/Type /XObject';
        $afterSignature[] = '/Subtype /Form';
        $afterSignature[] = '/Resources '.($objectNumber - 1).' 0 R';
        $afterSignature[] = '/FormType 1';
        $afterSignature[] = '/BBox [0.0 0.0 595.0 842.0]';
        $afterSignature[] = '>>';
        $afterSignature[] = "stream\r\n".$streamStr."\r\nendstream";
        $afterSignature[] = 'endobj';

        // Copiar o objeto que representa a página vinculada à assinatura /Type /Page  (mesma numeração)
        // Como foi forçado para que a anotação fosse colocada na última página, usar a última página para vincular à assinatura
        $afterSignature[] = $this->getPageAndAddAnnotation($objectNumberPage, $objectNumberAnnot, $documentPdf);

        // copiar o /Type /Catalog
        $catalog = $documentPdf->getDictionary()['Catalog']['all'];
        $objectNumberCatalog = str_replace('_', ' ', array_key_last($catalog));
        $afterSignature[] = $this->getCatalogAndAddFields($objectNumberCatalog, $objectNumberAnnot, $documentPdf);
        $pdfOut .= implode("\n", $afterSignature)."\n";

        // Criar a tabela de referência cruzada xref
        $startXref = mb_strlen($pdfOut, '8bit');
        $pdfOut .= $this->createXrefTable($pdfOut, mb_strlen($pdfContent, '8bit'))."\n";

        // $startXref = strlen($pdfOut);
        // $pdfOut .= $this->createXrefTable($pdfOut, strlen($pdfContent))."\n";

        // Trailer do PDF
        $trailer[] = 'trailer';
        $trailer[] = '<<';
        // size: é o número de objetos do arquivo + 1, porém o /Type/Calalog e /Type/Page foram copiados
        $trailer[] = '/Size '.($this->getCountObjects($pdfOut) - 1);
        $trailer[] = $this->getInformationDictionary($pdfContent);
        $trailer[] = '/Root '.$objectNumberCatalog.' R';                // Faz referência ao /Type /Catalog

        // Calcular o identificador do arquivo
        if ($documentPdf->getTrailer()->has('Id')) {
            $id = $documentPdf->getTrailer()->getElements()['Id']->getContent()->getElements()[0]->getContent();
        } else {
            $id = strtoupper(md5($pdfOut));
        }

        $trailer[] = '/ID [<'.$id.'> <'.strtoupper(md5($pdfOut)).'>]';  // identificador do arquivo
        $trailer[] = '/Prev '.$this->getLastStartXref($pdfContent);            // offset do xref anterior
        $trailer[] = '>>';
        $trailer[] = 'startxref';
        $trailer[] = $startXref;
        $trailer[] = '%%EOF';
        $pdfOut .= implode("\n", $trailer)."\n";

        $byteRange[3] = mb_strlen($pdfOut, '8bit') - $byteRange[2]; // length até o fim do arquivo
        $byteRangeWithValues = sprintf('/ByteRange [%u %u %u %u]', $byteRange[0], $byteRange[1], $byteRange[2], $byteRange[3]);
        $byteRangeWithValues .= str_repeat(' ', strlen($byteRangePattern) - strlen($byteRangeWithValues));

        // Depois de calculado o byteRange, é retirada a assinatura fake, que neste ponto, contém somente zeros
        $pdfOut = str_replace(str_repeat('0', self::SIGNATURE_MAX_LENGTH), '', $pdfOut);

        // Retorna o PDF com os campos para comportar a assinatura
        // Para inserir a assinatura, basta substibuir o /Contents <> por /Contents <308006092A864886F70D010702A0803080020101310F300...>
        return str_replace($byteRangePattern, $byteRangeWithValues, $pdfOut);
    }

    /**
     * Recupera a quantidade de objetos referentes às assinaturas.
     *
     * @param $pdfContent
     *
     * @return int
     *
     * @throws Exception
     */
    public function getCountSignature($pdfContent): int
    {
        $documentPdf = $this->pdfParserService->parseContent($pdfContent);

        return count($documentPdf->getObjectsByType('Sig'));
    }

    /**
     * Recupera o último startxref encontrado no PDF.
     *
     * @param $pdfContent
     *
     * @return int
     */
    private function getLastStartXref($pdfContent): int
    {
        $startXref = 0;
        $resultado = preg_match_all(
            "/startxref((.|\s)+?)%%EOF/i",
            $pdfContent,
            $matches,
            PREG_SET_ORDER
        );

        if ($resultado > 0) {
            $matches = array_pop($matches);
            $startXref = intval(trim($matches[1]));
        } elseif (0 === $resultado) {
            throw new RuntimeException('Erro: xref não encontrado no PDF!');
        } elseif (false === $resultado) {
            throw new RuntimeException('Erro ao ler o xref do PDF!');
        }

        return $startXref;
    }

    /**
     * Retorna o maior object number.
     *
     * @param $pdfContent
     *
     * @return int
     */
    private function getMaxObjectNumber($pdfContent): int
    {
        // $objectArray = $documentPdf->getObjects();
        $pattern = '/([0-9]+\s[0-9]+\sobj)/i';
        $maxObjId = 0;

        if (preg_match_all($pattern, $pdfContent, $matches) > 0) {
            foreach ($matches[0] as $match) {
                $obj = explode(' ', $match);
                if (3 == count($obj)) {
                    if ($maxObjId < intval($obj[0])) {
                        $maxObjId = intval($obj[0]);
                    }
                }
            }
        }

        return $maxObjId;
    }

    /**
     * Recupera todos os objetos Annot do documento.
     *
     * @param Document $documentPdf
     *
     * @return string
     */
    private function getPreviousAnnot(Document $documentPdf): string
    {
        $annotArray = $documentPdf->getObjectsByType('Annot', 'Widget');
        $objectArray = $documentPdf->getObjects();
        $out = [];
        foreach ($annotArray as $key => $annot) {
            if ($annot->has('FT') && 'Sig' == $annot->get('FT')->getContent()) {
                $out[] = str_replace('_', ' ', $key).' obj';
                $out[] = '<<';
                $out[] = '/FT /Sig';
                $out[] = '/Type /Annot';
                $out[] = '/Subtype /Widget';
                if ($annot->has('F')) {
                    $out[] = '/F '.$annot->get('F')->getContent();
                }
                $out[] = '/T ('.$annot->get('T')->getContent().')';
                $out[] = '/V '.str_replace('_', ' ', array_search($annot->get('V'), $objectArray)).' R'; // aponta para o /Sig
                $out[] = '/P '.str_replace('_', ' ', array_search($annot->get('P'), $objectArray)).' R';  // aponta para a página vinculada à assinatura
                $rect = $annot->get('Rect')->getContent();
                $out[] = '/Rect ['.$rect[0]->getContent().' '.$rect[1]->getContent().' '.$rect[2]->getContent().' '.$rect[3]->getContent().']';
                $n = array_search($annot->get('AP')->get('N'), $objectArray);
                $out[] = '/AP <<'; // aponta para aparência da assinatura (selo)
                $out[] = '/N '.str_replace('_', ' ', $n).' R';
                $out[] = '>>';
                $out[] = '>>';
                $out[] = 'endobj';
            }
        }

        return implode("\n", $out);
    }

    /**
     * Adiciona anotações na página vinculada à assinatura.
     *
     * @param string   $objectNumberPage  ex: 25_0 ou 25 0
     * @param string   $objectNumberAnnot ex: 26_0 ou 26 0
     * @param Document $documentPdf
     *
     * @return string
     */
    private function getPageAndAddAnnotation(string $objectNumberPage, string $objectNumberAnnot, Document $documentPdf): string
    {
        $page = $documentPdf->getObjectById(str_replace(' ', '_', $objectNumberPage));
        $objectArray = $documentPdf->getObjects();
        $out = [];

        if (!empty($page)) {
            $out[] = $objectNumberPage.' obj';
            $out[] = '<<';
            $out[] = '/Type /Page';
            $out[] = '/Parent '.str_replace('_', ' ', array_search($page->get('Parent'), $objectArray)).' R';
            $contentsArray = $page->get('Contents')->getContent();
            if (is_array($contentsArray)) {
                foreach ($contentsArray as $content) {
                    $contents[] = str_replace('_', ' ', array_search($content, $objectArray)).' R';
                }
                $contentsStr = '['.implode(' ', $contents).']';
            } else {
                $contentsStr = str_replace('_', ' ', array_search($page->get('Contents'), $objectArray)).' R';
            }

            $out[] = '/Contents '.$contentsStr;
            $out[] = '/Resources '.str_replace('_', ' ', array_search($page->get('Resources'), $objectArray)).' R';
            $annotsArray = $page->get('Annots')->getContent();
            $annots = [];
            if (is_array($annotsArray)) {
                foreach ($annotsArray as $annot) {
                    $annots[] = str_replace('_', ' ', array_search($annot, $objectArray)).' R';
                }
            }
            // Adiciona a nova anotação junto com as anteriores
            $annots[] = $objectNumberAnnot.' R';
            $out[] = '/Annots ['.implode(' ', $annots).']';
            $mediaBox = $page->get('MediaBox')->getContent();
            $out[] = '/MediaBox ['.$mediaBox[0]->getContent().' '.$mediaBox[1]->getContent().' '.$mediaBox[2]->getContent().' '.$mediaBox[3]->getContent().']';
            $out[] = '>>';
            $out[] = 'endobj';
        }

        return implode("\n", $out);
    }

    /**
     * Adiciona referência da anotação ao catálogo do PDF.
     *
     * @param string   $objectNumberCatalog
     * @param string   $objectNumberAnnot
     * @param Document $documentPdf
     *
     * @return string
     */
    private function getCatalogAndAddFields(string $objectNumberCatalog, string $objectNumberAnnot, Document $documentPdf): string
    {
        $catalog = $documentPdf->getObjectById(str_replace(' ', '_', $objectNumberCatalog));
        $objectArray = $documentPdf->getObjects();
        $out = [];

        if (!empty($catalog)) {
            $out[] = $objectNumberCatalog.' obj';
            $out[] = '<<';
            $out[] = '/Type /Catalog';
            $out[] = '/Pages '.str_replace('_', ' ', array_search($catalog->get('Pages'), $objectArray)).' R';
            // $out[] = '/Lang (pt-BR)';
            $out[] = '/AcroForm <<';

            $acroForm = $catalog->get('AcroForm');
            if (!empty($acroForm) && !str_contains(get_class($acroForm), 'ElementMissing')) {
                $fieldsArray = $acroForm->get('Fields')->getContent();
                $fields = [];
                if (is_array($fieldsArray)) {
                    foreach ($fieldsArray as $field) {
                        $fields[] = str_replace('_', ' ', array_search($field, $objectArray)).' R';
                    }
                }
            }
            // Adiciona a nota anotação às anteriores
            $fields[] = $objectNumberAnnot.' R';
            $out[] = '/Fields ['.implode(' ', $fields).']';
            $out[] = '/SigFlags 3';
            $out[] = '>>';
            $out[] = '>>';
            $out[] = 'endobj';
        }

        return implode("\n", $out);
    }

    /**
     * Cria a tabela de referência cruada.
     * Obs: $pdfContent não é necessariamente o conteúdo original do PDF.
     *
     * @param $pdfContent
     * @param $offset
     *
     * @return string
     */
    private function createXrefTable($pdfContent, $offset): string
    {
        $resultado = preg_match_all("/([0-9]+)\s[0-9]+\sobj\s/i", $pdfContent, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE, $offset);
        $xrefs = [];
        $xrefTable[] = 'xref';
        $xrefTable[] = '0 1';
        $xrefTable[] = "0000000000 65535 f\r";

        if ($resultado > 0) {
            foreach ($matches as $match) {
                $xrefs[$match[1][0]] = $match[1][1];    // object number => offset
            }
            ksort($xrefs);
            $xrefTemp = [];

            for ($i = array_key_first($xrefs); $i <= array_key_last($xrefs) + 1; ++$i) {
                if (!empty($xrefs[$i])) {
                    $xrefTemp[$i] = sprintf("%010d %05d %s\r", $xrefs[$i], 0, 'n');
                } else {
                    if (count($xrefTemp) > 0) {
                        $xrefTable[] = array_key_first($xrefTemp).' '.count($xrefTemp);
                        $xrefTable = array_merge($xrefTable, $xrefTemp);
                    }
                    $xrefTemp = [];
                }
            }

            return implode("\n", $xrefTable);
        } elseif (0 === $resultado) {
            throw new RuntimeException('Erro ao montar a xref table: nenhum object number encontrado no PDF!');
        } elseif (false === $resultado) {
            throw new RuntimeException('Erro ao montar a xref table do PDF!');
        }

        return '';
    }

    /**
     * Retorna o object number do Document Information Dictionary.
     *
     * @param $pdfContent
     *
     * @return int
     */
    private function getInformationDictionary($pdfContent): string
    {
        $pattern = "/\/Info\s[0-9]+\s[0-9]+\sR/i";
        $resultado = preg_match_all($pattern, $pdfContent, $matches, PREG_SET_ORDER);
        $objectNumber = '';

        if ($resultado > 0) {
            $objectNumber = $matches[0][0];
        } elseif (0 === $resultado) {
            throw new RuntimeException('Erro: não encontrado o Information Dictionary no PDF!');
        } elseif (false === $resultado) {
            // throw new RuntimeException(preg_last_error_msg());
            throw new RuntimeException('Erro ao ler o Information Dictionary do PDF!');
        }

        return $objectNumber;
    }

    /**
     * Retorna a quantidade de objetos no arquivo.
     * Obs: $pdfContent não é necessariamente o conteúdo original do PDF.
     *
     * @param $pdfContent
     *
     * @return int
     */
    private function getCountObjects($pdfContent): int
    {
        $pattern = "/[0-9]+\s[0-9]+\sobj\s/";
        $resultado = preg_match_all($pattern, $pdfContent, $matches, PREG_SET_ORDER);
        $count = 0;
        if ($resultado > 0) {
            $count = count($matches);
        } elseif (0 === $resultado) {
            throw new RuntimeException('Erro: não encontrado objetos no PDF!');
        } elseif (false === $resultado) {
            throw new RuntimeException('Erro ao ler os objetos do PDF!');
        }

        return $count;
    }
}

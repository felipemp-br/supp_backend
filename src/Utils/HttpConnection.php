<?php

declare(strict_types=1);
/**
 * /src/Utils/HttpConnection.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

/**
 * Class Utils.
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @noinspection PhpUnused
 */
class HttpConnection
{
    private ?string $requestHost;
    private ?string $requestUrl;
    private ?array $requestHeaders;
    private ?string $requestUserAgent;
    private ?string $requestEncoding;
    private ?string $requestProxy;
    private ?int $requestMaxRedirect;
    private ?bool $requestFollowLocation;
    private ?string $requestUser;
    private ?string $requestPassword;

    private ?string $cookieFile;
    private ?bool $cookies;

    private ?int $responseHeaders;
    private ?string $responseResult;
    private $responseInfo;

    /**
     * HttpConnection constructor.
     */
    public function __construct()
    {
        $this->requestUrl = null;
        $this->requestHost = null;
        $this->requestHeaders = [];
        $this->requestUserAgent = null;
        $this->requestEncoding = null;
        $this->requestProxy = null;
        $this->requestUser = null;
        $this->requestPassword = null;
        $this->requestMaxRedirect = 5;
        $this->requestFollowLocation = false;
        $this->responseHeaders = 1;
        $this->cookies = false;
        $this->cookieFile = null;
    }

    public function __destruct()
    {
        $this->disableCookies();
    }

    /** @noinspection PhpUnused */

    /**
     * @return string|null
     */
    public function getRequestHost(): ?string
    {
        return $this->requestHost;
    }

    /** @noinspection PhpUnused */
    /**
     * @return string|null
     */
    public function getRequestUrl(): ?string
    {
        return $this->requestUrl;
    }

    /** @noinspection PhpUnused */

    /**
     * @return array|null
     */
    public function getRequestHeaders(): ?array
    {
        return $this->requestHeaders;
    }

    /** @noinspection PhpUnused */

    /**
     * @return string|null
     */
    public function getRequestUserAgent(): ?string
    {
        return $this->requestUserAgent;
    }

    /** @noinspection PhpUnused */

    /**
     * @return string|null
     */
    public function getRequestEncoding(): ?string
    {
        return $this->requestEncoding;
    }

    /** @noinspection PhpUnused */

    /**
     * @return string|null
     */
    public function getRequestProxy(): ?string
    {
        return $this->requestProxy;
    }

    /** @noinspection PhpUnused */

    /**
     * @return int|null
     */
    public function getRequestMaxRedirect(): ?int
    {
        return $this->requestMaxRedirect;
    }

    /** @noinspection PhpUnused */

    /**
     * @return bool|null
     */
    public function getRequestFollowLocation(): ?bool
    {
        return $this->requestFollowLocation;
    }

    /** @noinspection PhpUnused */
    /**
     * @return string|null
     */
    public function getRequestUser(): ?string
    {
        return $this->requestUser;
    }

    /** @noinspection PhpUnused */
    /**
     * @return string|null
     */
    public function getRequestPassword(): ?string
    {
        return $this->requestPassword;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestUrl
     *
     * @return $this
     */
    public function setRequestUrl($requestUrl): self
    {
        preg_match('/^\s*https?:\/\/(.*)/', $requestUrl, $matches);
        $this->requestUrl = $requestUrl;
        $this->requestHost = $matches[1];

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $headers
     *
     * @return $this
     */
    public function setRequestHeaders($headers): self
    {
        $this->requestHeaders = $headers;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $header
     *
     * @return $this
     */
    public function addRequestHeader($header): self
    {
        $this->requestHeaders = array_merge($this->requestHeaders, is_array($header) ? $header : [$header]);

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestUserAgent
     *
     * @return $this
     */
    public function setRequestUserAgent($requestUserAgent): self
    {
        $this->requestUserAgent = $requestUserAgent;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestEncoding
     *
     * @return $this
     */
    public function setRequestEncoding($requestEncoding): self
    {
        $this->requestEncoding = $requestEncoding;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestProxy
     *
     * @return $this
     */
    public function setRequestProxy($requestProxy): self
    {
        $this->requestProxy = $requestProxy;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $maxRedirect
     *
     * @return $this
     */
    public function setRequestMaxRedirect($maxRedirect): self
    {
        $this->requestMaxRedirect = $maxRedirect;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param bool $followLocation
     *
     * @return $this
     */
    public function setRequestFollowLocation(bool $followLocation): self
    {
        $this->requestFollowLocation = $followLocation;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestUser
     *
     * @return $this
     */
    public function setRequestUser($requestUser): self
    {
        $this->requestUser = $requestUser;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @param $requestPassword
     *
     * @return $this
     */
    public function setRequestPassword($requestPassword): self
    {
        $this->requestPassword = $requestPassword;

        return $this;
    }

    /** @noinspection PhpUnused */
    /**
     * @return null
     */
    public function getResponseInfo()
    {
        return $this->responseInfo;
    }

    /** @noinspection PhpUnused */
    /**
     * @return string|null
     */
    public function getResponseResult(): ?string
    {
        return $this->responseResult;
    }

    /** @noinspection PhpUnused */
    /**
     * @return array
     */
    public function getResponseHeaders(): array
    {
        $result = [];
        $headers = $this->getResponseHeadersAsString();
        $headers = str_replace("\r\n\r\n", '', $headers);
        $headers = explode("\r\n", $headers);

        if (count($headers)) {
            $result['Status'] = array_shift($headers);
            foreach ($headers as $line) {
                list($key, $value) = explode(': ', $line);
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /** @noinspection PhpUnused */
    protected function enableCookies(): void
    {
        $dir = '/tmp/cookies';

        if (file_exists($dir) || mkdir($dir)) {
            $this->cookieFile = realpath(tempnam($dir, 'cok'));
            $fhnd = fopen($this->cookieFile, 'w');
            if (!$fhnd) {
                $this->cookies = false;
            } else {
                fclose($fhnd);
            }
        } else {
            $this->cookies = false;
        }
    }

    /** @noinspection PhpUnused */
    protected function disableCookies(): void
    {
        $this->cookies = false;

        if ($this->cookieFile && file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }

    /** @noinspection PhpUnused */
    public function printCookies(): void
    {
        if (file_exists($this->cookieFile)) {
            echo "========== Cookies ==========\n";
            echo file_get_contents($this->cookieFile);
        } else {
            echo 'No cookies found';
        }
    }

    /** @noinspection PhpUnused */
    public function enableResponseHeaders(): void
    {
        $this->responseHeaders = 1;
    }

    /** @noinspection PhpUnused */
    public function disableResponseHeaders(): void
    {
        $this->responseHeaders = 0;
    }

    /** @noinspection PhpUnused */
    /**
     * @return false|string
     */
    public function getResponseHeadersAsString(): bool|string
    {
        return substr($this->getResponseResult(), 0, $this->responseInfo['header_size']);
    }

    /** @noinspection PhpUnused */
    /**
     * @return false|string
     */
    public function getResponseEncoding(): bool|string
    {
        $headers = $this->getResponseHeaders();

        if (isset($headers['Content-Type']) && preg_match('/charset=([^\s]+)/', $headers['Content-Type'], $matches)) {
            return $matches[1];
        }

        return false;
    }

    /** @noinspection PhpUnused */
    /**
     * @return false|string
     */
    public function getResponseBody(): bool|string
    {
        return substr($this->getResponseResult(), $this->responseInfo['header_size']);
    }

    /** @noinspection PhpUnused */
    /**
     * @return false|string
     */
    public function getResponseBodyAsUtf8(): bool|string
    {
        $encoding = strtolower($this->getResponseEncoding());
        $body = $this->getResponseBody();

        if ('iso-8859-1' == $encoding) {
            return mb_convert_encoding($body, 'UTF-8', 'ISO-8859-1');
        } else {
            return $body;
        }
    }

    /** @noinspection PhpUnused */
    /**
     * @return int
     */
    public function getResponseStatusCode(): int
    {
        $info = $this->getResponseInfo();

        if (is_array($info) && isset($info['http_code'])) {
            return $info['http_code'];
        } else {
            return 0;
        }
    }

    /**
     * @noinspection DuplicatedCode
     */
    /**
     * @param string $urlFragment
     * @param string $urlParams
     */
    public function get(string $urlFragment = '', string $urlParams = ''): void
    {
        // build url
        if (is_array($urlParams) && (count($urlParams) > 0)) {
            $urlParams = '?'.http_build_query($urlParams);
        } elseif (is_string($urlParams) && ('' != $urlParams)) {
            $urlParams = '?'.$urlParams;
        }

        $url = rtrim($this->requestUrl, '/');
        if ('' != $urlFragment) {
            $url = $url.'/'.$urlFragment;
        }
        if ('' != $urlParams) {
            $url .= $urlParams;
        }

        $hnd = curl_init($url);
        curl_setopt($hnd, CURLOPT_HTTPHEADER, $this->requestHeaders);
        curl_setopt($hnd, CURLOPT_HEADER, $this->responseHeaders);

        if ($this->requestUserAgent) {
            curl_setopt($hnd, CURLOPT_USERAGENT, $this->requestUserAgent);
        }

        if ($this->requestEncoding) {
            curl_setopt($hnd, CURLOPT_ENCODING, $this->requestEncoding);
        }

        curl_setopt($hnd, CURLOPT_TIMEOUT, 60);
        curl_setopt($hnd, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($hnd, CURLOPT_MAXREDIRS, $this->requestMaxRedirect);
        curl_setopt($hnd, CURLOPT_FOLLOWLOCATION, $this->requestFollowLocation);

        if ((null !== $this->requestUser) && (null != $this->requestPassword)) {
            curl_setopt($hnd, CURLOPT_USERPWD, $this->requestUser.':'.$this->requestPassword);
        }

        curl_setopt($hnd, CURLOPT_TIMEOUT, 60);
        curl_setopt($hnd, CURLOPT_RETURNTRANSFER, true);

        if ($this->requestProxy) {
            curl_setopt($hnd, CURLOPT_PROXY, $this->requestProxy);
        }

        if (true == $this->cookies) {
            curl_setopt($hnd, CURLOPT_COOKIEFILE, $this->cookieFile);
            curl_setopt($hnd, CURLOPT_COOKIEJAR, $this->cookieFile);
        }

        if (preg_match('/^\s*https:/', $url)) {
            curl_setopt($hnd, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($hnd, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $this->responseResult = curl_exec($hnd);
        $this->responseInfo = curl_getinfo($hnd);
        curl_close($hnd);
    }

    /**
     * @noinspection DuplicatedCode
     */
    /**
     * @param string $urlFragment
     * @param string $urlParams
     */
    public function post(string $urlFragment = '', string $urlParams = ''): void
    {
        $url = rtrim($this->requestUrl, '/');

        if ('' != $urlFragment) {
            $url = $url.'/'.$urlFragment;
        }

        $hnd = curl_init($url);
        curl_setopt($hnd, CURLOPT_HTTPHEADER, $this->requestHeaders);
        curl_setopt($hnd, CURLOPT_HEADER, $this->responseHeaders);

        if ($this->requestUserAgent) {
            curl_setopt($hnd, CURLOPT_USERAGENT, $this->requestUserAgent);
        }

        if ($this->requestEncoding) {
            curl_setopt($hnd, CURLOPT_ENCODING, $this->requestEncoding);
        }

        curl_setopt($hnd, CURLOPT_TIMEOUT, 60);
        curl_setopt($hnd, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($hnd, CURLOPT_MAXREDIRS, $this->requestMaxRedirect);
        curl_setopt($hnd, CURLOPT_FOLLOWLOCATION, $this->requestFollowLocation);

        if ((null !== $this->requestUser) && (null != $this->requestPassword)) {
            curl_setopt($hnd, CURLOPT_USERPWD, $this->requestUser.':'.$this->requestPassword);
        }

        curl_setopt($hnd, CURLOPT_TIMEOUT, 60);
        curl_setopt($hnd, CURLOPT_RETURNTRANSFER, true);

        if ($this->requestProxy) {
            curl_setopt($hnd, CURLOPT_PROXY, $this->requestProxy);
        }

        if (true == $this->cookies) {
            curl_setopt($hnd, CURLOPT_COOKIEFILE, $this->cookieFile);
            curl_setopt($hnd, CURLOPT_COOKIEJAR, $this->cookieFile);
        }

        if (is_array($urlParams) && (count($urlParams) > 0)) {
            curl_setopt($hnd, CURLOPT_POSTFIELDS, http_build_query($urlParams));
        } elseif (is_string($urlParams) && ('' != $urlParams)) {
            curl_setopt($hnd, CURLOPT_POSTFIELDS, $urlParams);
        }

        if (preg_match('/^\s*https:/', $url)) {
            curl_setopt($hnd, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($hnd, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $this->responseResult = curl_exec($hnd);
        $this->responseInfo = curl_getinfo($hnd);
        curl_close($hnd); // print_r($this);
    }
}

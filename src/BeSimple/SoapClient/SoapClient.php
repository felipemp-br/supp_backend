<?php

declare(strict_types=1);

/*
 * This file is part of the BeSimpleSoapClient.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SuppCore\AdministrativoBackend\BeSimple\SoapClient;

use AllowDynamicProperties;
use ErrorException;
use RuntimeException;
use SoapFault;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\Converter\MtomTypeConverter;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\Helper;

/**
 * Extended SoapClient that uses a a cURL wrapper for all underlying HTTP
 * requests in order to use proper authentication for all requests. This also
 * adds NTLM support. A custom WSDL downloader resolves remote xsd:includes and
 * allows caching of all remote referenced items.
 *
 * @author Andreas Schamberger <mail@andreass.net>
 *
 */
#[AllowDynamicProperties]
class SoapClient extends \SoapClient
{
    /**
     * Soap version.
     */
    protected int $soapVersion = SOAP_1_1;

    /**
     * Tracing enabled?
     */
    protected bool $tracingEnabled = false;

    /**
     * cURL instance.
     */
    protected ?Curl $curl = null;

    /**
     * Last request headers.
     */
    private string $lastRequestHeaders = '';

    /**
     * Last request.
     */
    private string $lastRequest = '';

    /**
     * Last response headers.
     */
    private string $lastResponseHeaders = '';

    /**
     * Last response.
     */
    private string $lastResponse = '';

    /**
     * Soap kernel.
     */
    protected ?SoapKernel $soapKernel = null;

    /**
     * Custom options.
     */
    protected ?array $custom_options;

    /**
     * Constructor.
     *
     * @param string $wsdl   WSDL file
     * @param array $options Options array
     *
     * @throws SoapFault
     * @throws ErrorException
     */
    public function __construct(string $wsdl, array $options = [])
    {
        // Customização AGU, 20/06/2023
        $this->custom_options = $options;

        // tracing enabled: store last request/response header and body
        if (isset($options['trace']) &&
            true === $options['trace']) {
            $this->tracingEnabled = true;
        }
        // store SOAP version
        if (isset($options['soap_version'])) {
            $this->soapVersion = $options['soap_version'];
        }

        $this->curl = new Curl($options);

        if (isset($options['extra_options'])) {
            unset($options['extra_options']);
        }

        $wsdlFile = $this->loadWsdl($wsdl, $options);
        if (isset($options['force_https_always'])) {
            unset($options['force_https_always']);
        }

        $this->soapKernel = new SoapKernel();
        // set up type converter and mime filter
        $this->configureMime($options);

        // we want the exceptions option to be set
        $options['exceptions'] = true;
        // disable obsolete trace option for native SoapClient as we need to do our own tracing anyways
        $options['trace'] = false;
        // disable WSDL caching as we handle WSDL caching for remote URLs ourself
        $options['cache_wsdl'] = WSDL_CACHE_NONE;

        parent::__construct($wsdlFile, $options);
    }

    /**
     * Perform HTTP request with cURL.
     *
     * @param SoapRequest $soapRequest SoapRequest object
     *
     * @return SoapResponse
     *
     * @throws SoapFault
     */
    private function __doHttpRequest(SoapRequest $soapRequest): SoapResponse
    {
        // HTTP headers
        $soapVersion = $soapRequest->getVersion();
        $soapAction = $soapRequest->getAction();
        if (SOAP_1_1 === $soapVersion) {
            $headers = [
                'Content-Type:'.$soapRequest->getContentType(),
                'SOAPAction: "'.$soapAction.'"'
            ];
        } else {
            $headers = [
                'Content-Type:'.$soapRequest->getContentType().'; action="'.$soapAction.'"',
            ];
        }

        // Customização AGU, 20/06/2023
        // opção de headers adicionais (integração TSE/TRE com proteção via API Token da 3scale)
        $custom_options = $this->custom_options;
        if (!empty($custom_options['headers'])) {
            $headers = array_merge($headers, $custom_options['headers']);
        }

        $location = $soapRequest->getLocation();
        $content = $soapRequest->getContent();

        $headers = $this->filterRequestHeaders($soapRequest, $headers);

        $options = $this->filterRequestOptions($soapRequest);

        // execute HTTP request with cURL
        $responseSuccessfull = $this->curl->exec(
            $location,
            $content,
            $headers,
            $options
        );

        // tracing enabled: store last request header and body
        if ($this->tracingEnabled) {
            $this->lastRequestHeaders = $this->curl->getRequestHeaders();
            $this->lastRequest = $soapRequest->getContent();
        }
        // in case of an error while making the http request throw a soapFault
        if (!$responseSuccessfull) {
            // get error message from curl
            $faultstring = $this->curl->getErrorMessage();
            throw new SoapFault('HTTP', $faultstring);
        }
        // tracing enabled: store last response header and body
        if ($this->tracingEnabled) {
            $this->lastResponseHeaders = $this->curl->getResponseHeaders();
            $this->lastResponse = $this->curl->getResponseBody();
        }

        // wrap response data in SoapResponse object
        return SoapResponse::create(
            $this->curl->getResponseBody(),
            $soapRequest->getLocation(),
            $soapRequest->getAction(),
            $soapRequest->getVersion(),
            $this->curl->getResponseContentType()
        );
    }

    /**
     * Custom request method to be able to modify the SOAP messages.
     * $oneWay parameter is not used at the moment.
     *
     * @param string $request  Request string
     * @param string $location Location
     * @param string $action   SOAP action
     * @param int $version     SOAP version
     * @param bool $oneWay     0|1
     *
     * @return string|null
     */
    public function __doRequest(
        string $request,
        string $location,
        string $action,
        int $version,
        bool $oneWay = false
    ): ?string {
        // wrap request data in SoapRequest object
        $soapRequest = SoapRequest::create($request, $location, $action, $version);

        // do actual SOAP request
        $soapResponse = $this->__doRequest2($soapRequest);

        // return SOAP response to ext/soap
        return $soapResponse->getContent();
    }

    /**
     * Runs the currently registered request filters on the request, performs
     * the HTTP request and runs the response filters.
     *
     * @param SoapRequest $soapRequest SOAP request object
     *
     * @return SoapResponse
     *
     * @throws SoapFault
     */
    protected function __doRequest2(SoapRequest $soapRequest): SoapResponse
    {
        // run SoapKernel on SoapRequest
        $this->soapKernel->filterRequest($soapRequest);

        // perform HTTP request with cURL
        $soapResponse = $this->__doHttpRequest($soapRequest);

        // run SoapKernel on SoapResponse
        $this->soapKernel->filterResponse($soapResponse);

        return $soapResponse;
    }

    /**
     * Filters HTTP headers which will be sent.
     *
     * @param SoapRequest $soapRequest SOAP request object
     * @param array $headers           An array of HTTP headers
     *
     * @return array
     */
    protected function filterRequestHeaders(SoapRequest $soapRequest, array $headers): array
    {
        return $headers;
    }

    /**
     * Adds additional cURL options for the request.
     *
     * @param SoapRequest $soapRequest SOAP request object
     *
     * @return array
     */
    protected function filterRequestOptions(SoapRequest $soapRequest): array
    {
        return [];
    }

    /**
     * Get last request HTTP headers.
     *
     * @return string
     */
    public function __getLastRequestHeaders(): string
    {
        return $this->lastRequestHeaders;
    }

    /**
     * Get last request HTTP body.
     *
     * @return string
     */
    public function __getLastRequest(): string
    {
        return $this->lastRequest;
    }

    /**
     * Get last response HTTP headers.
     *
     * @return string
     */
    public function __getLastResponseHeaders(): string
    {
        return $this->lastResponseHeaders;
    }

    /**
     * Get last response HTTP body.
     *
     * @return string
     */
    public function __getLastResponse(): string
    {
        return $this->lastResponse;
    }

    /**
     * Get SoapKernel instance.
     *
     * @return SoapKernel|null
     */
    public function getSoapKernel(): ?SoapKernel
    {
        return $this->soapKernel;
    }

    /**
     * Configure filter and type converter for SwA/MTOM.
     *
     * @param array &$options SOAP constructor options array
     */
    private function configureMime(array &$options): void
    {
        if (isset($options['attachment_type']) &&
            Helper::ATTACHMENTS_TYPE_BASE64 !== $options['attachment_type']) {
            // register mime filter in SoapKernel
            $mimeFilter = new MimeFilter($options['attachment_type']);
            $this->soapKernel->registerFilter($mimeFilter);
            // configure type converter
            if (Helper::ATTACHMENTS_TYPE_MTOM === $options['attachment_type']) {
                $xmlMimeFilter = new XmlMimeFilter();
                $this->soapKernel->registerFilter($xmlMimeFilter);
                $converter = new MtomTypeConverter();
                $converter->setKernel($this->soapKernel);

                // configure typemap
                if (!isset($options['typemap'])) {
                    $options['typemap'] = [];
                }
                $options['typemap'][] = [
                    'type_name' => $converter->getTypeName(),
                    'type_ns' => $converter->getTypeNamespace(),
                    'from_xml' => fn($input) => $converter->convertXmlToPhp($input),
                    'to_xml' => fn($input) => $converter->convertPhpToXml($input),
                ];
            }
        }
    }

    /**
     * Downloads WSDL files with cURL. Uses all SoapClient options for
     * authentication. Uses the WSDL_CACHE_* constants and the 'soap.wsdl_*'
     * ini settings. Does only file caching as SoapClient only supports a file
     * name parameter.
     *
     * @param string $wsdl   WSDL file
     * @param array $options (string=>mixed) $options Options array
     *
     * @return string
     *
     * @throws SoapFault
     * @throws ErrorException
     */
    protected function loadWsdl(string $wsdl, array $options): string
    {
        // option to resolve wsdl/xsd includes
        $resolveRemoteIncludes = true;
        if (isset($options['resolve_wsdl_remote_includes'])) {
            $resolveRemoteIncludes = $options['resolve_wsdl_remote_includes'];
        }
        // option to enable cache
        $wsdlCache = WSDL_CACHE_DISK;
        if (isset($options['cache_wsdl'])) {
            $wsdlCache = $options['cache_wsdl'];
        }
        // customização AGU
        $forceHttpsAlways = false;
        if (isset($options['force_https_always'])
        ) {
            $forceHttpsAlways = $options['force_https_always'];
        }

        $wsdlDownloader = new WsdlDownloader($this->curl, $wsdlCache, $resolveRemoteIncludes, $forceHttpsAlways);

        try {
            $cacheFileName = $wsdlDownloader->download($wsdl);
        } catch (RuntimeException $e) {
            throw new SoapFault('WSDL', $e->getMessage().' - '.$e->getTraceAsString());
        }

        return $cacheFileName;
    }
}

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

use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapMessage;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapRequest as CommonSoapRequest;

/**
 * SoapRequest class for SoapClient. Provides factory function for request object.
 *
 * @author Andreas Schamberger <mail@andreass.net>
 */
class SoapRequest extends CommonSoapRequest
{
    /**
     * Factory function for SoapRequest.
     *
     * @param string $content  Content
     * @param string $location Location
     * @param string $action   SOAP action
     * @param string $version  SOAP version
     *
     * @return SoapRequest
     */
    public static function create($content, $location, $action, $version)
    {
        $request = new SoapRequest();
        // $content is if unmodified from SoapClient not a php string type!
        $request->setContent((string) $content);
        $request->setLocation($location);
        $request->setAction($action);
        $request->setVersion($version);
        $contentType = SoapMessage::getContentTypeForVersion($version);
        $request->setContentType($contentType);

        return $request;
    }
}

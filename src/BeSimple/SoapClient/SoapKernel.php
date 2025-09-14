<?php

declare(strict_types=1);

/*
 * This file is part of the BeSimpleSoapCommon.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 * (c) Andreas Schamberger <mail@andreass.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SuppCore\AdministrativoBackend\BeSimple\SoapClient;

use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapKernel as CommonSoapKernel;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapRequest as CommonSoapRequest;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapResponse as CommonSoapResponse;

/**
 * SoapKernel for Client.
 *
 * @author Andreas Schamberger <mail@andreass.net>
 */
class SoapKernel extends CommonSoapKernel
{
    /**
     * {@inheritdoc}
     */
    public function filterRequest(CommonSoapRequest $request)
    {
        $request->setAttachments($this->attachments);
        $this->attachments = [];

        parent::filterRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function filterResponse(CommonSoapResponse $response)
    {
        parent::filterResponse($response);

        $this->attachments = $response->getAttachments();
    }
}

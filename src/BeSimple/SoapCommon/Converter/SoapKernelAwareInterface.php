<?php

declare(strict_types=1);

/*
 * This file is part of the BeSimpleSoapCommon.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SuppCore\AdministrativoBackend\BeSimple\SoapCommon\Converter;

use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\SoapKernel;

/**
 * Internal type converter interface.
 *
 * @author Andreas Schamberger <mail@andreass.net>
 */
interface SoapKernelAwareInterface
{
    /**
     * Set SoapKernel instance.
     *
     * @param SoapKernel $soapKernel SoapKernel instance
     */
    public function setKernel(SoapKernel $soapKernel);
}

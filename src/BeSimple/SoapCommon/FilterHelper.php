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

namespace SuppCore\AdministrativoBackend\BeSimple\SoapCommon;

use DOMAttr;
use DOMDocument;
use DOMElement;

/**
 * Soap request/response filter helper for manipulating SOAP messages.
 *
 * @author Andreas Schamberger <mail@andreass.net>
 */
class FilterHelper
{
    /**
     * DOMDocument on which the helper functions operate.
     */
    protected ?DOMDocument $domDocument = null;

    /**
     * Namespaces added.
     *
     * @var array(string=>string)
     */
    protected array $namespaces = [];

    /**
     * Constructor.
     *
     * @param DOMDocument $domDocument SOAP document
     */
    public function __construct(DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
    }

    /**
     * Add new soap header.
     *
     * @param DOMElement $node           DOMElement to add
     * @param bool       $mustUnderstand SOAP header mustUnderstand attribute
     * @param string     $actor          SOAP actor/role
     * @param int        $soapVersion    SOAP version SOAP_1_1|SOAP_1_2
     */
    public function addHeaderElement(DOMElement $node, $mustUnderstand = null, $actor = null, $soapVersion = SOAP_1_1)
    {
        $root = $this->domDocument->documentElement;
        $namespace = $root->namespaceURI;
        $prefix = $root->prefix;
        if (null !== $mustUnderstand) {
            $node->appendChild(new DOMAttr($prefix.':mustUnderstand', (int) $mustUnderstand));
        }
        if (null !== $actor) {
            $attributeName = (SOAP_1_1 == $soapVersion) ? 'actor' : 'role';
            $node->appendChild(new DOMAttr($prefix.':'.$attributeName, $actor));
        }
        $nodeListHeader = $root->getElementsByTagNameNS($namespace, 'Header');
        // add header if not there
        if (0 == $nodeListHeader->length) {
            // new header element
            $header = $this->domDocument->createElementNS($namespace, $prefix.':Header');
            // try to add it before body
            $nodeListBody = $root->getElementsByTagNameNS($namespace, 'Body');
            if (0 == $nodeListBody->length) {
                $root->appendChild($header);
            } else {
                $body = $nodeListBody->item(0);
                $header = $body->parentNode->insertBefore($header, $body);
            }
            $header->appendChild($node);
        } else {
            $nodeListHeader->item(0)->appendChild($node);
        }
    }

    /**
     * Add new soap body element.
     *
     * @param DOMElement $node DOMElement to add
     */
    public function addBodyElement(DOMElement $node)
    {
        $root = $this->domDocument->documentElement;
        $namespace = $root->namespaceURI;
        $prefix = $root->prefix;
        $nodeList = $this->domDocument->getElementsByTagNameNS($namespace, 'Body');
        // add body if not there
        if (0 == $nodeList->length) {
            // new body element
            $body = $this->domDocument->createElementNS($namespace, $prefix.':Body');
            $root->appendChild($body);
            $body->appendChild($node);
        } else {
            $nodeList->item(0)->appendChild($node);
        }
    }

    /**
     * Add new namespace to root tag.
     *
     * @param string $prefix       Namespace prefix
     * @param string $namespaceURI Namespace URI
     */
    public function addNamespace($prefix, $namespaceURI)
    {
        if (!isset($this->namespaces[$namespaceURI])) {
            $root = $this->domDocument->documentElement;
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:'.$prefix, $namespaceURI);
            $this->namespaces[$namespaceURI] = $prefix;
        }
    }

    /**
     * Create new element for given namespace.
     *
     * @param string $namespaceURI Namespace URI
     * @param string $name         Element name
     * @param string $value        Element value
     *
     * @return DOMElement
     */
    public function createElement($namespaceURI, $name, $value = null)
    {
        $prefix = $this->namespaces[$namespaceURI];

        return $this->domDocument->createElementNS($namespaceURI, $prefix.':'.$name, $value);
    }

    /**
     * Add new attribute to element with given namespace.
     *
     * @param DOMElement $element      DOMElement to edit
     * @param string     $namespaceURI Namespace URI
     * @param string     $name         Attribute name
     * @param string     $value        Attribute value
     */
    public function setAttribute(DOMElement $element, $namespaceURI, $name, $value)
    {
        if (null !== $namespaceURI) {
            $prefix = $this->namespaces[$namespaceURI];
            $element->setAttributeNS($namespaceURI, $prefix.':'.$name, $value);
        } else {
            $element->setAttribute($name, $value);
        }
    }

    /**
     * Register namespace.
     *
     * @param string $prefix       Namespace prefix
     * @param string $namespaceURI Namespace URI
     */
    public function registerNamespace($prefix, $namespaceURI)
    {
        if (!isset($this->namespaces[$namespaceURI])) {
            $this->namespaces[$namespaceURI] = $prefix;
        }
    }
}

<?php
/**
 * Copyright 
 *
 */

namespace TheSeer\fDOM {

    /**
     * fDOMDocumentFragmen
     * @property  fDOMDocument $ownerDocument
     *
     */
    class fDOMDocumentFragment extends \DOMDocumentFragment {

        /**
         * @return string
         */
        public function __toString() {
            return $this->ownerDocument->saveXML($this);
        }

        /**
         * Wrapper to standard method with exception support
         *
         * @param string $str Data string to parse and append
         *
         * @throws fDOMException
         *
         * @return bool true on success
         */
        public function appendXML($str) {
            if (!parent::appendXML($str)) {
                throw new fDOMException('Appending xml string failed', fDOMException::ParseError);
            }
            return true;
        }

        /**
         * Create a new element and append it
         *
         * @param string $name     Name of not element to create
         * @param string $content  Optional content to be set
         *
         * @return fDOMElement Reference to created fDOMElement
         */
        public function appendElement($name, $content = null) {
            $node = $this->ownerDocument->createElement($name, $content);
            $this->appendChild($node);
            return $node;
        }

        /**
         * Create a new element in given namespace and append it
         *
         * @param string $ns       Namespace of node to create
         * @param string $name     Name of not element to create
         * @param string $content  Optional content to be set
         *
         * @return fDOMElement Reference to created fDOMElement
         */
        public function appendElementNS($ns, $name, $content = null) {
            $node = $this->ownerDocument->createElementNS($ns, $name, $content);
            $this->appendChild($node);
            return $node;
        }

        /**
         * Create a new element in given namespace and append it
         *
         * @param string $prefix   Namespace prefix for node to create
         * @param string $name     Name of not element to create
         * @param string $content  Optional content to be set
         * @param bool $asTextnode Create content as textNode rather then setting nodeValue
         *
         * @return fDOMElement Reference to created fDOMElement
         */
        public function appendElementPrefix($prefix, $name, $content = null, $asTextnode = FALSE) {
            $node = $this->ownerDocument->createElementPrefix($prefix, $name, $content, $asTextnode);
            $this->appendChild($node);
            return $node;
        }

        /**
         * Create a new text node and append it
         *
         * @param string $content Text content to be added
         *
         * @return \DOMText
         */
        public function appendTextNode($content) {
            $text = $this->ownerDocument->createTextNode($content);
            $this->appendChild($text);
            return $text;
        }

        /**
         * Check if the given node is in the same document
         *
         * @param \DOMNode $node Node to compare with
         *
         * @return boolean true on match, false if they differ
         *
         */
        public function inSameDocument(\DOMNode $node) {
            return $this->ownerDocument->inSameDocument($node);
        }


        /**
         * Forward to fDomDocument->query()
         *
         * @param string   $q               XPath to use
         * @param \DOMNode $ctx             \DOMNode to overwrite context
         * @param boolean  $registerNodeNS  Register flag pass thru
         *
         * @return \DomNodeList
         */
        public function query($q, \DOMNode $ctx = null, $registerNodeNS = true) {
            return $this->ownerDocument->query($q, $ctx ? $ctx : $this, $registerNodeNS);
        }

        /**
         * Forward to fDomDocument->queryOne()
         *
         * @param string   $q               XPath to use
         * @param \DOMNode $ctx             (optional) \DOMNode to overwrite context
         * @param boolean  $registerNodeNS  Register flag pass thru
         *
         * @return mixed
         */
        public function queryOne($q, \DOMNode $ctx = null, $registerNodeNS = true) {
            return $this->ownerDocument->queryOne($q, $ctx ? $ctx : $this, $registerNodeNS);
        }

        /**
         * Forward to fDomDocument->select()
         *
         * @param string   $selector A CSS Level 3 Selector string
         * @param \DOMNode $ctx
         * @param bool     $registerNodeNS
         *
         * @return \DOMNodeList
         */
        public function select($selector, \DOMNode $ctx = null, $registerNodeNS = true) {
            return $this->ownerDocument->select($selector, $ctx ? $ctx : $this, $registerNodeNS);
        }

    } // fDOMDocumentFragment

}

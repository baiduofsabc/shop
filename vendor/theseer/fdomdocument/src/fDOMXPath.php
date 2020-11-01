<?php
/**
 * Copyright
 *
 */

namespace TheSeer\fDOM {

    /**
     * fDOMXPath extension to PHP's DOMXPath.
     *
     * @category  PHP
     * @package   TheSeer\fDOM
     * @access    public
     *
     */
    class fDOMXPath extends \DOMXPath {

        /**
         * @var \DOMDocument
         */
        protected $doc;

        /**
         * @param \DOMDocument $doc
         */
        public function __construct(\DOMDocument $doc) {
            parent::__construct($doc);
            $this->doc = $doc;
        }

        /**
         * @param string $xpath
         * @param array $valueMap
         *
         * @return string
         */
        public function prepare($xpath, array $valueMap) {
            if (count($valueMap)==0) {
                return $xpath;
            }
            foreach($valueMap as $key => $value) {
                $xpath = str_replace(':'.$key, $this->quote($value), $xpath);
            }
            return $xpath;
        }

        /**
         * @param string $q
         * @param \DOMNode $ctx
         * @param bool $registerNodeNS
         *
         * @throws fDOMException
         *
         * @return \DOMNodeList
         */
        public function query($q, \DOMNode $ctx = null, $registerNodeNS = true) {
            libxml_clear_errors();
            if (version_compare(PHP_VERSION, '5.3.3', '<') || strpos(PHP_VERSION, 'hiphop') || strpos(PHP_VERSION, 'hhvm')) {
                $rc = parent::query($q, ($ctx instanceof \DOMNode) ? $ctx : $this->doc->documentElement);
            } else {
                $rc = parent::query($q, ($ctx instanceof \DOMNode) ? $ctx : $this->doc->documentElement, $registerNodeNS);
            }

            if (libxml_get_last_error()) {
                throw new fDOMException('evaluating xpath expression failed.', fDOMException::QueryError);
            }
            return $rc;
        }

        /**
         * @param string $q
         * @param \DOMNode $ctx
         * @param bool $registerNodeNS
         *
         * @throws fDOMException
         *
         * @return mixed
         */
        public function evaluate($q, \DOMNode $ctx = null, $registerNodeNS = true) {
            libxml_clear_errors();
            if (version_compare(PHP_VERSION, '5.3.3', '<') || strpos(PHP_VERSION, 'hiphop') || strpos(PHP_VERSION, 'hhvm')) {
                $rc = parent::evaluate($q, ($ctx instanceof \DOMNode) ? $ctx : $this->doc->documentElement);
            } else {
                $rc = parent::evaluate($q, ($ctx instanceof \DOMNode) ? $ctx : $this->doc->documentElement, $registerNodeNS);
            }
            if (libxml_get_last_error()) {
                throw new fDOMException('evaluating xpath expression failed.', fDOMException::QueryError);
            }
            return $rc;
        }

        /**
         * @param string $q
         * @param \DOMNode $ctx
         * @param bool $registerNodeNS
         *
         * @throws fDOMException
         *
         * @return \DOMNode|mixed
         */
        public function queryOne($q, \DOMNode $ctx = null, $registerNodeNS = true) {
            $rc = $this->evaluate($q, $ctx, $registerNodeNS);
            if ($rc instanceof \DOMNodelist) {
                return $rc->item(0);
            }
            return $rc;
        }

        /**
         * @param string $str
         *
         * @return string
         */
        public function quote($str) {
            if (strpos($str, '"') === false) {
                return '"'.$str.'"';
            }
            $parts = explode('"', $str);
            return 'concat("' . join('",\'"\',"', $parts).'")';
        }
    }
}

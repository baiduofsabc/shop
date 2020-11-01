<?php
/**
 * Copyright
 *
 */

namespace TheSeer\fDOM {

    /**
     * Class XPathQuery
     *
     * @package TheSeer\fDOM
     */
    class XPathQuery {

        /**
         * @var string
         */
        private $query;

        /**
         * Key-value Map for bound values
         *
         * @var array
         */
        private $values = array();

        /**
         * @param string $query
         */
        public function __construct($query) {
            $this->setQuery($query);
        }

        /**
         * Set Query.
         *
         * @param string $query
         */
        private function setQuery($query) {
            $this->query = $query;
            $res = preg_match_all('/(:(\w*))/', $query, $matches);
            if ($res > 0) {
                $this->values = array_fill_keys($matches[2], '');
            }
        }

        /**
         * Returns keys.
         *
         * @return array
         */
        public function getKeys() {
            return array_keys($this->values);
        }

        /**
         * Bind value to key.
         *
         * @param string $key
         * @param string $value
         *
         * @throws XPathQueryException
         */
        public function bind($key, $value) {
            if (!array_key_exists($key, $this->values)) {
                throw new XPathQueryException("'$key' not found in query'", XPathQueryException::KeyNotFound );
            }
            $this->values[$key] = $value;
        }

        /**
         * Generate query.
         *
         * @param \DOMNode $ctx
         * @param array $values
         *
         * @return string
         */
        public function generate(\DOMNode $ctx, array $values = NULL) {
            return $this->buildQuery($this->getXPathObjectFor($ctx), $values);
        }

        /**
         * Evaluate Query.
         *
         * @param \DOMNode $ctx
         * @param array $values
         * @param bool $registerNodeNS
         *
         * @throws fDOMException
         *
         * @return mixed
         */
        public function evaluate(\DOMNode $ctx, array $values = NULL, $registerNodeNS = TRUE) {
            $xp = $this->getXPathObjectFor($ctx);
            return $xp->evaluate($this->buildQuery($xp, $values), $ctx, $registerNodeNS);
        }

        /**
         * Execute Query.
         *
         * @param \DOMNode $ctx
         * @param array $values
         * @param bool $registerNodeNS
         *
         * @throws fDOMException
         *
         * @return mixed
         */
        public function query(\DOMNode $ctx, array $values = NULL, $registerNodeNS = TRUE) {
            $xp = $this->getXPathObjectFor($ctx);
            return $xp->evaluate($this->buildQuery($xp, $values), $ctx, $registerNodeNS);
        }

        /**
         * Execute Query and return first result.
         *
         * @param \DOMNode $ctx
         * @param array $values
         * @param bool $registerNodeNS
         *
         * @return \DOMNode
         */
        public function queryOne(\DOMNode $ctx, array $values = NULL, $registerNodeNS = TRUE) {
            $xp = $this->getXPathObjectFor($ctx);
            return $xp->queryOne($this->buildQuery($xp, $values), $ctx, $registerNodeNS);
        }

        /**
         * Return xPath for node
         *
         * @param \DOMNode $ctx
         *
         * @throws fDOMException
         *
         * @return fDOMXPath
         */
        private function getXPathObjectFor(\DOMNode $ctx) {
            $dom = $ctx instanceof \DOMDocument ? $ctx : $ctx->ownerDocument;
            if ($dom instanceOf fDOMDocument) {
                return $dom->getDOMXPath();
            }
            return new fDOMXPath($dom);
        }

        /**
         * Build query using values.
         *
         * @param fDOMXPath $xp
         * @param array $values
         *
         * @throws XPathQueryException
         *
         * @return string
         */
        private function buildQuery(fDOMXPath $xp, array $values = NULL) {
            $backup = $this->values;
            if (is_array($values) && count($values) > 0) {
                foreach($values as $k => $v) {
                    $this->bind($k, $v);
                }
            }
            $query = $xp->prepare($this->query, $this->values);
            $this->values = $backup;
            return $query;
        }
    }

}

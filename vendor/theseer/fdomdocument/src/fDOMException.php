<?php
/**
 * Copyright
 */

namespace TheSeer\fDOM {

    /**
     * fDOMException
     *
     * @category  PHP
     * @package   TheSeer\fDOM
     * @access    public
     *
     */
    class fDOMException extends \Exception {

        const LoadError          = 1;
        const ParseError         = 2;
        const SaveError          = 3;
        const QueryError         = 4;
        const RegistrationFailed = 5;
        const NoDOMXPath         = 6;
        const UnboundPrefix      = 7;
        const SetFailedError     = 8;
        const NameInvalid        = 9;

        /**
         * List of libxml error objects
         *
         * @var array
         */
        private $errorList;


        /**
         * Full Error message
         *
         * @var string
         */
        private $fullMessage = null;


        /**
         * Short Error Message
         *
         * @var string
         */
        private $shortMessage = null;


        private static $fullMesageMode = true;

        /**
         * Constructor
         *
         * @param string  $message Exception message
         * @param integer $code    Exception code
         * @param \Exception $chain optional chained exception
         *
         */
        public function __construct($message, $code = 0, \Exception $chain = NULL) {
            $this->shortMessage = $message;
            $this->errorList = libxml_get_errors();
            libxml_clear_errors();
            parent :: __construct($message, $code, $chain);

            $this->fullMessage = $message."\n\n";

            foreach ($this->errorList as $error) {
                // hack, skip "attempt to load external pseudo error"
                if ($error->code=='1543') {
                    continue;
                }

                if (empty($error->file)) {
                    $this->fullMessage .= '[XML-STRING] ';
                } else {
                    $this->fullMessage .= '['.$error->file.'] ';
                }

                $this->fullMessage .= '[Line: '.$error->line.' - Column: '.$error->column.'] ';

                switch ($error->level) {
                    case LIBXML_ERR_WARNING:
                        $this->fullMessage .= "Warning $error->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $this->fullMessage .= "Error $error->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $this->fullMessage .= "Fatal Error $error->code: ";
                        break;
                }

                $this->fullMessage .= str_replace("\n", '', $error->message)."\n";

                if (self::$fullMesageMode) {
                    $this->message = $this->fullMessage;
                }

            }
        }

        /**
         * Accessor to fullMessage
         *
         * @return string
         */
        public function getFullMessage() {
            return $this->fullMessage;
        }

        /**
         * Access to shortMessage
         *
         * @return string
         */
        public function getShortMessage() {
            return $this->shortMessage;
        }

        /**
         * Accessor to errorList objets
         *
         * @return array
         */
        public function getErrorList() {
            return $this->errorList;
        }

        /**
         * Toggle wehter getMessage() should return full or only exception message
         *
         * @param boolean $full Flag to enable or disable full message output
         *
         * @return void
         */
        public function toggleFullMessage($full = true) {
            $this->message = $full ? $this->fullMessage : $this->shortMessage;
        }

        /**
         * Magic method for string context
         *
         * @return string
         */
        public function __toString() {
            return $this->fullMessage;
        }

    } // fDOMException

}

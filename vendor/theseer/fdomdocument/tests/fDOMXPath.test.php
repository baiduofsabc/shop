<?php
    /**
     * Copyright 
     */

namespace TheSeer\fDOM\Tests {

    use TheSeer\fDOM\fDOMDocument;
    use TheSeer\fDOM\fDOMXPath;

    /**
     */
    class fDOMXPathTest extends \PHPUnit\Framework\TestCase {

        /**
         * @var TheSeer\fDOM\fDOMDocument
         */
        private $dom;

        /**
         * @var TheSeer\fDOM\fDOMXPath
         */
        private $xp;

        public function setUp() {
            $this->dom = new fDOMDocument();
            $this->dom->loadXML('<?xml version="1.0" ?><root><node attr="foo" /></root>');
            $this->xp = $this->dom->getDOMXPath();
        }

        /**
         * @covers TheSeer\fDOM\fDOMXPath::query
         * @expectedException TheSeer\fDOM\fDOMException
         */
        public function testExecutingAQueryWithInvalidXPathThrowsException() {
            $this->xp->query('//[invalid');
        }

        public function testQueryReturnsNodeList() {
            $res = $this->xp->query('//*');
            $this->assertInstanceOf('DOMNodeList', $res);
            $this->assertEquals(2, $res->length);
        }

        /**
         * @covers TheSeer\fDOM\fDOMXPath::evaluate
         * @expectedException TheSeer\fDOM\fDOMException
         */
        public function testExecutingAQueryWithEvaluateWithInvalidXPathThrowsException() {
            $this->xp->evaluate('//[invalid');
        }

        /**
         * @covers TheSeer\fDOM\fDOMXPath::quote
         */
        public function testPrepareReturnsStraightStringOnPlainText() {
            $this->assertEquals('"test"', $this->xp->quote('test'));
        }

        public function testQueryOneReturnsANode() {
            $this->assertSame($this->dom->documentElement, $this->xp->queryOne('//root'));
        }

        public function testPrepareReturnsUnmodifiedXPathOnEmptyArray() {
            $xpath = 'test';
            $this->assertEquals($xpath, $this->xp->prepare($xpath, array()));
        }

        public function testQueryOneReturnsValueOnNonNodeQuery() {
            $this->assertEquals('1', $this->xp->queryOne('count(//root)'));
        }

    }

}

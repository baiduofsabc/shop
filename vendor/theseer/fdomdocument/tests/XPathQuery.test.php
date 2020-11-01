<?php
/**
 * Copyright 
 *
 */

namespace TheSeer\fDOM\Tests {

    use TheSeer\fDOM\XPathQuery;
    use TheSeer\fDOM\fDOMDocument;

    class XPathQueryTest extends \PHPUnit\Framework\TestCase {

        private $dom;

        protected function setUp() {
            $this->dom = new fDOMDocument();
            $this->dom->loadXML('<?xml version="1.0" ?><root attr="value" />');
        }


        public function testFindingKeysInQueryWorks() {
            $xp = new XPathQuery(':key');
            $this->assertEquals(array('key'), $xp->getKeys());
        }

        /**
         */
        public function testTryingToBindNonExistingKeyThrowsException() {
            $xp = new XPathQuery(':key');
            $xp->bind('other', 123);
        }

        public function testBoundValueForKeyGetsApplied() {
            $xp = new XPathQuery(':key');
            $xp->bind('key', 123);
            $this->assertEquals('"123"', $xp->generate($this->dom));
        }

        public function testAppliedValueForKeyIsUsedOnQueryAndReturnsNode() {
            $xp = new XPathQuery('//*[@attr = :key]');
            $xp->bind('key', 'value');
            $res = $xp->query($this->dom);
            $this->assertInstanceOf('\DOMNodelist', $res);
            $this->assertEquals(1, $res->length);
            $this->assertInstanceOf('\DOMNode', $res->item(0));
        }

        public function testOverwriteValueOnQuery() {
            $xp = new XPathQuery('//*[@attr = :key]');
            $xp->bind('key', 'first');
            $res = $xp->query($this->dom, array('key' => 'value'));
            $this->assertEquals(1, $res->length);
            $this->assertInstanceOf('\DOMNode', $res->item(0));
        }

        public function testAppliedValueForKeyIsUsedOnEvaluateAndReturnsNode() {
            $xp = new XPathQuery('//*[@attr = :key]');
            $xp->bind('key', 'value');
            $res = $xp->evaluate($this->dom);
            $this->assertInstanceOf('\DOMNodelist', $res);
            $this->assertEquals(1, $res->length);
            $this->assertInstanceOf('\DOMNode', $res->item(0));
        }

        public function testOverwriteValueOnEvaluate() {
            $xp = new XPathQuery('//*[@attr = :key]');
            $xp->bind('key', 'first');
            $res = $xp->evaluate($this->dom, array('key' => 'value'));
            $this->assertEquals(1, $res->length);
            $this->assertInstanceOf('\DOMNode', $res->item(0));
        }

        public function testCallToQueryOneReturnsOneNode() {
            $xp = new XPathQuery('//*[@attr]');
            $res = $xp->queryOne($this->dom);
            $this->assertInstanceOf('\DOMNode', $res);
        }

        public function testQueryCanBeRunWithStandardDomDocument() {
            $xp = new XPathQuery('/');
            $res = $xp->query(new \DomDocument());
            $this->assertInstanceOf('\DOMNodelist', $res);
        }

    }

}

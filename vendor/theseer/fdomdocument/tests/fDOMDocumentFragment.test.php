<?php
/**
 * Copyright 
 *
 */

namespace TheSeer\fDOM\Tests {

    use TheSeer\fDOM\fDOMDocument;
    use TheSeer\fDOM\fDOMDocumentFragment;

    /**
     *
     */
    class fDOMDocumentFragmentTest extends \PHPUnit\Framework\TestCase {

        /**
         * @var fDOMDocument
         */
        private $dom;

        /**
         * @var fDOMDocumentFragment
         */
        private $frag;

        public function setUp() {
            $this->dom = new fDOMDocument();
            $this->frag = $this->dom->createDocumentFragment();
        }

        public function testAppendedXMLGetsAddedAndIsParsedAsXML() {
            $this->frag->appendXML('<some />');
            $this->assertEquals('some', $this->frag->firstChild->nodeName);
        }

        /**
         * @expectedException \TheSeer\fDOM\fDOMException
         */
        public function testTryingToAppendInvalidXMLToFragmentThrowsException() {
            $this->frag->appendXML('<foo');
        }

        public function testCheckingInSameDocumentReturnsTrueOnNodeFromFragment() {
            $this->frag->appendXML('<some />');
            $this->assertTrue($this->frag->inSameDocument($this->frag->firstChild));
        }

        public function testAppendingANewElement() {
            $node = $this->frag->appendElement('append', 'text');
            $this->assertInstanceOf('TheSeer\fDOM\fDOMElement', $node);
            $this->assertEquals(1, $this->frag->query('count(append)'));
            $this->assertEquals('text', $node->nodeValue);
        }

        public function testAppendingANewElementWithinANamespace() {
            $node = $this->frag->appendElementNS('test:uri', 'append', 'text');
            $this->dom->registerNamespace('t', 'test:uri');
            $this->assertInstanceOf('TheSeer\fDOM\fDOMElement', $node);
            $this->assertEquals(1, $this->frag->query('count(t:append)'));
            $this->assertEquals('text', $node->nodeValue);
        }

        public function testAppendingANewElementWithinANamespaceByPrefix() {
            $this->dom->registerNamespace('t', 'test:uri');
            $node = $this->frag->appendElementPrefix('t', 'append', 'text');
            $this->assertInstanceOf('TheSeer\fDOM\fDOMElement', $node);
            $this->assertEquals(1, $this->frag->query('count(t:append)'));
            $this->assertEquals('text', $node->nodeValue);
        }

        public function testAppendingANewElementWithinANamespaceAsTextNodeByPrefix() {
            $this->dom->registerNamespace('t', 'test:uri');
            $node = $this->frag->appendElementPrefix('t', 'append', 'test & demo', true);
            $this->assertInstanceOf('TheSeer\fDOM\fDOMElement', $node);
            $this->assertEquals(1, $this->frag->query('count(t:append)'));
            $this->assertEquals('test & demo', $node->nodeValue);
        }

        public function testAppendingATextAsTextnode() {
            $node = $this->frag->appendTextNode('test & demo');
            $found = $this->frag->queryOne('text()');
            $this->assertSame($node, $found);
            $this->assertEquals('test & demo', $node->nodeValue);
        }

        public function testCSSSelectorReturnsCorrectNodes() {
            $node = $this->frag->appendElement('append', 'text');
            $result = $this->frag->select('append');
            $this->assertSame($node, $result->item(0));
            $this->assertEquals(1, $result->length);
        }

        public function testToStringReturnsSerializedXMLString() {
            $this->frag->appendElement('append', 'text');
            $this->assertEquals('<append>text</append>', (string)$this->frag);
        }

    }
}

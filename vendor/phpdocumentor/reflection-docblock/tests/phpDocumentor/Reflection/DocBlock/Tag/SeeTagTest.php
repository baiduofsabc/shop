<?php
/**
 * phpDocumentor See Tag Test
 */

namespace phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Test class for \phpDocumentor\Reflection\DocBlock\Tag\SeeTag
 */
class SeeTagTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $exContent,
        $exDescription,
        $exReference
    ) {
        $tag = new SeeTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($exContent, $tag->getContent());
        $this->assertEquals($exDescription, $tag->getDescription());
        $this->assertEquals($exReference, $tag->getReference());
    }

    /**
     * Data provider for testConstructorParesInputsIntoCorrectFields
     *
     * @return array
     */
    public function provideDataForConstuctor()
    {
        // $type, $content, $exContent, $exDescription, $exReference
        return array(
            array(
                'see',
                'Foo::bar()',
                'Foo::bar()',
                '',
                'Foo::bar()'
            ),
            array(
                'see',
                'Foo::bar() Testing',
                'Foo::bar() Testing',
                'Testing',
                'Foo::bar()',
            ),
            array(
                'see',
                'Foo::bar() Testing comments',
                'Foo::bar() Testing comments',
                'Testing comments',
                'Foo::bar()',
            ),
        );
    }
}

<?php
/**
 * phpDocumentor Covers Tag Tes
 */

namespace phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Test class for \phpDocumentor\Reflection\DocBlock\Tag\CoversTag
 */
class CoversTagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the \phpDocumentor\Reflection\DocBlock\Tag\CoversTag can create
     * a link for the covers doc block.
     *
     * @return void
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $exContent,
        $exDescription,
        $exReference
    ) {
        $tag = new CoversTag($type, $content);

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
                'covers',
                'Foo::bar()',
                'Foo::bar()',
                '',
                'Foo::bar()'
            ),
            array(
                'covers',
                'Foo::bar() Testing',
                'Foo::bar() Testing',
                'Testing',
                'Foo::bar()',
            ),
            array(
                'covers',
                'Foo::bar() Testing comments',
                'Foo::bar() Testing comments',
                'Testing comments',
                'Foo::bar()',
            ),
        );
    }
}

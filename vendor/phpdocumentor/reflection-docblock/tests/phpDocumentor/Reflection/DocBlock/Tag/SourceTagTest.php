<?php
/**
 * phpDocumentor Source Tag Test
 */

namespace phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Test class for \phpDocumentor\Reflection\DocBlock\Tag\SourceTag
 */
class SourceTagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the \phpDocumentor\Reflection\DocBlock\Tag\SourceTag can
     * understand the @source DocBlock.
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $exContent,
        $exDescription,
        $exStartingLine,
        $exLineCount
    ) {
        $tag = new SourceTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($exContent, $tag->getContent());
        $this->assertEquals($exDescription, $tag->getDescription());
        $this->assertEquals($exStartingLine, $tag->getStartingLine());
        $this->assertEquals($exLineCount, $tag->getLineCount());
    }

    /**
     * Data provider for testConstructorParesInputsIntoCorrectFields
     *
     * @return array
     */
    public function provideDataForConstuctor()
    {
        // $type, $content, $exContent, $exDescription, $exStartingLine, $exLineCount
        return array(
            array(
                'source',
                '2',
                '2',
                '',
                2,
                null
            ),
            array(
                'source',
                'Testing',
                'Testing',
                'Testing',
                1,
                null
            ),
            array(
                'source',
                '2 Testing',
                '2 Testing',
                'Testing',
                2,
                null
            ),
            array(
                'source',
                '2 3 Testing comments',
                '2 3 Testing comments',
                'Testing comments',
                2,
                3
            ),
            array(
                'source',
                '2 -1 Testing comments',
                '2 -1 Testing comments',
                '-1 Testing comments',
                2,
                null
            ),
            array(
                'source',
                '-1 1 Testing comments',
                '-1 1 Testing comments',
                '-1 1 Testing comments',
                1,
                null
            )
        );
    }
}

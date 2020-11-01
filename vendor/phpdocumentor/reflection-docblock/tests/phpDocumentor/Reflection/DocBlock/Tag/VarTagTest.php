<?php
/**
 * phpDocumentor Var Tag Test
 */

namespace phpDocumentor\Reflection\DocBlock\Tag;

/**
 */
class VarTagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $exType,
        $exVariable,
        $exDescription
    ) {
        $tag = new VarTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($exType, $tag->getType());
        $this->assertEquals($exVariable, $tag->getVariableName());
        $this->assertEquals($exDescription, $tag->getDescription());
    }

    /**
     * Data provider for testConstructorParesInputsIntoCorrectFields
     *
     * @return array
     */
    public function provideDataForConstuctor()
    {
        // $type, $content, $exType, $exVariable, $exDescription
        return array(
            array(
                'var',
                'int',
                'int',
                '',
                ''
            ),
            array(
                'var',
                'int $bob',
                'int',
                '$bob',
                ''
            ),
            array(
                'var',
                'int $bob Number of bobs',
                'int',
                '$bob',
                'Number of bobs'
            ),
            array(
                'var',
                '',
                '',
                '',
                ''
            ),
        );
    }
}

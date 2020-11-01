<?php
/**
 * phpcpd
 *
 */
namespace SebastianBergmann\PHPCPD;

/**
 * Represents an exact code clone file.
 */
class CodeCloneFile
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $startLine;

    /**
     * @param string  $name
     * @param integer $startLine
     */
    public function __construct($name, $startLine)
    {
        $this->name      = $name;
        $this->startLine = $startLine;
        $this->id        = $this->name . ':' . $this->startLine;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getStartLine()
    {
        return $this->startLine;
    }
}

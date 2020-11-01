<?php
/**
 * phpDocumentor
 *
 
 */

namespace phpDocumentor\Reflection\DocBlock\Tag;

use phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Reflection class for a @source tag in a Docblock.
 *
 
 */
class SourceTag extends Tag
{
    /**
     * @var int The starting line, relative to the structural element's
     *     location.
     */
    protected $startingLine = 1;

    /** 
     * @var int|null The number of lines, relative to the starting line. NULL
     *     means "to the end".
     */
    protected $lineCount = null;

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        if (null === $this->content) {
            $this->content
                = "{$this->startingLine} {$this->lineCount} {$this->description}";
        }

        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        parent::setContent($content);
        if (preg_match(
            '/^
                # Starting line
                ([1-9]\d*)
                \s*
                # Number of lines
                (?:
                    ((?1))
                    \s+
                )?
                # Description
                (.*)
            $/sux',
            $this->description,
            $matches
        )) {
            $this->startingLine = (int)$matches[1];
            if (isset($matches[2]) && '' !== $matches[2]) {
                $this->lineCount = (int)$matches[2];
            }
            $this->setDescription($matches[3]);
            $this->content = $content;
        }

        return $this;
    }

    /**
     * Gets the starting line.
     *
     * @return int The starting line, relative to the structural element's
     *     location.
     */
    public function getStartingLine()
    {
        return $this->startingLine;
    }

    /**
     * Sets the starting line.
     * @return $this
     */
    public function setStartingLine($startingLine)
    {
        $this->startingLine = $startingLine;

        $this->content = null;
        return $this;
    }

    /**
     * Returns the number of lines.
     *
     * @return int|null The number of lines, relative to the starting line. NULL
     *     means "to the end".
     */
    public function getLineCount()
    {
        return $this->lineCount;
    }

    /**
     * Sets the number of lines.
     * 
     * 
     * @return $this
     */
    public function setLineCount($lineCount)
    {
        $this->lineCount = $lineCount;

        $this->content = null;
        return $this;
    }
}

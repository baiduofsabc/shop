<?php
/**
 */

namespace phpDocumentor\Reflection\DocBlock;

/**
 * The context in which a DocBlock occurs.
 */
class Context
{
    /** @var string The current namespace. */
    protected $namespace = '';

    /** @var array List of namespace aliases => Fully Qualified Namespace. */
    protected $namespace_aliases = array();
    
    /** @var string Name of the structural element, within the namespace. */
    protected $lsen = '';
    
    /**
     */
    public function __construct(
        $namespace = '',
        array $namespace_aliases = array(),
        $lsen = ''
    ) {
        if (!empty($namespace)) {
            $this->setNamespace($namespace);
        }
        $this->setNamespaceAliases($namespace_aliases);
        $this->setLSEN($lsen);
    }

    /**
     * @return string The namespace where this DocBlock resides in.
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return array List of namespace aliases => Fully Qualified Namespace.
     */
    public function getNamespaceAliases()
    {
        return $this->namespace_aliases;
    }
    
    /**
     * Returns the Local Structural Element Name.
     * 
     * @return string Name of the structural element, within the namespace.
     */
    public function getLSEN()
    {
        return $this->lsen;
    }
    
    /**
     * Sets a new namespace
     * 
     * @return $this
     */
    public function setNamespace($namespace)
    {
        if ('global' !== $namespace
            && 'default' !== $namespace
        ) {
            // Srip leading and trailing slash
            $this->namespace = trim((string)$namespace, '\\');
        } else {
            $this->namespace = '';
        }
        return $this;
    }
    
    /**
     * Sets the namespace aliases, replacing all previous ones.
     */
    public function setNamespaceAliases(array $namespace_aliases)
    {
        $this->namespace_aliases = array();
        foreach ($namespace_aliases as $alias => $fqnn) {
            $this->setNamespaceAlias($alias, $fqnn);
        }
        return $this;
    }
    
    /**
     * Adds a namespace alias to the context.
     */
    public function setNamespaceAlias($alias, $fqnn)
    {
        $this->namespace_aliases[$alias] = '\\' . trim((string)$fqnn, '\\');
        return $this;
    }
    
    /**
     */
    public function setLSEN($lsen)
    {
        $this->lsen = (string)$lsen;
        return $this;
    }
}

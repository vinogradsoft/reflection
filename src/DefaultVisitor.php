<?php

namespace Vinograd\Reflection;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\NodeFactory;

class DefaultVisitor extends AbstractVisitor
{
    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param string $detect
     * @param string $found
     * @param mixed|null $data
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->result['FILES'][] = $factory->createLeaf($detect, $found);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param string $detect
     * @param string $found
     * @param mixed|null $data
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->result['DIRECTORIES'][] = $factory->createNode($detect, $found);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param string $detect
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {

    }

}
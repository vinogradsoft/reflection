<?php

namespace Vinograd\Reflection;

use Vinograd\IO\Exception\NotFoundException;
use Vinograd\Scanner\Leaf;
use Vinograd\Scanner\Node;
use Vinograd\Scanner\NodeFactory;

class DefaultNodeFactory implements NodeFactory
{
    /**
     * @param string $detect
     * @param string $found
     * @return Node
     * @throws NotFoundException
     */
    public function createNode($detect, $found): Node
    {
        return DirectoryReflection::createBinded($detect . DIRECTORY_SEPARATOR . $found);
    }

    /**
     * @param string $detect
     * @param string $found
     * @return Leaf
     * @throws NotFoundException
     */
    public function createLeaf($detect, $found): Leaf
    {
        return FileReflection::createBinded($detect . DIRECTORY_SEPARATOR . $found);
    }

}
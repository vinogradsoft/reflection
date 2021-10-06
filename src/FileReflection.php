<?php

namespace Vinograd\Reflection;

use Vinograd\Scanner\Leaf;
use Vinograd\SimpleFiles\File;

class FileReflection extends File implements Leaf
{

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->getSourcePath();
    }
}
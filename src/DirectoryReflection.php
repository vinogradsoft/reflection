<?php

namespace Vinograd\Reflection;

use Vinograd\Scanner\Node;
use Vinograd\SimpleFiles\Directory;

class DirectoryReflection extends Directory implements Node
{
    /**
     * @param ReflectionService $service
     */
    public function reflect(ReflectionService $service): void
    {
        $items = $service->search($this->getSource());

        if (isset($items['DIRECTORIES'])) {
            $directories = $items['DIRECTORIES'];
            foreach ($directories as $directory) {
                $directory->reflect($service);
                $this->addDirectory($directory);
            }
        }
        if (isset($items['FILES'])) {
            $files = $items['FILES'];
            foreach ($files as $file) {
                $this->addFile($file);
            }
        }
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->getSourcePath();
    }
}
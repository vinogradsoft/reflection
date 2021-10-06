<?php

namespace Test\Unit;

use Test\Cases\IoEnvCase;
use Vinograd\IO\Exception\NotFoundException;
use Vinograd\Reflection\DefaultNodeFactory;
use Vinograd\Reflection\DirectoryReflection;
use Vinograd\Reflection\FileReflection;

class DefaultNodeFactoryTest extends IoEnvCase
{

    public function testCreateNode()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/node',
            ]
        ]);
        $factory = new DefaultNodeFactory();
        $node = $factory->createNode($this->outPath . '/childL', 'node');
        self::assertInstanceOf(DirectoryReflection::class, $node);
        self::assertEquals($this->outPath . '/childL/node', $node->getSource());
    }

    public function testCreateNodeNotFound()
    {
        $this->expectException(NotFoundException::class);
        $factory = new DefaultNodeFactory();
        $factory->createNode($this->outPath . '/childL', 'node');
    }

    public function testCreateLeaf()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => 'initial1',
            ],
        ]);
        $factory = new DefaultNodeFactory();
        $node = $factory->createLeaf($this->outPath . '/childL', 'file1.txt');
        self::assertInstanceOf(FileReflection::class, $node);
        self::assertEquals($this->outPath . '/childL/file1.txt', $node->getSource());
    }

    public function testCreateLeafNotFound()
    {
        $this->expectException(NotFoundException::class);
        $factory = new DefaultNodeFactory();
        $factory->createLeaf($this->outPath . '/childL', 'file1.txt');
    }

}

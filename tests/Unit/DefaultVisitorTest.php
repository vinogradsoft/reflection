<?php

namespace Test\Unit;

use Test\Cases\IoEnvCase;
use Vinograd\Reflection\DefaultNodeFactory;
use Vinograd\Reflection\DefaultVisitor;
use Vinograd\Reflection\DirectoryReflection;
use Vinograd\Reflection\FileReflection;
use Vinograd\Scanner\SingleStrategy;

class DefaultVisitorTest extends IoEnvCase
{
    private $strategy;
    private $nodeFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->strategy = new SingleStrategy();
        $this->nodeFactory = new DefaultNodeFactory();
    }

    public function testVisitLeaf()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => 'initial1',
                $this->outPath . '/childL/file2.txt' => 'initial1',
            ],
        ]);
        $visitor = new DefaultVisitor();

        $visitor->visitLeaf($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'file1.txt');
        $visitor->visitLeaf($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'file2.txt');
        $result = $visitor->getResult();
        self::assertArrayHasKey('FILES', $result);
        self::assertInstanceOf(FileReflection::class, $result['FILES'][0]);
        self::assertEquals($this->outPath . '/childL/file1.txt', $result['FILES'][0]->getSource());
        self::assertInstanceOf(FileReflection::class, $result['FILES'][1]);
        self::assertEquals($this->outPath . '/childL/file2.txt', $result['FILES'][1]->getSource());
        self::assertCount(2, $result['FILES']);
        self::assertCount(1, $result);
    }

    public function testVisitNode()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/node1',
                $this->outPath . '/childL/node2',
            ],
        ]);
        $visitor = new DefaultVisitor();

        $visitor->visitNode($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'node1');
        $visitor->visitNode($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'node2');
        $result = $visitor->getResult();

        self::assertArrayHasKey('DIRECTORIES', $result);
        self::assertInstanceOf(DirectoryReflection::class, $result['DIRECTORIES'][0]);
        self::assertEquals($this->outPath . '/childL/node1', $result['DIRECTORIES'][0]->getSource());
        self::assertInstanceOf(DirectoryReflection::class, $result['DIRECTORIES'][1]);
        self::assertEquals($this->outPath . '/childL/node2', $result['DIRECTORIES'][1]->getSource());
        self::assertCount(2, $result['DIRECTORIES']);
        self::assertCount(1, $result);
    }

    public function testScanStarted()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/node1',
                $this->outPath . '/childL/node2',
            ],
        ]);
        $visitor = new DefaultVisitor();

        $visitor->visitNode($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'node1');
        $visitor->visitNode($this->strategy, $this->nodeFactory, $this->outPath . '/childL', 'node2');
        $visitor->scanStarted($this->strategy, $this->outPath . '/childL');
        $result = $visitor->getResult();
        self::assertEmpty($result);
    }
}

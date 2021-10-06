<?php

namespace Test\Unit;

use Test\Cases\IoEnvCase;
use Vinograd\FilesDriver\FilesystemDriver;
use Vinograd\Reflection\AbstractVisitor;
use Vinograd\Reflection\DefaultNodeFactory;
use Vinograd\Reflection\DefaultVisitor;
use Vinograd\Reflection\ReflectionService;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Scanner;
use Vinograd\Scanner\SingleStrategy;

class ReflectionServiceTest extends IoEnvCase
{

    public function testConstruct()
    {
        $searchService = new ReflectionService();

        $reflection = new \ReflectionObject($searchService);
        $property = $reflection->getProperty('scanner');
        $property->setAccessible(true);
        $objectValue = $property->getValue($searchService);
        self::assertInstanceOf(Scanner::class, $objectValue);
        $driver = $objectValue->getDriver();
        self::assertInstanceOf(FilesystemDriver::class, $driver);
        $nodeFactory = $objectValue->getNodeFactory();
        self::assertInstanceOf(DefaultNodeFactory::class, $nodeFactory);
        $visitor = $objectValue->getVisitor();
        self::assertInstanceOf(DefaultVisitor::class, $visitor);
        $strategy = $objectValue->getStrategy();
        self::assertInstanceOf(SingleStrategy::class, $strategy);
    }

    public function testConstructWithCustomVisitor()
    {
        $searchService = new ReflectionService($customVisitor = $this->getMockForAbstractClass(AbstractVisitor::class));

        $reflection = new \ReflectionObject($searchService);
        $property = $reflection->getProperty('scanner');
        $property->setAccessible(true);
        $objectValue = $property->getValue($searchService);
        self::assertInstanceOf(Scanner::class, $objectValue);
        $driver = $objectValue->getDriver();
        self::assertInstanceOf(FilesystemDriver::class, $driver);
        $nodeFactory = $objectValue->getNodeFactory();
        self::assertInstanceOf(DefaultNodeFactory::class, $nodeFactory);
        $visitor = $objectValue->getVisitor();
        self::assertSame($customVisitor, $visitor);

        $strategy = $objectValue->getStrategy();
        self::assertInstanceOf(SingleStrategy::class, $strategy);
    }


    public function testConstructWithCustomDriver()
    {
        $searchService = new ReflectionService(null, $customDriver = new ArrayDriver());

        $reflection = new \ReflectionObject($searchService);
        $property = $reflection->getProperty('scanner');
        $property->setAccessible(true);
        $objectValue = $property->getValue($searchService);
        self::assertInstanceOf(Scanner::class, $objectValue);
        $driver = $objectValue->getDriver();

        self::assertInstanceOf(ArrayDriver::class, $driver);
        self::assertSame($customDriver, $driver);

        $nodeFactory = $objectValue->getNodeFactory();
        self::assertInstanceOf(DefaultNodeFactory::class, $nodeFactory);
        $visitor = $objectValue->getVisitor();
        self::assertInstanceOf(DefaultVisitor::class, $visitor);
        $strategy = $objectValue->getStrategy();
        self::assertInstanceOf(SingleStrategy::class, $strategy);
    }

    public function testConstructWithCustomNodeFactory()
    {
        $searchService = new ReflectionService(null, null, $customNodeFactory = $this->getMockForAbstractClass(NodeFactory::class));

        $reflection = new \ReflectionObject($searchService);
        $property = $reflection->getProperty('scanner');
        $property->setAccessible(true);
        $objectValue = $property->getValue($searchService);
        self::assertInstanceOf(Scanner::class, $objectValue);
        $driver = $objectValue->getDriver();

        self::assertInstanceOf(FilesystemDriver::class, $driver);
        $nodeFactory = $objectValue->getNodeFactory();
        self::assertSame($customNodeFactory, $nodeFactory);

        $visitor = $objectValue->getVisitor();
        self::assertInstanceOf(DefaultVisitor::class, $visitor);
        $strategy = $objectValue->getStrategy();
        self::assertInstanceOf(SingleStrategy::class, $strategy);
    }

    public function testSearch()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/root',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => 'initial1',
                $this->outPath . '/childL/file2.txt' => 'initial1',
            ],
        ]);
        $searchService = new ReflectionService();

        $result = $searchService->search($this->outPath . '/childL');
        self::assertCount(2, $result);
        self::assertArrayHasKey('DIRECTORIES', $result);
        self::assertArrayHasKey('FILES', $result);
        $directories = $result['DIRECTORIES'];
        $files = $result['FILES'];
        self::assertCount(1, $directories);
        self::assertCount(2, $files);
        self::assertEquals($this->outPath . '/childL/file1.txt', $files[0]->getSource());
        self::assertEquals($this->outPath . '/childL/file2.txt', $files[1]->getSource());

        self::assertEquals($this->outPath . '/childL/root', $directories[0]->getSource());

        $result = $searchService->search($this->outPath . '/childL');
        self::assertCount(2, $result);
        self::assertArrayHasKey('DIRECTORIES', $result);
        self::assertArrayHasKey('FILES', $result);
        $directories = $result['DIRECTORIES'];
        $files = $result['FILES'];
        self::assertCount(1, $directories);
        self::assertCount(2, $files);
        self::assertEquals($this->outPath . '/childL/file1.txt', $files[0]->getSource());
        self::assertEquals($this->outPath . '/childL/file2.txt', $files[1]->getSource());

        self::assertEquals($this->outPath . '/childL/root', $directories[0]->getSource());
    }
}

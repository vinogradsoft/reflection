<?php

namespace Test\Unit;

use Test\Cases\IoEnvCase;
use Vinograd\Reflection\DirectoryReflection;
use Vinograd\Reflection\ReflectionService;
use Vinograd\SimpleFiles\AbstractDirectory;

class DirectoryReflectionTest extends IoEnvCase
{
    public function testGetSource()
    {
        $directoryReflection = new DirectoryReflection('name');
        self::assertEquals('name', $directoryReflection->getSource());

    }

    public function testGetSourceWithBinded()
    {
        $directoryReflection = DirectoryReflection::createBinded(__DIR__);
        self::assertEquals(__DIR__, $directoryReflection->getSource());
    }

    public function testReflect()
    {
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/root',
                $this->outPath . '/childL/root/child1',
                $this->outPath . '/childL/root/child1/child2',
                $this->outPath . '/childL/root/child1/child2/child3',
                $this->outPath . '/childL/root/child1/child2/child3/child4',
                $this->outPath . '/childL/root/child1/child2/child3/child10',
                $this->outPath . '/childL/root/child1/child2/child3/child4/child5',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => 'initial1',
                $this->outPath . '/childL/root/file7.txt' => 'initial7',
                $this->outPath . '/childL/root/child1/file6.txt' => 'initial6',
                $this->outPath . '/childL/root/child1/file10.txt' => 'initial10',
                $this->outPath . '/childL/root/child1/child2/file5.txt' => 'initial5',
                $this->outPath . '/childL/root/child1/child2/child3/file4.txt' => 'initial4',
                $this->outPath . '/childL/root/child1/child2/child3/child4/file3.txt' => 'initial3',
                $this->outPath . '/childL/root/child1/child2/child3/child4/child5/file2.txt' => 'initial2',
                $this->outPath . '/childL/root/child1/child2/child3/child4/child5/file11.txt' => 'initial11',
            ],
        ]);

        $childL = DirectoryReflection::createBinded($this->outPath . '/childL');
        $childL->reflect(new ReflectionService());

        $this->assertTreeItem($childL, 1, $childLChildsDirectories = $childL->getDirectories());
        $this->assertTreeLeafItem($childL, 1, $childLFiles = $childL->getFiles());
        self::assertEquals('file1.txt', $childLFiles['file1.txt']->getName());

        $root = $childLChildsDirectories['root'];
        $this->assertTreeItem($root, 1, $rootChildsDirectories = $root->getDirectories(), $childL);
        $this->assertTreeLeafItem($root, 1, $rootFiles = $root->getFiles(), $childL);
        self::assertEquals('file7.txt', $rootFiles['file7.txt']->getName());

        $child1 = $rootChildsDirectories['child1'];
        $this->assertTreeItem($child1, 1, $child1ChildsDirectories = $child1->getDirectories(), $root);
        $this->assertTreeLeafItem($child1, 2, $child1Files = $child1->getFiles(), $root);
        self::assertEquals('file6.txt', $child1Files['file6.txt']->getName());
        self::assertEquals('file10.txt', $child1Files['file10.txt']->getName());

        $child2 = $child1ChildsDirectories['child2'];
        $this->assertTreeItem($child2, 1, $child2ChildsDirectories = $child2->getDirectories(), $child1);
        $this->assertTreeLeafItem($child2, 1, $child2Files = $child2->getFiles(), $child1);
        self::assertEquals('file5.txt', $child2Files['file5.txt']->getName());

        $child3 = $child2ChildsDirectories['child3'];
        $this->assertTreeItem($child3, 2, $child3ChildsDirectories = $child3->getDirectories(), $child2);
        $this->assertTreeLeafItem($child3, 1, $child3Files = $child3->getFiles(), $child2);
        self::assertEquals('file4.txt', $child3Files['file4.txt']->getName());

        $child4 = $child3ChildsDirectories['child4'];

        $this->assertTreeItem($child4, 1, $child4ChildsDirectories = $child4->getDirectories(), $child3);
        $this->assertTreeLeafItem($child4, 1, $child4Files = $child4->getFiles(), $child3);
        self::assertEquals('file3.txt', $child4Files['file3.txt']->getName());

        $child10 = $child3ChildsDirectories['child10'];
        $this->assertTreeItem($child10, 0, $child10->getDirectories(), $child3);
        $this->assertTreeLeafItem($child10, 0, $child10->getFiles(), $child3);

        $child5 = $child4ChildsDirectories['child5'];
        $this->assertTreeItem($child5, 0, $child5->getDirectories(), $child4);
        $this->assertTreeLeafItem($child5, 2, $child5Files = $child5->getFiles(), $child4);
        self::assertEquals('file2.txt', $child5Files['file2.txt']->getName());
        self::assertEquals('file11.txt', $child5Files['file11.txt']->getName());
    }

    protected function assertTreeItem(
        AbstractDirectory  $directory,
        int                $countChilds,
        array              $childs = [],
        ?AbstractDirectory $parent = null
    )
    {
        $directories = $directory->getDirectories();
        self::assertCount($countChilds, $directories);
        if (empty($childs)) {
            self::assertEmpty($directories);
        } else {
            if ($countChilds !== count($childs)) {
                self::fail();
            }
            foreach ($childs as $child) {
                self::assertSame($child, $directories[$child->getLocalName()]);
                self::assertArrayHasKey($child->getLocalName(), $directories);
            }
        }
        if (empty($parent)) {
            self::assertEmpty($directory->getParent());
        } else {
            self::assertSame($parent, $directory->getParent());
        }
    }

    protected function assertTreeLeafItem(
        AbstractDirectory  $directory,
        int                $countChilds,
        array              $childs = [],
        ?AbstractDirectory $parent = null
    )
    {
        $files = $directory->getFiles();
        self::assertCount($countChilds, $files);
        if (empty($childs)) {
            self::assertEmpty($files);
        } else {
            if ($countChilds !== count($childs)) {
                self::fail();
            }
            foreach ($childs as $child) {
                self::assertSame($child, $files[$child->getLocalName()]);
                self::assertArrayHasKey($child->getLocalName(), $files);
            }
        }
        if (empty($parent)) {
            self::assertEmpty($directory->getParent());
        } else {
            self::assertSame($parent, $directory->getParent());
        }
    }

}

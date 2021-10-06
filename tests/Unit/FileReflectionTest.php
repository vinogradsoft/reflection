<?php

namespace Test\Unit;

use Vinograd\Reflection\FileReflection;
use PHPUnit\Framework\TestCase;

class FileReflectionTest extends TestCase
{
    public function testGetSource()
    {
        $fileReflection = new FileReflection('name');
        self::assertEquals('name', $fileReflection->getSource());

    }

    public function testGetSourceWithBinded()
    {
        $fileReflection = FileReflection::createBinded(__FILE__);
        self::assertEquals(__FILE__, $fileReflection->getSource());
    }
}

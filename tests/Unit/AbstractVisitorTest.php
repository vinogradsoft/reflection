<?php

namespace Test\Unit;

use Vinograd\Reflection\AbstractVisitor;
use PHPUnit\Framework\TestCase;

class AbstractVisitorTest extends TestCase
{

    public function testEquals()
    {
        $visitorA = $this->getMockForAbstractClass(AbstractVisitor::class);
        $visitorB = $this->getMockForAbstractClass(AbstractVisitor::class);
        self::assertTrue($visitorA->equals($visitorA));
        self::assertFalse($visitorA->equals($visitorB));
    }

}

<?php

namespace Vinograd\Reflection;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\Visitor;

abstract class AbstractVisitor implements Visitor
{
    /** @var array */
    protected $result = [];

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    public function clear(): void
    {
        $this->result = [];
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        $this->clear();
    }

    /**
     * @param Visitor $visitor
     * @return bool
     */
    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}
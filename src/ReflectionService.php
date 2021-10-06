<?php

namespace Vinograd\Reflection;

use Vinograd\FilesDriver\FilesystemDriver;
use Vinograd\Scanner\Driver;
use Vinograd\Scanner\Node;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Scanner;
use Vinograd\Scanner\SingleStrategy;

class ReflectionService
{

    /**@var Scanner */
    protected $scanner;

    /**
     * @param AbstractVisitor|null $visitor
     * @param Driver|null $driver
     * @param NodeFactory|null $factory
     */
    public function __construct(?AbstractVisitor $visitor = null, ?Driver $driver = null, ?NodeFactory $factory = null)
    {
        $this->scanner = $this->createScanner($visitor, $driver, $factory);
    }

    /**
     * @param AbstractVisitor|null $visitor
     * @param Driver|null $driver
     * @param NodeFactory|null $factory
     * @return Scanner
     */
    protected function createScanner(?AbstractVisitor $visitor = null, ?Driver $driver = null, ?NodeFactory $factory = null): Scanner
    {
        $scanner = new Scanner();
        if (empty($visitor)) {
            $scanner->setVisitor(new DefaultVisitor());
        } else {
            $scanner->setVisitor($visitor);
        }

        if (empty($driver)) {
            $scanner->setDriver(new FilesystemDriver());
        } else {
            $scanner->setDriver($driver);
        }

        if (empty($factory)) {
            $scanner->setNodeFactory(new DefaultNodeFactory());
        } else {
            $scanner->setNodeFactory($factory);
        }

        $scanner->setStrategy(new SingleStrategy());
        return $scanner;
    }

    /**
     * @return Node[]
     */
    public function search(string $path): array
    {
        /** @var AbstractVisitor $visitor */
        $visitor = $this->scanner->getVisitor();

        $this->scanner->search($path);
        $result = $visitor->getResult();
        $visitor->clear();
        return $result;
    }

}
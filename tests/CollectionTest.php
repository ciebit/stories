<?php
namespace Ciebit\Stories\Tests;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use PHPUnit\Framework\TestCase;
use ArrayIterator;

class CollectionTest extends TestCase
{
    private const title = 'Title test';
    private const totalItens = 3;

    private $collection; #:Collection

    public function testValues()
    {
        $this->assertEquals(self::totalItens, $this->collection->count());
        $this->assertInstanceOf(ArrayIterator::class, $this->collection->getIterator());
    }

    protected function setUp()
    {
        $this->collection = new Collection;

        for ($i=0; $i < self::totalItens; $i++) {
            $this->collection->add(
                new Story(
                    self::title . " {$i}",
                    Status::DRAFT()
                )
            );
        }
    }
}

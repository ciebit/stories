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

    public function testMethods(): void
    {
        $this->assertEquals(self::totalItens, $this->collection->count());
        $this->assertInstanceOf(ArrayIterator::class, $this->collection->getIterator());
    }

    public function testGetById(): void
    {
        $id = 2;
        $story = $this->collection->getById((string) $id);
        $this->assertEquals($id, $story->getId());
    }

    protected function setUp()
    {
        $this->collection = new Collection;

        for ($i=0; $i < self::totalItens; $i++) {
            $story = new Story(
                self::title . " {$i}",
                Status::DRAFT()
            );
            $story->setId((string) $i);
            $this->collection->add($story);
        }
    }
}

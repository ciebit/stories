<?php
namespace Ciebit\Stories\Tests\Storages;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use Ciebit\Stories\Storages\DatabaseSql;
use Ciebit\Stories\Tests\Connection;

class DatabaseSqlTest extends Connection
{
    public function testGet(): void
    {
        $this->database = new DatabaseSql($this->getPdo());
        $story = $this->database->get();
        $this->assertInstanceOf(Story::class, $story);
    }

    public function testGetFilterByStatus(): void
    {
        $this->database = new DatabaseSql($this->getPdo());
        $this->database->addFilterByStatus(Status::ACTIVE());
        $story = $this->database->get();
        $this->assertEquals(Status::ACTIVE(), $story->getStatus());
    }

    public function testGetFilterById(): void
    {
        $id = 2;
        $this->database = new DatabaseSql($this->getPdo());
        $this->database->addFilterById($id+0);
        $story = $this->database->get();
        $this->assertEquals($id, $story->getId());
    }

    public function testGetAll(): void
    {
        $this->database = new DatabaseSql($this->getPdo());
        $stories = $this->database->getAll();
        $this->assertInstanceOf(Collection::class, $stories);
        $this->assertCount(3, $stories);
    }

    public function testGetAllFilterByStatus(): void
    {
        $this->database = new DatabaseSql($this->getPdo());
        $this->database->addFilterByStatus(Status::ACTIVE());
        $stories = $this->database->getAll();
        $this->assertCount(1, $stories);
        $this->assertEquals(Status::ACTIVE(), $stories->getArrayObject()->offsetGet(0)->getStatus());
    }

    public function testGetAllFilterById(): void
    {
        $id = 3;
        $this->database = new DatabaseSql($this->getPdo());
        $this->database->addFilterById($id+0);
        $stories = $this->database->getAll();
        $this->assertCount(1, $stories);
        $this->assertEquals($id, $stories->getArrayObject()->offsetGet(0)->getId());
    }
}

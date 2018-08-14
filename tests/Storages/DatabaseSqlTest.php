<?php
namespace Ciebit\Stories\Tests\Storages;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use Ciebit\Stories\Storages\DatabaseSql;
use Ciebit\Stories\Tests\Connection;

class DatabaseSqlTest extends Connection
{
    public function getDatabase(): DatabaseSql
    {
        return new DatabaseSql($this->getPdo());
    }

    public function testGet(): void
    {
        $story = $this->getDatabase()->get();
        $this->assertInstanceOf(Story::class, $story);
    }

    public function testGetFilterByBody(): void
    {
        $database = $this->getDatabase();
        static $body = 'Text New 1';
        $database->addFilterByBody($body, '=');
        $story = $database->get();
        $this->assertEquals(1, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('%New 2', 'LIKE');
        $story = $database->get();
        $this->assertEquals(2, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('Fourth New%', 'LIKE');
        $story = $database->get();
        $this->assertEquals(4, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('%Five%', 'LIKE');
        $story = $database->get();
        $this->assertEquals(5, $story->getId());
    }

    public function testGetFilterByStatus(): void
    {
        $database = new DatabaseSql($this->getPdo());
        $database->addFilterByStatus(Status::ACTIVE());
        $story = $database->get();
        $this->assertEquals(Status::ACTIVE(), $story->getStatus());
    }

    public function testGetFilterById(): void
    {
        $id = 2;
        $database = new DatabaseSql($this->getPdo());
        $database->addFilterById($id+0);
        $story = $database->get();
        $this->assertEquals($id, $story->getId());
    }

    public function testGetFilterByTitle(): void
    {
        $database = $this->getDatabase();
        static $title1 = 'Title New 1';
        $database->addFilterByTitle($title1, '=');
        $story = $database->get();
        $this->assertEquals($title1, $story->getTitle());

        $database = $this->getDatabase();
        $database->addFilterByTitle('%New 2', 'LIKE');
        $story = $database->get();
        $this->assertEquals(2, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByTitle('Fourth New%', 'LIKE');
        $story = $database->get();
        $this->assertEquals(4, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByTitle('%Five%', 'LIKE');
        $story = $database->get();
        $this->assertEquals(5, $story->getId());
    }

    public function testGetAll(): void
    {
        $database = new DatabaseSql($this->getPdo());
        $stories = $database->getAll();
        $this->assertInstanceOf(Collection::class, $stories);
        $this->assertCount(5, $stories);
    }

    public function testGetAllFilterByStatus(): void
    {
        $database = new DatabaseSql($this->getPdo());
        $database->addFilterByStatus(Status::ACTIVE());
        $stories = $database->getAll();
        $this->assertCount(3, $stories);
        $this->assertEquals(Status::ACTIVE(), $stories->getArrayObject()->offsetGet(0)->getStatus());
    }

    public function testGetAllFilterById(): void
    {
        $id = 3;
        $database = new DatabaseSql($this->getPdo());
        $database->addFilterById($id+0);
        $stories = $database->getAll();
        $this->assertCount(1, $stories);
        $this->assertEquals($id, $stories->getArrayObject()->offsetGet(0)->getId());
    }
}

<?php
namespace Ciebit\Stories\Tests\Storages\Database;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use Ciebit\Stories\LanguageReference;
use Ciebit\Stories\Storages\Database\Sql;
use Ciebit\Stories\Tests\Connection;
use ArrayObject;
use DateTime;

class SqlTest extends Connection
{
    public function getDatabase(): Sql
    {
        return new Sql($this->getPdo());
    }

    public function testGet(): void
    {
        $story = $this->getDatabase()->get();
        $this->assertInstanceOf(Story::class, $story);
    }

    public function testGetFilterByBody(): void
    {
        $database = $this->getDatabase();
        static $body = 'Texto da notícia 1';
        $database->addFilterByBody('=', $body);
        $story = $database->get();
        $this->assertEquals(1, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('LIKE', '%New 2');
        $story = $database->get();
        $this->assertEquals(2, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('LIKE', 'Fourth New%');
        $story = $database->get();
        $this->assertEquals(4, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByBody('LIKE', '%Five%');
        $story = $database->get();
        $this->assertEquals(5, $story->getId());
    }

    public function testGetFilterByStatus(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByStatus('=', Status::ACTIVE());
        $story = $database->get();
        $this->assertEquals(Status::ACTIVE(), $story->getStatus());
    }

    public function testGetFilterById(): void
    {
        $id = 2;
        $database = $this->getDatabase();
        $database->addFilterById('=', $id+0);
        $story = $database->get();
        $this->assertEquals($id, $story->getId());
    }

    public function testGetFilterByTitle(): void
    {
        $database = $this->getDatabase();
        static $title1 = 'Titulo da notícia 1';
        $database->addFilterByTitle('=', $title1);
        $story = $database->get();
        $this->assertEquals($title1, $story->getTitle());

        $database = $this->getDatabase();
        $database->addFilterByTitle('LIKE', '%New 2');
        $story = $database->get();
        $this->assertEquals(2, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByTitle('LIKE', 'Fourth New%');
        $story = $database->get();
        $this->assertEquals(4, $story->getId());

        $database = $this->getDatabase();
        $database->addFilterByTitle('LIKE', '%Five%');
        $story = $database->get();
        $this->assertEquals(5, $story->getId());
    }

    public function testGetFilterByLanguage(): void
    {
        static $spanish = 'es';
        $database = $this->getDatabase()->addFilterByLanguage('=', $spanish);
        $story = $database->get();
        $this->assertEquals($spanish, $story->getLanguage());

        static $english = 'en';
        $database = $this->getDatabase()->addFilterByLanguage('=', $english);
        $story2 = $database->get();
        $this->assertEquals($english, $story2->getLanguage());

        static $portuguese = 'pt-br';
        $database = $this->getDatabase()->addFilterByLanguage('=', $portuguese);
        $story3 = $database->get();
        $this->assertEquals($portuguese, $story3->getLanguage());
    }

    public function testGetAll(): void
    {
        $database = $this->getDatabase();
        $stories = $database->getAll();
        $this->assertInstanceOf(Collection::class, $stories);
        $this->assertCount(7, $stories);
    }

    public function testGetAllFilterByStatus(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByStatus('=', Status::ACTIVE());
        $stories = $database->getAll();
        $this->assertCount(3, $stories);
        $this->assertEquals(Status::ACTIVE(), $stories->getArrayObject()->offsetGet(0)->getStatus());
    }

    public function testGetAllFilterById(): void
    {
        $id = 3;
        $database = $this->getDatabase();
        $database->addFilterById('=', $id+0);
        $stories = $database->getAll();
        $this->assertCount(1, $stories);
        $this->assertEquals($id, $stories->getArrayObject()->offsetGet(0)->getId());

        $stories = $this->getDatabase()->addFilterById('=', 2, 3)->getAll();
        $this->assertCount(2, $stories);
        $this->assertEquals(2, $stories->getById(2)->getId());
        $this->assertEquals(3, $stories->getById(3)->getId());
    }

    public function testLanguageReferences(): void
    {
        $database = $this->getDatabase();
        $story = $database->get();

        $refs = $story->getLanguageReferences();
        $this->assertInstanceOf(ArrayObject::class, $refs);
        $this->assertInstanceOf(LanguageReference::class, $refs->offSetGet(0));
        $this->assertEquals(6, $refs->offsetGet(0)->getReferenceId());
    }

    public function testUpdate(): void
    {
        $body = 'new body';
        $dateTime = new DateTime;
        $status = Status::ACTIVE();
        $summary = 'new summary';
        $uri = 'new-uri';
        $views = 13;

        $database = $this->getDatabase();
        $database->addFilterById('=', 2);
        $story = $database->get();
        $story->setBody($body.'')
        ->setDateTime(clone $dateTime)
        ->setStatus(clone $status)
        ->setSummary($summary.'')
        ->setUri($uri.'')
        ->setViews($views + 0);

        $story = $database->update($story)->get();

        $this->assertEquals($body, $story->getBody());
        $this->assertEquals($dateTime->format('Y-m-d H:i:s'), $story->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals($status, $story->getStatus());
        $this->assertEquals($summary, $story->getSummary());
        $this->assertEquals($uri, $story->getUri());
        $this->assertEquals($views, $story->getViews());
    }
}

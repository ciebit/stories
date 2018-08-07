<?php
namespace Ciebit\Stories\Tests;

use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use DateTime;
use PHPUnit\Framework\TestCase;

class StoryTest extends TestCase
{
    private const body = 'Text body article';
    private const datetime = '2018-07-28 00:20:14';
    private const id = 5;
    private const summary = 'Title test';
    private const status = Status::ACTIVE;
    private const title = 'Title test';
    private const uri = 'title-test';
    private const views = 2;

    private $story; #:Story

    public function testValues()
    {
        $this->assertEquals(self::body, $this->story->getBody());
        $this->assertEquals(self::datetime, $this->story->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(self::id, $this->story->getId());
        $this->assertEquals(self::summary, $this->story->getSummary());
        $this->assertEquals(self::status, $this->story->getStatus()->getValue());
        $this->assertEquals(self::title, $this->story->getTitle());
        $this->assertEquals(self::uri, $this->story->getUri());
        $this->assertEquals(self::views, $this->story->getViews());
    }

    protected function setUp()
    {
        $this->story = (new Story(self::title, Status::DRAFT()))
        ->setSummary(self::summary)
        ->setUri(self::uri)
        ->setBody(self::body)
        ->setDateTime(new DateTime(self::datetime))
        ->setId(self::id)
        ->setViews(self::views)
        ->setStatus(new Status(self::status))
        ;
    }
}

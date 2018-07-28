<?php
namespace Ciebit\Stories\Tests;

use Ciebit\Stories\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testAttributes(): void
    {
        $this->assertTrue(Status::ACTIVE != 0);
        $this->assertTrue(Status::ANALYZE != 0);
        $this->assertTrue(Status::DRAFT != 0);
        $this->assertTrue(Status::TRASH != 0);
    }
}

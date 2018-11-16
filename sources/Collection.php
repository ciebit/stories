<?php
declare(strict_types=1);

namespace Ciebit\Stories;

use ArrayIterator;
use ArrayObject;
use Countable;

class Collection implements Countable
{
    private $stories; #:ArrayObject

    public function __construct()
    {
        $this->stories = new ArrayObject;
    }

    public function add(Story $story): self
    {
        $this->stories->append($story);
        return $this;
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->stories;
    }

    public function getById(string $id): ?Story
    {
        foreach ($this->getIterator() as $story) {
            if ($story->getId() == $id) {
                return $story;
            }
        }

        return null;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->stories->getIterator();
    }

    public function count(): int
    {
        return $this->stories->count();
    }
}

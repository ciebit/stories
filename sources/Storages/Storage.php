<?php
declare(strict_types=1);

namespace Ciebit\Stories\Storages;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Story;
use Ciebit\Stories\Status;

interface Storage
{
    public function addFilterById(int $id, string $operator = '='): self;

    public function addFilterByStatus(Status $status, string $operator = '='): self;

    public function get(): ?Story;

    public function getAll(): Collection;

    public function setStartingLine(int $lineInit): self;

    public function setTotalLines(int $total): self;
}

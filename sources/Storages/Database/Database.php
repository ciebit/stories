<?php
namespace Ciebit\Stories\Storages\Database;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Story;
use Ciebit\Stories\Status;
use Ciebit\Stories\Storages\Storage;

interface Database extends Storage
{
    public function addFilterByBody(string $operator, string $title): self;

    public function addFilterById(string $operator, int ...$id): self;

    public function addFilterByStatus(string $operator, Status $status): self;

    public function addFilterByTitle(string $operator, string $title): self;

    public function addFilterByLanguage(string $operator, string $language): self;

    public function get(): ?Story;

    public function getAll(): Collection;

    public function setStartingItem(int $lineInit): self;

    public function setTotalItems(int $total): self;

    public function update(Story $story): self;
}

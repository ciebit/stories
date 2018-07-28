<?php
declare(strict_types=1);

namespace Ciebit\Stories\Storages;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Factories\FromArray as FactoryFromArray;
use Ciebit\Stories\Story;
use Ciebit\Stories\Status;
use Ciebit\Stories\Storages\Storage;
use Ciebit\Stories\Storages\DatabaseSqlFilters;
use Exception;
use PDO;

class DatabaseSql extends DatabaseSqlFilters implements Storage
{
    private $pdo; #: PDO
    private $table; #: string

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'cb_story';
    }

    public function addFilterById(int $id, string $operator = '='): Storage
    {
        $key = 'id';
        $sql = "`story`.`id` $operator :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_INT, $id);
        return $this;
    }

    public function addFilterByStatus(Status $status, string $operator = '='): Storage
    {
        $key = 'status';
        $sql = "`story`.`status` {$operator} :{$key}";
        $this->addFilter($key, $sql, PDO::PARAM_INT, $status->getValue());
        return $this;
    }

    public function get(): Story
    {
        $statement = $this->pdo->prepare("
            SELECT SQL_CALC_FOUND_ROWS
            {$this->getFields()}
            FROM {$this->table} as `story`
            WHERE {$this->generateSqlFilters()}
            LIMIT 1
        ");

        $this->bind($statement);

        if ($statement->execute() === false) {
            throw new Exception('ciebit.stories.storages.database.get_error', 2);
        }

        return FactoryFromArray::convert($story);
    }

    public function getAll(): Collection
    {
        $statement = $this->pdo->prepare("
            SELECT SQL_CALC_FOUND_ROWS
            {$this->getFields()}
            FROM {$this->table} as `story`
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlLimit()}
        ");

        $this->bind($statement);

        if ($statement->execute() === false) {
            throw new Exception('ciebit.stories.storages.database.get_error', 2);
        }

        $collection = new Collection;

        while ($story = $statement->fetch(PDO::FETCH_ASSOC)) {
            $collection->add(FactoryFromArray::convert($story));
        }

        return $collection;
    }

    private function getFields(): string
    {
        return '
            `story`.`id`,
            `story`.`title`,
            `story`.`summary`,
            `story`.`body`,
            `story`.`datetime`,
            `story`.`uri`,
            `story`.`views`,
            `story`.`status`
        ';
    }

    public function getTotalRows(): int
    {
        return $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
    }

    public function setStartingLine(int $lineInit): Storage
    {
        parent::setStartingLine($lineInit);
        return $this;
    }

    public function setTable(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    public function setTotalLines(int $total): Storage
    {
        parent::setTotalLines($total);
        return $this;
    }
}

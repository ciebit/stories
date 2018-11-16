<?php
declare(strict_types=1);

namespace Ciebit\Stories\Storages\Database;

use Ciebit\Stories\Collection;
use Ciebit\Stories\Builders\FromArray as BuilderFromArray;
use Ciebit\Stories\Story;
use Ciebit\Stories\Status;
use Ciebit\Stories\Storages\Database\Database;
use Ciebit\Stories\Storages\Database\SqlFilters;
use Exception;
use PDO;

use function count;
use function implode;

class Sql extends SqlFilters implements Database
{
    static private $counterKey = 0;

    private $pdo; #: PDO
    private $table; #: string

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'cb_stories';
    }

    public function addFilterByBody(string $operator, string $body): Database
    {
        $key = 'body';
        $field = '`story`.`body`';
        $sql = "{$field} {$operator} :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_STR, $body);

        return $this;
    }

    public function addFilterById(string $operator, int ...$ids): Database
    {
        $key = 'id';

        if (count($ids) == 1) {
            $sql = "`story`.`id` $operator :{$key}";
            $this->addfilter($key, $sql, PDO::PARAM_INT, $ids[0]);
            return $this;
        }

        $keyPrefix = $key;

        foreach ($ids as $id) {
            $key = $keyPrefix . self::$counterKey++;
            $this->addBind($key, PDO::PARAM_INT, $id);
            $keys[] = $key;
        }

        $keysSql = implode(', :', $keys);
        $this->addSqlFilter("`story`.`id` {$operator} (:{$keysSql})");
        return $this;
    }

    public function addFilterByLanguage(string $operator, string $language): Database
    {
        $key = 'laguage';
        $field = '`story`.`language`';
        $sql = "{$field} {$operator} :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_STR, $language);

        return $this;
    }

    public function addFilterByStatus(string $operator, Status $status): Database
    {
        $key = 'status';
        $sql = "`story`.`status` {$operator} :{$key}";
        $this->addFilter($key, $sql, PDO::PARAM_INT, $status->getValue());
        return $this;
    }

    public function addFilterByTitle(string $operator, string $title): Database
    {
        $key = 'title';
        $field = '`story`.`title`';
        $sql = "{$field} {$operator} :{$key}";

        $this->addfilter($key, $sql, PDO::PARAM_STR, $title);

        return $this;
    }

    public function get(): ?Story
    {
        $statement = $this->pdo->prepare("
            SELECT
            {$this->getFields()}
            FROM {$this->table} as `story`
            WHERE {$this->generateSqlFilters()}
            LIMIT 1
        ");

        $this->bind($statement);

        if ($statement->execute() === false) {
            throw new Exception('ciebit.stories.storages.get_error', 2);
        }

        $storyData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($storyData == false) {
            return null;
        }

        return (new BuilderFromArray)->setData($storyData)->build();
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
            throw new Exception('ciebit.stories.storages.get_error', 2);
        }

        $collection = new Collection;

        $builder = new BuilderFromArray;
        while ($story = $statement->fetch(PDO::FETCH_ASSOC)) {
            $collection->add(
                $builder->setData($story)->build()
            );
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
            `story`.`language`,
            `story`.`languages_references`,
            `story`.`status`
        ';
    }

    public function getTotalRows(): int
    {
        return $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
    }

    public function setStartingItem(int $lineInit): Database
    {
        parent::setOffset($lineInit);
        return $this;
    }

    public function setTable(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    public function setTotalItems(int $total): Database
    {
        parent::setLimit($total);
        return $this;
    }

    /**
     * @throw Exception
    */
    public function update(Story $story): Database
    {
        $statement = $this->pdo->prepare("
            UPDATE `{$this->table}` SET
                `title` = :title,
                `summary` = :summary,
                `body` = :body,
                `datetime` = :datetime,
                `uri` = :uri,
                `views` = :views,
                `language` = :language,
                `status` = :status
            WHERE `id` = :id
            LIMIT 1
        ");

        $statement->bindValue(':title', $story->getTitle(), PDO::PARAM_STR);
        $statement->bindValue(':summary', $story->getSummary(), PDO::PARAM_STR);
        $statement->bindValue(':body', $story->getBody(), PDO::PARAM_STR);
        $statement->bindValue(':datetime', $story->getDateTime()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':uri', $story->getUri(), PDO::PARAM_STR);
        $statement->bindValue(':views', $story->getViews(), PDO::PARAM_INT);
        $statement->bindValue(':status', $story->getStatus(), PDO::PARAM_INT);
        $statement->bindValue(':language', $story->getLanguage(), PDO::PARAM_STR);
        $statement->bindValue(':id', $story->getId(), PDO::PARAM_INT);

        if ($statement->execute() === false) {
            throw new Exception('ciebit.stories.storages.update_error', 3);
        }

        return $this;
    }
}

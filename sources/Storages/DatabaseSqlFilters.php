<?php
declare(strict_types=1);

namespace Ciebit\Stories\Storages;

use PDOStatement;

use function implode;

abstract class DatabaseSqlFilters
{
    private $bindList; #:Array
    private $filersSql; #:Array
    private $startingLine; #:int
    private $totalLines; #:int

    protected function addBind(string $key, int $type, $value): self
    {
        $this->bindList[] = [
            'key' => $key,
            'value' => $value,
            'type' => $type
        ];
        return $this;
    }

    protected function addFilter(string $key, string $sql, int $type, $value): self
    {
        $this->addBind($key, $type, $value);
        $this->addSqlFilter($sql);
        return $this;
    }

    protected function addSqlFilter(string $sql): self
    {
        $this->filersSql[] = $sql;
        return $this;
    }

    protected function bind(PDOStatement $statment): self
    {
        foreach ($this->bindList as $bind) {
            $statment->bindValue(":{$bind['key']}", $bind['value'], $bind['type']);
        }

        return $this;
    }

    protected function generateSqlFilters(): string
    {
        if (empty($this->filtersSql)) {
            return '1';
        }
        return implode(' AND ', $this->filters);
    }

    protected function generateSqlLimit(): string
    {
        $init = (int) $this->startingLine;
        $sql =
            $this->totalLines === null
            ? ''
            : "LIMIT {$init},{$this->totalLines}";
        return $sql;
    }

    protected function setStartingLine(int $lineInit): self
    {
        $this->startingLine = $lineInit;
        return $this;
    }

    protected function setTotalLines(int $total): self
    {
        $this->totalLines = $total;
        return $this;
    }
}

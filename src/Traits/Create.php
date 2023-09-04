<?php

namespace ElePHPant\Traits;

trait Create
{
      /**
     * @param array $data
     * @return int|null
     */
    public function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $stmt = self::$instance->prepare('INSERT INTO ' . self::$table . " ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

            return self::$instance->lastInsertId();
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }
}
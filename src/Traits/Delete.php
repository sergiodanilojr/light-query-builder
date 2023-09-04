<?php

namespace ElePHPant\Traits;

trait Delete
{
    /**
     * @param string $terms
     * @param string|null $params
     * @return bool
     */
    public function delete(string $terms, ?string $params = null): bool
    {
        try {
            $stmt = self::$instance->prepare('DELETE FROM ' . self::$table . " WHERE {$terms}");

            if ($params) {
                $this->parseParams($params);
                $stmt->execute($this->params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }
}
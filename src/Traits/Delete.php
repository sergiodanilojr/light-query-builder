<?php

namespace ElePHPant\Traits;

use ElePHPant\Exceptions\LightQueryBuilderException;

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
            $stmt = self::$instance->prepare('DELETE FROM ' . $this->table . " WHERE {$terms}");

            if ($params) {
                $this->parseParams($params);
                $stmt->execute($this->params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
           throw new LightQueryBuilderException($exception->getMessage());
        }
    }
}
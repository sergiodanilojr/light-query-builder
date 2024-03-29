<?php

namespace ElePHPant\Traits;

use ElePHPant\Exceptions\LightQueryBuilderException;

trait Update
{
     /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    public function update(array $data, string $terms): ?int
    {
        try {
            $dataSet = [];
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }

            $dataSet = implode(", ", $dataSet);

            $stmt = self::$instance->prepare('UPDATE ' . $this->table . " SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $this->params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
           throw new LightQueryBuilderException($exception->getMessage());
        }
    }
}
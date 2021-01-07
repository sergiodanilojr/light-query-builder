<?php

use ElePHPant\LightQueryBuilder;

/**
 * Class MyQueryBuilder
 */
class MyQueryBuilder extends LightQueryBuilder
{
    /**
     * @param string $column
     * @param string|null $condition
     * @return MyQueryBuilder
     */
    public function avg(string $column, ?string $condition): self
    {
        $select = $this->select("AVG({$column})");

        if ($condition) {
            return $select->where($condition);
        }

        return $select;
    }

    /**
     * @param string $columns
     * @param string $condition
     * @return MyQueryBuilder
     */
    public function sum(string $columns, string $condition): self
    {
        $select = $this->select("SUM({$columns})");

        if ($condition) {
            return $select->where($condition);
        }

        return $select;
    }

}
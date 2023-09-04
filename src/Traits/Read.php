<?php

namespace ElePHPant\Traits;

trait Read
{
   
    /**
     * @param string $class
     * @param bool $all
     * @return array|mixed
     */
    public function read(string $class = \stdClass::class, bool $all = false)
    {
        if ($all && $fetch = $this->fetch()) {
            return $fetch->fetchAll(\PDO::FETCH_CLASS, $class);
        }

        $fetch = $this->fetch();

        return $fetch ? $fetch->fetchObject($class) : null;
    }

    /**
     * @return bool|\PDOStatement|null
     */
    private function fetch()
    {
        try {
            $stmt = self::$instance->prepare($this->query . $this->order . $this->limit . $this->offset);

            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            return $stmt;

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }
}
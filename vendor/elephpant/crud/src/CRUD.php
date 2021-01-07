<?php


namespace ElePHPant;


/**
 * Class CRUD
 * @package ElePHPant
 */
class CRUD
{
    /**
     * @var
     */
    private $fail;
    /**
     * @var
     */
    private $query;
    /**
     * @var
     */
    private $params;
    /**
     * @var
     */
    private static $table;

    /**
     * @param string $table
     * @return static
     */
    public static function setTable(string $table)
    {
        self::$table = $table;
        return new static();
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getFail()
    {
        return $this->fail;
    }

    /**
     * @param mixed $query
     * @return CRUD
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        $this->parseParams($params);
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $stmt = Connection::getInstance()->prepare('INSERT INTO ' . self::$table . " ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

            return Connection::getInstance()->lastInsertId();
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

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

            $stmt = Connection::getInstance()->prepare('UPDATE ' . self::$table . " SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $this->params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $terms
     * @param string|null $params
     * @return bool
     */
    public function delete(string $terms, ?string $params = null): bool
    {
        try {
            $stmt = Connection::getInstance()
                ->prepare('DELETE FROM ' . self::$table . " WHERE {$terms}");

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

    /**
     * @param string $key
     * @return int
     */
    public function count(string $key = "id"): int
    {
        $stmt = Connection::getInstance()->prepare($this->query);
        $params = $this->params;

        if (!$params) {
            parse_str($params, $params);
        }

        if ($params && !is_array($params)) {
            parse_str($params, $params);
        }

        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * @return bool|\PDOStatement|null
     */
    private function fetch()
    {
        try {
            $stmt = Connection::getInstance()->prepare($this->query . $this->order . $this->limit . $this->offset);

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

    /**
     * @param $params
     * @return $this
     */
    private function parseParams($params)
    {
        parse_str($params, $this->params);
        return $this;
    }

    /**
     * @param array $data
     * @return array|null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }
}
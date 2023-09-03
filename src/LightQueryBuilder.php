<?php


namespace ElePHPant;


/**
 * Class LightQueryBuilder
 * @package ElePHPant
 */
class LightQueryBuilder
{
    /**
     * INNER JOIN
     */
    public const INNER_JOIN = 'INNER';
    /**
     * LEFT JOIN
     */
    public const LEFT_JOIN = 'LEFT';
    /**
     * RIGHT JOIN
     */
    public const RIGHT_JOIN = 'RIGHT';
    /**
     * FULL JOIN
     */
    public const FULL_JOIN = 'FULL';
    /**
     * CROSS JOIN
     */
    public const CROSS_JOIN = 'CROSS';

    /**
     * @var
     */
    private static $table;
    /**
     * @var
     */
    private $class;
    /**
     * @var string
     */
    protected $query = '';
    /**
     * @var
     */
    protected $params;
    /**
     * @var
     */
    protected $terms;
    /**
     * @var
     */
    protected $columns;

    /**
     * @var
     */
    protected $order;
    /**
     * @var
     */
    protected $limit;
    /**
     * @var
     */
    protected $offset;

    /**
     * @var
     */
    public $fail;

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
     * @param string $class
     * @return $this
     */
    public function setFetchClass(string $class)
    {
        $this->class = $class;
        return $this;
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
     * @param string $columns
     * @return $this
     */
    public function select($columns = '*')
    {
        $this->columns = $columns;
        return $this->toQuery("SELECT {$this->columns} FROM " . self::$table);
    }

    /**
     * @return int
     */
    public function lastId(): int
    {
        return Connection::getInstance()
                ->query('SELECT MAX(id) as maxId FROM ' . self::$table)
                ->fetch()
                ->maxId + 1;
    }

    /**
     * @param string $terms
     * @param string|null $param
     * @return $this
     */
    public function where(string $terms, ?string $param = null): self
    {
        if ($param) {
            $this->params = $param;
        }

        $this->terms = $terms;
        return $this->toQuery(" WHERE {$this->terms}");
    }

    /**
     * @param string $columns
     * @param string $search
     * @param bool $all
     * @return mixed
     */
    public function match(string $columns, string $search, bool $all = true)
    {
        $this->params = "s={$search}";
        return $this
            ->select("MATCH({$columns}) AGAINST(:s)")
            ->get($all);
    }

    /**
     * @return $this
     */
    public function and(string $query)
    {
        return $this->toQuery('AND ' . $query);
    }

    /**
     * @return $this
     */
    public function or(string $query)
    {
        return $this->toQuery('OR ' . $query);
    }

    /**
     * @param string $columns
     * @param string $table
     * @param $condition
     * @param string $type
     * @return LightQueryBuilder
     */
    public function join(string $columns, string $table, $condition, $type = self::INNER_JOIN)
    {
        return $this->select($columns)
            ->toQuery("{$type} JOIN {$table} ON {$condition}");
    }

    /**
     * @param string $firstValue
     * @param string $secondValue
     * @return LightQueryBuilder
     */
    public function between(string $firstValue, string $secondValue)
    {
        return $this->toQuery(" BETWEEN {$firstValue}")
            ->and($secondValue);
    }

    /**
     * @param string $partial
     * @return $this
     */
    public function toQuery(string $partial): self
    {
        $this->query = trim(preg_replace('[\s+]', ' ', $this->query . " {$partial}"));
        return $this;
    }

    /*-----------------------------------------------------------------------------------*/

    /**
     * @param bool $all
     * @return array|mixed|null
     */
    public function get(bool $all = false)
    {
        $class = $this->class ?? \stdClass::class;
        $crud = new CRUD();
        $crud->setQuery($this->query);

        if ($this->params) {
            $crud->setParams($this->params);
        }

        return $crud->read($class, $all);
    }

    /**
     * @param array $data
     * @param string $terms
     * @param $params
     * @return int|null
     */
    public function update(array $data, string $terms, $params)
    {
        $this->terms = $terms;
        $this->params = $params;
        return $this->crud()->update($data, $this->terms);
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function create(array $data)
    {
        return $this->crud()->create($data);
    }

    /**
     * @param string $terms
     * @param string $param
     * @return bool
     */
    public function delete(string $terms, string $param): bool
    {
        $this->params = $param;
        $this->terms = $terms;

        return $this->crud()->delete($this->terms, $this->params);
    }



    /*-----------------------------------------------------------------------------------*/

    /**
     * @param string $key
     * @return int
     */
    public function count($key = 'id'): int
    {
        $crud = $this->crud();

        if ($this->params) {
            $crud->setParams($this->params);
        }

        if ($this->query) {
            $crud->setQuery($this->query);
        }

        return $crud->count($key);
    }

    /**
     * @param string $columnOrder
     * @return $this
     */
    public function order(string $columnOrder)
    {
        $this->order = $columnOrder;
        $this->toQuery(" ORDER BY {$columnOrder}");
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        $this->toQuery(" LIMIT {$this->limit}");
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset)
    {
        $this->offset = $offset;
        $this->toQuery(" OFFSET {$offset}");
        return $this;
    }

    /*-----------------------------------------------------------------------------------*/

    /**
     * @return CRUD
     */
    private function crud()
    {
        $crud = (new CRUD())::setTable(self::$table);
        $crud->setQuery($this->query);

        if ($this->params) {
            $crud->setParams($this->params);
        }

        return $crud;
    }

}
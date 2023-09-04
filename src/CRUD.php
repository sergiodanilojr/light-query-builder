<?php


namespace ElePHPant;

use ElePHPant\Traits\Create;
use ElePHPant\Traits\Delete;
use ElePHPant\Traits\Read;
use ElePHPant\Traits\Update;
use PDO;

/**
 * Class CRUD
 * @package ElePHPant
 */
class CRUD
{
    use Create, Read, Update, Delete;

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
    private $table;


    private $order;

    private $limit;

    private $offset;

   private static ?\PDO $instance = null;

    public function __construct(string $dsn, string $user, string $password, ?array $options)
    {
        self::connect($dsn, $user, $password, $options);
    }

    public static function bootstrap(string $dsn, string $user, string $password, ?array $options)
    {
        return new static($dsn, $user, $password, $options);
    }

    public static function connect(string $dsn, string $user, string $password, ?array $options)
    {
        self::$instance = Connection::instance($dsn, $user, $password, $options);
    }

    /**
     * @param string $table
     * @return static
     */
    public function setTable(string $table)
    {
        $this->table = $table;
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
     * @param string $key
     * @return int
     */
    public function count(string $key = "id"): int
    {
        $stmt = self::$instance->prepare($this->query);
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
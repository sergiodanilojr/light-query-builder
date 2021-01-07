<?php

namespace ElePHPant;

/**
 * Class Connection
 */
class Connection
{
    /**
     *
     */
    private const OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
    ];

    /**
     * @var
     */
    private static $instance;
    /**
     * @var
     */
    private static $configs;

    /**
     * Connection constructor.
     */
    final private function __construct()
    {

    }

    /**
     *
     */
    final private function __clone()
    {
    }

    /**
     * @return \PDO|null
     */
    public static function getInstance(): ?\PDO
    {
        if (empty(self::$instance)) {
            try {
                $config = self::getConfig();
                self::$instance = new \PDO(
                    "mysql:host={$config->host};dbname={$config->name}",
                    $config->user,
                    $config->password,
                    self::OPTIONS
                );

            } catch (\PDOException $exception) {
                return null;
            }

        }
        return self::$instance;
    }


    /**
     * @return object
     */
    private static function getConfig(): object
    {
        self::$configs = new \stdClass();
        self::$configs->host = getenv('DB_HOST');
        self::$configs->user = getenv('DB_USER');
        self::$configs->password = getenv('DB_PASSWORD');
        self::$configs->name = getenv('DB_NAME');

        return self::$configs;
    }
}
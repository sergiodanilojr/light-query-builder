<?php

namespace ElePHPant;


final class Connection
{

    private static ?\PDO $instance = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function instance(string $dsn, string $user, string $password, ?array $options): \PDO
    {
        if (!self::$instance) {
            self::$instance = self::connection(
                $dsn, $user, $password, (!empty($options)?$options:null)
            );
        }

        return self::$instance;
    }

    private static function connection(string $dsn, string $user, string $password, ?array $options): \PDO
    {
        return new \PDO(
            $dsn,
            $user,
            $password,
            $options
        );
    }
}
<?php

namespace components;

use PDO;

/**
 * Класс Db
 * Возвращает объект PDO
 * @property PDO $db
 */
class Db
{
    public PDO $db;

    /**
     * Устанавливаем объект PDO
     */
    public function __construct()
    {
        $this->db = $this->setDb();
    }

    /**
     * Инициализируем объект PDO
     * @return PDO
     */
    public function setDb(): PDO
    {
        return new PDO(
            "mysql:host=mysql;dbname=shopfans",
            'root',
            'password'
        );
    }

    /**
     * Возвращает объект PDO
     * @return PDO
     */
    public function getDb(): PDO
    {
        return $this->db;
    }
}
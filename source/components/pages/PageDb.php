<?php

namespace components\pages;

use components\Db;
use components\TextToHtml;
use PDO;

/**
 * Класс PageDb
 * Представляет функционал для работы со страницами, хранящимися в БД
 */
class PageDb extends Page
{
    /**
     * Возвращает найденную страницу
     * @param $uri
     * @return array|null
     */
    public static function find($uri)
    {
        $db = self::getDb();
        $query = $db->prepare(self::getSql());
        $query->bindParam(':search', $uri);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Возвращает sql запрос для поиска
     * @return string
     */
    private static function getSql(): string
    {
        return "
            SELECT page.* FROM link
            LEFT JOIN page ON link.page_id = page.id
            WHERE link = :search
        ";
    }

    /**
     * Получаем объект PDO
     * @return PDO
     */
    private static function getDb(): \PDO
    {
        return (new Db)->getDb();
    }
}
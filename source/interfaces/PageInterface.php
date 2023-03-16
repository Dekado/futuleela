<?php

namespace interfaces;

/**
 * Интерфейс страницы
 */
interface PageInterface
{
    /**
     * Метод поиска данных
     * @return mixed
     */
    public static function find($uri);

    /**
     * Преобразование контента в html
     * @return mixed
     */
    public static function contentToHtml($content);
}
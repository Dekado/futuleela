<?php

namespace components\pages;

use components\TextToHtml;
use interfaces\PageInterface;

class Page implements PageInterface
{
    const MIME_TEXT = 'text/plain';
    const MIME_HTML = 'text/html';

    /**
     * Получаем контент страницы
     * @param $uri
     * @return string
     */
    public static function getContent($uri): string
    {
        return self::content(self::find($uri));
    }

    /**
     * Ищем страницу
     * @param $uri
     * @return array|false|mixed|null
     */
    public static function find($uri)
    {
        $page = glob(self::getViewsPath($uri));
        if(!$page) {
            $page = PageDb::find($uri);
        }

        return $page;
    }

    /**
     * Проверяем mime тип
     * @param string $mimeType
     * @return bool
     */
    protected static function checkMimeTypeIsText(string $mimeType): bool
    {
        return ($mimeType == self::MIME_TEXT);
    }

    /**
     * Получаем адрес для поиска файла
     * @param string $uri
     * @return string
     */
    private static function getViewsPath(string $uri): string
    {
        return __DIR__ . "/../../views/$uri.*";
    }

    /**
     * Получаем контент страницы
     * @param $page
     * @return string
     */
    private static function content($page): string
    {
        if(isset($page[0])) {
            $mime = mime_content_type($page[0]);
            $content = file_get_contents($page[0]);
        } else {
            $mime = $page['mime'];
            $content = $page['text'];
        }

        if(self::checkMimeTypeIsText($mime)) {
            $content = self::contentToHtml($content);
        }

        return $content;
    }

    /**
     * Преобразуем текст в html
     * @param $content
     * @return mixed|string
     */
    public static function contentToHtml($content)
    {
        return TextToHtml::convertToHTML($content);
    }

    /**
     * Получаем title
     * @param $content
     * @return mixed
     */
    public static function getTitle($content)
    {
        preg_match('/<h1>(.+)<\/h1/', $content, $title);

        return $title[1] ?? '';
    }

}
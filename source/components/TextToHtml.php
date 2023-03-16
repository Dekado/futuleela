<?php

namespace components;

/**
 * Класс TextToHtml
 * Преобразует маркированный текст в html
 * @property string $content
 */
class TextToHtml
{
    public static string $content;
    public static array $paragraphs;
    public static array $headingsPlaceholder = [
        2 => '##',
        3 => '###',
        4 => '####',
        5 => '#####',
        6 => '######',
    ];

    /**
     * Преобразовываем текст в html
     * @param $text
     * @return string
     */
    public static function convertToHTML($text): string
    {
        self::$content = $text;
        foreach (self::splitIntoParagraphs() as $key => $paragraph) {
            self::$paragraphs[$key] = self::convertUrls(self::$paragraphs[$key]);
            self::$paragraphs[$key] = self::convertMainHeaders(self::$paragraphs[$key]);
            self::$paragraphs[$key] = self::convertHeadings(self::$paragraphs[$key]);
            self::$paragraphs[$key] = self::convertLists(self::$paragraphs[$key]);
        }

        self::envelopeParagraphs();
        return self::assemblePage();
    }

    /**
     * Разбиваем текст на параграфы
     * @return array
     */
    private static function splitIntoParagraphs(): array
    {
        return self::$paragraphs = preg_split('/(\r?\n){2}/', self::$content);
    }

    /**
     * Обрабатываем ссылки
     * @param string $paragraph
     * @return string
     */
    private static function convertUrls(string $paragraph) : string
    {
        $paragraph =  preg_replace('/(https?:\/\/\S+)/', '<a href="$1">$1</a>', $paragraph);
        return preg_replace('/(\S+@\S+\.\S+)/', '<a href="mailto:$1">$1</a>', $paragraph);
    }

    /**
     * Ищем H1
     * @param string $paragraph
     * @return string
     */
    private static function convertMainHeaders(string $paragraph): string
    {
        $paragraph = preg_replace('/(.+)\n=+/', "<h1>$1</h1>", $paragraph);
        return preg_replace('/(.+)\n--+/', "<h1>$1</h1>", $paragraph);
    }

    /**
     * Обрабатываем заголовки
     * @param string $paragraph
     * @return string
     */
    private static function convertHeadings(string $paragraph): string
    {
        foreach (self::$headingsPlaceholder as $key => $placeholder) {
            $paragraph = preg_replace('/' .$placeholder. '\s(.*)/', "<h$key>$1</h$key>", $paragraph);
        }

        return $paragraph;
    }

    /**
     * Обрабатываем списки
     * @param string $paragraph
     * @return array|string|string[]|null
     */
    private static function convertLists(string $paragraph)
    {
        $paragraph = preg_replace("/\*+(.*)?/i","<ul><li>$1</li></ul>",$paragraph);
        return preg_replace("/(\<\/ul\>\n(.*)\<ul\>*)+/","",$paragraph);
    }

    /**
     * Оборачиваем в тег <p>
     * @return void
     */
    private static function envelopeParagraphs()
    {
        self::$paragraphs = array_map('trim', self::$paragraphs);
        self::$paragraphs = array_filter(self::$paragraphs);
        self::$paragraphs = array_map(function($p) {
            return "<p>$p</p>";
        }, self::$paragraphs);
    }

    /**
     * Собираем контент из массива
     * @return string
     */
    private static function assemblePage(): string
    {
        return self::$content = implode('', self::$paragraphs);;
    }
}
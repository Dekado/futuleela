<?php

namespace components;


use components\pages\Page;

/**
 * Класс App
 * Основной класс приложения
 * @property string $uri Адрес запроса
 */
class App
{
    public string $uri;
    public string $pageContent;
    public int $timeStart;

    /**
     * Инициализируем свойство $uri
     */
    public function __construct()
    {
        $this->timeStart = microtime(true);
        $this->uri = str_ireplace('/', '', $_SERVER['REQUEST_URI']);
    }

    /**
     * Выводим время выполнения
     */
    public function __destruct()
    {
        echo microtime(true) - $this->timeStart;
    }

    /**
     * Запускаем приложение
     */
    public function run(): string
    {
        return $this->render($this->findPage());
    }

    /**
     * Ищем страницу
     * @return string
     */
    private function findPage(): string
    {
        return $this->pageContent = Page::getContent($this->uri);
    }

    /**
     * Возвращает html страницу
     * @param string $pageContent
     * @return string
     */
    private function render(string $pageContent)
    {
        $html = $this->getHeader(Page::getTitle($pageContent));
        $html .= $this->getContent($pageContent);
        $html .= $this->getFooter();
        return $html;
    }

    /**
     * Получаем шапку документа
     * @param string $title
     * @return string
     */
    private function getHeader(string $title = ''): string
    {
        ob_start();
        require_once(__DIR__ . '/../views/layouts/header.php');
        return ob_get_contents();
    }

    /**
     * Получаем футер документа
     * @return string
     */
    private function getFooter(): string
    {
        ob_start();
        require_once(__DIR__ . '/../views/layouts/footer.php');
        return ob_get_contents();
    }

    /**
     * Обрабытываем контент
     * @param $pageContent
     * @return string
     */
    private function getContent($pageContent): string
    {
        ob_start();
        echo $pageContent;
        return ob_get_contents();
    }


}
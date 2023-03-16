<?php

namespace app\router;

class Router
{
    private string $url = '/';

    /**
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function run()
    {

    }
}
<?php

namespace App\Utils;

class View
{
    /**
     * @var array $args
     */
    private static $args = [];

    /**
     * Send data as BASE_URL to views
     * @param array $args
     * @return void
     */
    public static function init($args = []): void
    {
        self::$args = $args;
    }

    /**
     * Get content of view
     * @param string $view
     * @return string
     */
    private static function getContentView($view): string
    {
        $file = __DIR__.'/../../resources/views/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Render a view
     * @param string $views
     * @param array $data
     * @return string
     */
    public static function render($view, $data = []): string
    {
        $contentView = self::getContentView($view);

        $args = array_merge(self::$args, $data);

        $keys = array_keys($args);

        $map = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        return str_replace($map, array_values($args), $contentView);
    }
}
<?php

namespace App\Utils;

class View
{
    private static $args = [];

    public static function init($args = [])
    {
        self::$args = $args;
    }

    private static function getContentView($view)
    {
        $file = __DIR__.'/../../resources/views/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    public static function render($view, $data = [])
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
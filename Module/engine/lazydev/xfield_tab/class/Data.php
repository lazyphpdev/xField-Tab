<?php
/**
 * Конфиг и языковый файл
 *
 * @link https://lazydev.pro/
 * @author LazyDev <email@lazydev.pro>
 **/

namespace LazyDev\xFieldTab;

class Data
{
    static private $data = [];

    /**
     * Загрузить конфиг и языковый пакет
     */
    static function load()
    {
        self::$data['config'] = include_once realpath(__DIR__ . '/..')  . '/data/config.php';
        self::$data['lang'] = include_once realpath(__DIR__ . '/..')  . '/data/lang.lng';
    }

    /**
     * Вернуть массив данных
     *
     * @param   string  $key
     * @return  array
     */
    static function receive($key)
    {
        return self::$data[$key];
    }

    /**
     * Получить данные с массива по ключу
     *
     * @param    string|array   $key
     * @param    string         $type
     * @return   mixed
     */
    static public function get($key, $type)
    {
        if (is_array($key)) {
            return self::multiArray(self::$data[$type], $key, count($key));
        }

        return self::$data[$type][$key];
    }

	/**
    * Получить данные с массива по массиву ключей
    *
    * @param    array   $a
    * @param    array   $k
    * @param    int     $c
    * @return   string
    **/
    static public function multiArray($a, $k, $c)
    {
        return ($c > 1) ? self::multiArray($a[$k[count($k) - $c]], $k, ($c - 1)) : $a[$k[(count($k) - 1)]];
    }
	
}
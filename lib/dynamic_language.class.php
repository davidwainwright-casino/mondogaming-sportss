<?php

/**
 * Language
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */


if (!defined("_YOYO"))
    die('Direct access to this location is not allowed.');


final class Dynamic_Lang
{
    const langdir = "lang/";
    public static $language;
    public static $word = array();
    public static $lang;

    /**
     * Lang::__construct()
     *
     * @return
     */
    public function __construct()
    {
    }


    /**
     * Lang::Run()
     *
     * @return
     */
    public static function Run()
    {
        self::get();
    }

    /**
     * @param $name
     * @return string
     */
    public static function Key($name) {
        return strtoupper(str_replace(' ', '_', $name));
    }

    /**
     * Lang::get()
     *
     * @return
     */
    private static function get()
    {
        if (!isset($_COOKIE['LANG_CMSPRO'])) $_COOKIE['LANG_CMSPRO'] = 'en';

        $lang_file = "../../lang/{$_COOKIE['LANG_CMSPRO']}/dynamic/lang.de";

        self::$word = array();

        if (file_exists($lang_file)) {
            self::$word = file_get_contents($lang_file);
            self::$word = unserialize(gzuncompress(self::$word));
        }
        else {
            $storage_lang = gzcompress(serialize(self::$word));
            file_put_contents($lang_file, $storage_lang);
        }

        return self::$word;
    }

}
<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_emoticons_array'))
{
    /**
     * Get Emoticons Array
     *
     * Fetches the config/emoticons.php file
     *
     * @return  mixed
     */
    function get_emoticons_array()
    {
        static $_emoticons;

        if ( ! is_array($_emoticons))
        {
            if (file_exists(APPPATH.'config/emoticons.php'))
            {
                include(APPPATH.'config/emoticons.php');
            }

            if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/emoticons.php'))
            {
                include(APPPATH.'config/'.ENVIRONMENT.'/emoticons.php');
            }

            if (empty($emoticons) OR ! is_array($emoticons))
            {
                $_emoticons = array();
                return FALSE;
            }

            $_emoticons = $emoticons;
        }

        return $_emoticons;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 08.11.19
 * Time: 20:28
 */

require_once 'WpdbOptInterface.php';

class wpdb implements WpdbOptInterface
{

    public $prefix = 'test_';

    public const CHARSET_COLLATE = 'DEFAULT CHARACTER SET UTF-8';

    /**
     * @return string
     */
    public function get_charset_collate()
    {
        return self::CHARSET_COLLATE;
    }
}
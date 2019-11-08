<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 08.11.19
 * Time: 20:28
 */

/**
 * Interface WpdbOptInterface
 * @property string $prefix
 */
interface WpdbOptInterface
{
    /**
     * @return string
     */
    public function get_charset_collate();
}
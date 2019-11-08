<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 08.11.19
 * Time: 20:22
 */

define('PLUGIN_ROOT', __DIR__ . '/../');
define('TEST_ROOT', __DIR__);

require_once PLUGIN_ROOT . 'opt/load.php';

/*
 * Ofc, no autoloader because wordpress doesn't use it :(
 */
require_once TEST_ROOT . '/Fakes/wpdb/WpdbOptInterface.php';
require_once TEST_ROOT . '/Fakes/wpdb/wpdb.php';

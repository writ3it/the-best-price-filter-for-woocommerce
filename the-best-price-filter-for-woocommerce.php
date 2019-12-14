<?php

/**
 * Plugin Name:       The Best Price Filter for Woocommerce
 * Plugin URI:        https://github.com/writ3it/the-best-price-filter-for-woocommerce
 * Description:       Woocommerce implements price filter thats cannot search product variation individually! This plugin is a solution.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            writ3it
 * Author URI:        https://github.com/writ3it
 * License:           MIT
 * License URI:       https://github.com/writ3it/the-best-price-filter-for-woocommerce/blob/master/LICENSE
 */

define('TBWPF', __FILE__);
define('__TBWPF_VERSION', 2);

/** @var wpdb $wpdb */

//migration
/** @var tbwpf_Migrations $migration */
$migration = require_once __DIR__ . '/Migrations.php';
$migration->verify();

//opt
require_once __DIR__ . '/opt/load.php';

//plugin (de)activation hook
require_once __DIR__ . '/install.php';

//price updates
require_once __DIR__ . '/PriceUpdater.php';
$updater = new tbwpf_PriceUpdater();

//fix
require_once __DIR__ . '/wc-query-fixer.php';

$migration->postProcess();
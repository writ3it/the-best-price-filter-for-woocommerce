<?php

/**
 * Plugin Name:       Woocommerce The Best Price Filter
 * Plugin URI:        https://github.com/writ3it/wordpress-the-best-woo-price-filter
 * Description:       Woocommerce implements price filter thats cannot search product variation individually! This plugin is a solution.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            writ3it
 * Author URI:        https://github.com/writ3it
 * License:           MIT
 * License URI:       https://github.com/writ3it/wordpress-the-best-woo-price-filter/blob/master/LICENSE
 */

define('TBWPF', __FILE__);

//plugin (de)activation hook
require_once __DIR__ . '/install.php';

//price updates
require_once __DIR__.'/PriceUpdater.php';
$updater = new PriceUpdater();

//fix
require_once __DIR__.'/wc-query-fixer.php';
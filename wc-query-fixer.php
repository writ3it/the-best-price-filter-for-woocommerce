<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 20:48
 */

add_filter('posts_clauses', 'tbwpf_price_filter_post_clauses', 9999, 2);

function tbwpf_price_filter_post_clauses($args, $wp_query)
{
    global $wpdb;
    if (!$wp_query->is_main_query() || (!isset($_GET['max_price']) && !isset($_GET['min_price']))) {
        return $args;
    }
    $tableName = tbwpf_OptProperties::tableNameFor($wpdb, 'float');
    $args['join'] .= "\n LEFT JOIN {$tableName} opt_price ON {$wpdb->prefix}posts.ID = opt_price.post_id ";
    $args['where'] = preg_replace(
        '/AND [a-z_\.]+(\s*>=\s*[0-9\.]+)\s+AND\s+[a-z_\.]+(\s*<=\s[0-9\.]+)/mi',
        'AND opt_price.property="_price" AND opt_price.value $1 AND opt_price.value $2',
        $args['where']);
    return $args;
}

add_action('wc_update_product_lookup_tables_column', 'tbwpf_regenerate');

function tbwpf_regenerate($column)
{
    global $wpdb;

    if ($column != 'min_max_price') {
        return;
    }
    $floatTable = new tbwpf_OptFloatProperties($wpdb);

    if (!$floatTable->isCreated()) {
        return;
    }

    $floatTable->clearAndRegenerate(['_price']);
}
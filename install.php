<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 18.10.19
 * Time: 20:23
 */

function tbwpf_tableName()
{
    global $wpdb;
    return $wpdb->prefix . 'opt_properties_float';
}

function tbwpf_create_table()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $tableName = tbwpf_tableName();
    $postTableName = $wpdb->prefix . 'posts';

    $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` bigint(20) UNSIGNED NOT NULL,
    `property` varchar(255) NOT NULL ,
    `value` float NOT NULL,
    PRIMARY KEY(`id`),
    INDEX(`property`),
    FOREIGN KEY(`post_id`) REFERENCES $postTableName(`ID`),
    INDEX( `post_id`, `property`, `value`)
)  $charset_collate";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

}

function tbwpf_filldata()
{
    global $wpdb;
    $tableName = tbwpf_tableName();
    $wpdb->query(
            "DELETE FROM $tableName
		 WHERE 1
		"
    );
    $wpdb->query(
        "   INSERT INTO  $tableName (`post_id`,`property`,`value`)
            SELECT post_id, meta_key, CAST(meta_value AS DECIMAL(10,6)) 
            FROM {$wpdb->prefix}postmeta pm
            WHERE pm.meta_key = '_price'
		"
    );
}


function tbwpf_activation()
{
    global $wpdb;
    $tableName = tbwpf_tableName();
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
        tbwpf_create_table();
    }

    tbwpf_filldata();
}

register_activation_hook(TBWPF, 'tbwpf_activation');
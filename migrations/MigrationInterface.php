<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 14.12.19
 * Time: 19:31
 */

interface tbwpf_MigrationInterface
{

    /**
     * WPDB dependency
     * @param wpdb $db
     * @return void
     */
    public function setDb($db);

    /**
     * Run migration
     * @return void
     */
    public function run();
}
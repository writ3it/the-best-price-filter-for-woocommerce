<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 14.12.19
 * Time: 19:31
 */

class tbwpf_MigrateTo2 implements tbwpf_MigrationInterface
{
    /**
     * @var wpdb
     */
    private $db;

    /**
     * WPDB dependency
     * @param wpdb $db
     * @return void
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Run migration
     * @return void
     */
    public function run()
    {
        $prefix = $this->db->prefix;
        $this->db->query("ALTER TABLE `{$prefix}opt_properties_float` MODIFY `value` DECIMAL(10,2)");
    }
}
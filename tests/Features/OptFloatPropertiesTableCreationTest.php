<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 08.11.19
 * Time: 20:20
 */
class OptPropertiesTableCreationTest extends TestCase
{
    /**
     * Issue: https://wordpress.org/support/topic/sql-error-on-install-with-table-creation/
     * @PHP(7.3)
     */
    public function test_type_of_value_column()
    {
        $wpdb = new wpdb();
        $floatProperties = new tbwpf_OptFloatProperties($wpdb);
        $sql = $floatProperties->getCreationSQL();
        $this->assertFalse(strpos($sql,'undefined'));
    }
}
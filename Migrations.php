<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 14.12.19
 * Time: 19:16
 */

require_once __DIR__ . '/migrations/MigrationInterface.php';

class tbwpf_Migrations
{
    private const OPTION_VERSION_NAME = '__tbwpf_version';
    /**
     * @var int
     */
    private $current_version;
    /**
     * @var wpdb
     */
    private $db;

    /**
     * tbwpf_Migrations constructor.
     * @param wpdb $wpdb
     */
    public function __construct($wpdb)
    {
        $this->db = $wpdb;
        $this->current_version = (int)get_option(self::OPTION_VERSION_NAME, 1);
    }

    public function verify()
    {
        if ($this->current_version >= __TBWPF_VERSION) {
            return;
        }
        $this->runMigrations();
        update_option(self::OPTION_VERSION_NAME, __TBWPF_VERSION);
        $this->current_version = __TBWPF_VERSION;
    }

    private function runMigrations()
    {
        for ($version = $this->current_version + 1; $version <= __TBWPF_VERSION; $version++) {
            $this->runMigration($version);
        }
    }

    private function runMigration($version)
    {
        $fileName = __DIR__ . "/migrations/MigrateTo{$version}.php";
        $className = "tbwpf_MigrateTo{$version}";
        if (!file_exists($fileName)) {
            return;
        }
        require_once $fileName;
        if (!class_exists($className)) {
            return;
        }
        /** @var tbwpf_MigrationInterface $migration */
        $migration = new $className();
        $migration->setDb($this->db);
        $migration->run();
    }
}

return new tbwpf_Migrations($wpdb);
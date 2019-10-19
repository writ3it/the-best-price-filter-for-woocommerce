<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 21:48
 */

class OptProperties
{
    protected $type = OptPropertyTypes::UNDEFINED;
    protected $sqlType = 'undefined';
    protected $sqlCast = 'undefined';
    protected $cast = 'floatval';


    /**
     * @var wpdb
     */
    private $db;

    /**
     * OptProperties constructor.
     * @param wpdb $wpdb
     */
    public function __construct(wpdb $wpdb)
    {
        $this->db = $wpdb;
    }

    /**
     * Creates table
     */
    public function create()
    {
        $charset_collate = $this->db->get_charset_collate();
        $tableName = $this->getTableName();
        $type = $this->getSqlType();
        $postTableName = $this->db->prefix . 'posts';

        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `post_id` bigint(20) UNSIGNED NOT NULL,
                    `property` varchar(255) NOT NULL ,
                    `value` {$type},
                    PRIMARY KEY(`id`),
                    INDEX(`property`),
                    FOREIGN KEY(`post_id`) REFERENCES $postTableName(`ID`),
                    INDEX( `post_id`, `property`, `value`)
                )  $charset_collate";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    /**
     * Returns table name
     * @return string
     */
    public function getTableName()
    {
        return static::tableNameFor($this->db, $this->type);
    }

    public static function tableNameFor(wpdb $db, $type)
    {
        return $db->prefix . 'opt_properties_' . $type;
    }

    /**
     * SQL column type
     * @return string
     */
    public function getSqlType()
    {
        return $this->sqlType;
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        $tableName = $this->getTableName();
        return $this->db->get_var("SHOW TABLES LIKE '$tableName'") == $tableName;
    }

    /**
     * Clears and fills property table with postmeta data
     * @param $types
     */
    public function clearAndRegenerate($types)
    {
        $this->removeAll();
        $tableName = $this->getTableName();
        $castType = $this->getSqlCastType();
        foreach ($types as $type) {
            $this->db->query("INSERT INTO  $tableName (`post_id`,`property`,`value`)
            SELECT post_id, meta_key, CAST(meta_value AS {$castType}) 
            FROM {$this->db->prefix}postmeta pm
            WHERE pm.meta_key = '{$type}'");
        }
    }

    /**
     * Removes all properties
     */
    public function removeAll()
    {
        $tableName = $this->getTableName();
        $this->db->query("DELETE FROM $tableName WHERE 1");
    }

    /**
     * Returns sql cast type
     * @return mixed
     */
    public function getSqlCastType()
    {
        return $this->sqlCast;
    }

    /**
     * @param int $postId
     * @param string $property
     * @param array $currentValues
     */
    public function removeUselessValues($postId, $property, $currentValues)
    {
        $optPricesString = implode(',', $currentValues);
        $tableName = $this->getTableName();
        $this->db->query($this->db->prepare(
            "DELETE FROM {$tableName} WHERE post_id=%d AND property='%s' AND `value` NOT IN ({$optPricesString})",
            $property,
            $postId
        )
        );
    }

    /**
     * Returns values
     * @param int $productId
     * @param string $property
     * @return array
     */
    public function getValues($productId, $property)
    {
        $tableName = $this->getTableName();
        $values = $this->db->get_results($this->db->prepare("SELECT `value` FROM {$tableName} WHERE post_id=%d AND property='%s'", $productId, $property));
        $arrayValues = array_map(function ($row) {
            return $row->value;
        }, $values);
        $function = $this->getCastFunction();
        return array_map($function, $arrayValues);
    }

    public function getCastFunction()
    {
        return $this->cast;
    }

    /**
     * @param int $postId
     * @param string $property
     * @param array $valuesToAdd
     */
    public function insert($postId, $property, $valuesToAdd)
    {
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} (`post_id`,`property`,`value`) VALUES ";
        foreach ($valuesToAdd as $value) {
            $escapedValue = $this->db->_real_escape($value);
            $sql .= " ({$postId}, '$property', '{$escapedValue}'),";
        }
        $sql = trim($sql, ',');
        $this->db->query($sql);
    }
}
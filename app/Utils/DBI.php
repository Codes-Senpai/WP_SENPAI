<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    /**
	 * DBI helper class it's used to speedup interact with WordPress DataBase while activate/deactivate theme or plugin.
	 * @category Class
	 * @author amine safsafi
	 */
class DBI {
	/**
	 * @ignore
	 */
    var $senpai_db;
	/**
	 * @ignore
	 */
    var $senpai_prefix;
    /**
	 * @ignore
	 */
    var $senpai_charset;


	/**
	 * $senpai_db = new \WP_SENPAI\Utils\DB_INIT('senpai');
	 * @param string $prefix
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($prefix){
        global $wpdb;
        $this->senpai_db = $wpdb;
        $this->senpai_prefix = $wpdb->prefix . $prefix . '_';
        $this->senpai_charset = $wpdb->get_charset_collate();
    }
    
    /**
	 * $table_name = 'test';
	 * $colums_sql = "id mediumint(11) NOT NULL AUTO_INCREMENT,
	 * name varchar(30),
	 * PRIMARY KEY  (id)";
     * $senpai_db->create_table($table_name,$colums_sql);
	 * @param string $table_name
     * @param string $colums_sql
	 * @author amine safsafi
	 * @return void
	 */
    public function create_table($table_name,$colums_sql){
        $table = $this->senpai_prefix . $table_name;
        $charset = $this->senpai_charset;
        $sql = "CREATE TABLE $table ($colums_sql) $charset;";
        maybe_create_table( $table, $sql );
    }
    /**
	 * $table_name = 'test';
     * $senpai_db->drop_table($table_name);
	 * @param string $table_name
	 * @author amine safsafi
	 * @return void
	 */
    public function drop_table($table_name){
        $table = $this->senpai_prefix . $table_name;
        $sql = "DROP TABLE IF EXISTS $table";
        $this->senpai_db->query($sql);
    }
    
}
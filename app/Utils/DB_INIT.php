<?php
namespace WP_SENPAI\Utils;

//if ( !defined( 'WPINC' ) ) {die();}

    /**
	 * DB_INIT helper class it's used to speedup interact with WordPress DataBase while activate/deactivate theme or plugin.
	 * @category Class
	 * @author amine safsafi
	 */

class DB_INIT {
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
	 * $senpai_db = \WP_SENPAI\Utils\DB_INIT('senpai');
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

    public function create_table($table_name,$colums_sql){
        $table = $this->senpai_prefix . $table_name;
        $charset = $this->senpai_charset
        $sql = "CREATE TABLE $table ($colums_sql) $charset;";
        maybe_create_table( $table, $sql );
    }

    public function drop_table($table_name){
        $table = $this->senpai_prefix . $table_name;
        $sql = "DROP TABLE IF EXISTS $table";
        $this->senpai_db->query($sql);
    }
    
}
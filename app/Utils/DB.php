<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * DB helper class it's used to speedup interact with WordPress DataBase.
	 * @category Class
	 * @author amine safsafi
	 */
class DB {
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
    var $senpai_remote;

	/**
	 * $senpai_db = \WP_SENPAI\Utils\DB($prefix,$db_obj);
	 * @param string $prefix
	 * @param object $db
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($prefix,$db = NULL){
		if($db == NULL){
			global $wpdb;
			$this->senpai_db = $wpdb;
			$this->senpai_prefix = $wpdb->prefix . $prefix . '_';
			$this->senpai_remote = false;
		}else{
			$remote_db = new wpdb($db->username,$db->password,$db->database_name,$db->host);
			$this->senpai_db = $remote_db;
			$this->senpai_remote = true;
		}
    }

	/**
	 * $data = $senpai_db->get_all('senpai_table');
	 * @param string $table_name
	 * @return array
	 */
    public function get_all($table_name){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_results( "SELECT * FROM $table_name" );
	}

	/**
	 * $val = $senpai_db->get_value('senpai_table','senpai_col', id);
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $id
	 * @return string
	 */
    public function get_value($table_name,$column_name,$id){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_var( $wpdb->prepare("SELECT %s FROM %s WHERE id='%s'",array($column_name,$table,$id)));
	}


}
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
	 * $senpai_db = new \WP_SENPAI\Utils\DB(prefix,db_obj);
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
	 * $data = $senpai_db->get_all(table_name);
	 * @param string $table_name
	 * @return array 
	 */
    public function get_all($table_name){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_results( "SELECT * FROM $table" );
	}

	/**
	 * $val = $senpai_db->get_val(senpai_table,senpai_col, id);
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $id
	 * @return string
	 */
    public function get_val($table_name,$column_name,$id){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_var( $this->senpai_db->prepare("SELECT %s FROM %s WHERE id='%s'",array($column_name,$table,$id)));
	}

	/**
	 * $row = $senpai_db->get_row(senpai_table, id);
	 * @param string $table_name
	 * @param string $id
	 * @return array
	 */
    public function get_row($table_name,$id){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_row( "SELECT * FROM $table WHERE id='$id'" );
	}

	/**
	 * $column = $senpai_db->get_col(senpai_table, column_name);
	 * @param string $table_name
	 * @param string $column_name
	 * @return array
	 */
    public function get_col($table_name,$column_name){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_col( "SELECT $column_name FROM $table" );
	}

	/**
	 * $last_id = $senpai_db->insert(table_name, data);
	 * @param string $table_name
	 * @param array $data
	 * @return int
	 */
    public function insert($table_name,$data){
		$table = $this->senpai_prefix . $table_name;
		$this->senpai_db->insert( $table, $data );
		return $this->senpai_db->insert_id;
	}

	/**
	 * $last_id = $senpai_db->update(table_name, data, id);
	 * @param string $table_name
	 * @param array $data
	 * @param array $id
	 * @return void
	 */
    public function update($table_name,$data,$id){
		$table = $this->senpai_prefix . $table_name;
		$this->senpai_db->update( $table, $data, array( 'id' => $id ));
	}

}
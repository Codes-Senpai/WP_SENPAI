<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * DB helper class it's used to interact with WordPress DataBase Table.
	 * 
	 * ```
	 * $remote_db = (object)['username'=>'','password'=>'','database_name'=>'','host'=>''];
	 * $senpai_db = new \WP_SENPAI\Utils\DB('senpai');
	 * $val = $senpai_db->get_val('senpai_table','senpai_col', 'id', 1, '%d');
	 * $row = $senpai_db->get_row(senpai_table, 'id', 1, '%d');
	 * $column = $senpai_db->get_col('senpai_table', 'column_name');
	 * ```
	 * 
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
	 * Initiate DB connection
	 * 
	 * ```
	 * $remote_db = (object)['username'=>'','password'=>'','database_name'=>'','host'=>''];
	 * $senpai_db = new \WP_SENPAI\Utils\DB('senpai');
	 * 
	 * ```
	 * 
	 * @param string $prefix
	 * @param object $db {username,password,database_name,host}
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($prefix ='',$db = NULL){
		if($db == NULL){
			global $wpdb;
			$this->senpai_db = $wpdb;
			if($prefix){
				$this->senpai_prefix = $wpdb->prefix . $prefix . '_';
			}	
			$this->senpai_remote = false;
		}else{
			$remote_db = new wpdb($db->username,$db->password,$db->database_name,$db->host);
			$this->senpai_db = $remote_db;
			$this->senpai_remote = true;
		}
    }

	/**
	 * get all rows
	 * 
	 * ```
	 * $data = $senpai_db->get_all(table_name);
	 * ```
	 * 
	 * @param string $table_name
	 * @return array 
	 */
    public function get_all($table_name){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_results( "SELECT * FROM $table" );
	}

	/**
	 * Get single value
	 * 
	 * The following placeholders can be used in the type string: %d(integer) %f(float) %s(string)
	 * 
	 * ```
	 * $val = $senpai_db->get_val(senpai_table,senpai_col, 'id', 1, '%d');
	 * ```
	 * 
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $target
	 * @param string $value
	 * @param string $type
	 * @return string
	 */
    public function get_val($table_name,$column_name,$target,$value, $type = "%s"){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_var( $this->senpai_db->prepare("SELECT {$column_name} FROM `$table` WHERE $target='$type'",$value));
	}

	/**
	 * get single Row
	 * 
	 * The following placeholders can be used in the type string: %d(integer) %f(float) %s(string)
	 * ```
	 * $row = $senpai_db->get_row(senpai_table, 'id', 1, '%d');
	 * ```
	 * 
	 * @param string $table_name
	 * @param string $target
	 * @param string $value
	 * @param string $type
	 * @return array
	 */
    public function get_row($table_name,$target,$value, $type = "%s"){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_row( $this->senpai_db->prepare("SELECT * FROM {$table} WHERE $target='$type'",$value));
	}

	/**
	 * Get Single colum
	 * 
	 * 
	 * ```
	 * $column = $senpai_db->get_col(senpai_table, column_name);
	 * ```
	 * 
	 * @param string $table_name
	 * @param string $column_name
	 * @return array
	 */
    public function get_col($table_name,$column_name){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->get_col( $this->senpai_db->prepare("SELECT %s FROM $s",$column_name,$table) );
	}

	/**
	 * Get last inserted ID
	 * 
	 * ```
	 * $last_id = $senpai_db->insert(table_name, data);
	 * ```
	 * 
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
	 * Update values by Row ID
	 * 
	 * ```
	 * $senpai_db->update(table_name, data, id);
	 * ```
	 * 
	 * @param string $table_name
	 * @param array $data
	 * @param string $target
	 * @param mixed $value
	 * @return void
	 */
    public function update($table_name,$data,$target,$value){
		$table = $this->senpai_prefix . $table_name;
		return $this->senpai_db->update( $table, $data, array( $target => $value ));
	}

	/**
	 * Delete Row by Cell condition
	 * 
	 * ```
	 * $senpai_db->delete(table_name, target_column, value);
	 * ```
	 * 
	 * @param string $table_name
	 * @param string $target_column
	 * @param string $value
	 * @return void
	 */
    public function delete($table_name,$target_column,$value){
		$table = $this->senpai_prefix . $table_name;
		$this->senpai_db->delete( $table, array( $target_column => $value ));
	}

	/**
	 * Remove all rows
	 * 
	 * ```
	 * $senpai_db->reset(table_name);
	 * ```
	 * 
	 * @param string $table_name
	 * @return void
	 */
    public function reset($table_name){
		$table = $this->senpai_prefix . $table_name;
		$this->senpai_db->query("TRUNCATE TABLE $table");
	}

}
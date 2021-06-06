<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Cache helper class it's used to speedup interact with WordPress DB Cache.
	 * 
	 * 
	 * @see https://developer.wordpress.org/reference/functions/wp_cache_get/
	 * @see https://developer.wordpress.org/reference/functions/wp_cache_set/
	 * @category Class
	 * @author amine safsafi
	 */
class CACHE {
	/**
	 * @ignore
	 */
    protected $group;
	/**
	 * @ignore
	 */
    protected $expire;


    /**
	 * Cache constructor
	 * 
	 * ```
	 * $cache = new \WP_SENPAI\Utils\CACHE('dev',30);
	 * ```
	 * 
	 * @param string $group
	 * @param string $expire in minutes
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($group = '',$expire = 0){
		$this->group = $group;
		$this->expire = (int)$expire * 60;
       //store a value in cache
		//wp_cache_set( 'unique_key', $data );

		//retrieve value from cache
		//$data = wp_cache_get( 'unqiue_key' ); 
    }

	/**
	 * Cache setter
	 * 
	 * ```
	 * $cache->set('test',array('hello','bye'));
	 * ```
	 * 
	 * @param string $key
	 * @param array $data
	 * @author amine safsafi
	 * @return bool
	 */
	public function set($key, $data){
		//store a value in cache
		return wp_cache_set( $key, json_encode($data),$this->group,$this->expire);
 
		 //retrieve value from cache
		 //$data = wp_cache_get( 'unqiue_key' ); 
	}

	/**
	 * Cache getter
	 * 
	 * ```
	 * $data = $cache->get('test');
	 * ```
	 * 
	 * @param string $key
	 * @author amine safsafi
	 * @return array
	 */
	public function get($key){
		//retrieve value from cache
		return json_decode (wp_cache_get( $key,$this->group ),true); 
	 }

}
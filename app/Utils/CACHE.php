<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Cache helper class it's used to speedup interact with WordPress DB Cache.
	 * 
	 * 
	 * @see https://developer.wordpress.org/reference/functions/wp_cache_set/
	 * @category Class
	 * @author amine safsafi
	 */
class CACHE {

    public function __construct(){
       //store a value in cache
		//wp_cache_set( 'unique_key', $data );

		//retrieve value from cache
		//$data = wp_cache_get( 'unqiue_key' ); 
    }

}
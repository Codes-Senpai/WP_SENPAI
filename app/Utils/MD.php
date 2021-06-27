<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * MD helper class it's used to interact with WordPress MetaData.
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
	 * ```
	 * 
	 * @category Class
	 * @author amine safsafi
	 */
class MD {
   
    /**
	 * @ignore
	 */
    var $senpai_prefix = '';

    /**
	 * Initiate MD
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
	 * ```
	 * 
	 * @param string $prefix
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($prefix =''){
        if($prefix){
            $this->senpai_prefix = $prefix . '_';
        }else{
            $this->senpai_prefix = 'wp_senpai_';
        }
    }

    /**
	 * Create new MetaData MD
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
     * $senpai_md->set_metadata($post_id, 'test');
     * OR
     * \WP_SENPAI\Utils\MD::set_metadata($post_id, 'test');
	 * ```
	 * 
	 * @param int $post_id
	 * @param string $meta_key
	 * @param mixed $meta_value
	 * @author amine safsafi
	 * @return void
	 */
    public static function set_metadata($post_id, $meta_key, $meta_value = ''){
        return add_post_meta( $post_id, $this->senpai_prefix . $meta_key, $meta_value, true);
    }

    /**
	 * Get new MetaData MD
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
     * $meta_value = $senpai_md->get_metadata($post_id, 'test');
     * OR
     * $meta_value = \WP_SENPAI\Utils\MD::get_metadata($post_id, 'test');
	 * ```
	 * 
	 * @param int $post_id
	 * @param string $meta_key
	 * @param mixed $meta_value
	 * @author amine safsafi
	 * @return void
	 */
    public static function get_metadata($post_id,$meta_key){
        return get_post_meta($post_id, $this->senpai_prefix . $meta_key, true);
    }

    /**
	 * Create new MetaData MD or update existing
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
     * $senpai_md->update_metadata($post_id,'test','test_value');
     * OR
     * \WP_SENPAI\Utils\MD::update_metadata($post_id,'test','test_value');
	 * ```
	 * 
	 * @param int $post_id
	 * @param string $meta_key
	 * @param mixed $meta_value
	 * @author amine safsafi
	 * @return void
	 */
    public static function update_metadata($post_id, $meta_key, $meta_value=''){
        return update_post_meta($post_id, $this->senpai_prefix . $meta_key, $meta_value); 
    }

    /**
	 * Remove MetaData MD
	 * 
	 * ```
	 * $senpai_md = new \WP_SENPAI\Utils\MD();
     * $senpai_md->remove_metadata($post_id,'test');
     * OR
     * \WP_SENPAI\Utils\MD::remove_metadata($post_id,'test');
	 * ```
	 * 
	 * @param int $post_id
	 * @param string $meta_key
	 * @param mixed $meta_value
	 * @author amine safsafi
	 * @return void
	 */
    public static function remove_metadata($post_id, $meta_key, $meta_value = ''){
        return delete_post_meta($post_id, $this->senpai_prefix . $meta_key, $meta_value);
    }
}
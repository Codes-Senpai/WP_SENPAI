<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * @category Class
	 * @author amine safsafi
	 */
class JSON {
	/**
	 * @ignore
	 */
	var $options;
	/**
	 * @ignore
	 */
	var $option_name;
	/**
	 * @ignore
	 */
    var $is_site_option;
    
    
	/**
	 * @param string $option_name
	 * @param boolean $is_site_options
	 * @author amine safsafi
	 * @return void
	 */
	public function __construct($option_name, $is_site_options = false){
		$this->option_name = $option_name;
		$this->is_site_option = $is_site_options;
		if($this->is_site_option){
			$this->options = get_site_option($this->option_name);
		} else {
			$this->options = get_option($this->option_name);
		}
        
		// Check if options are JSON
		if(!is_array($this->options)){
			$temp_options = json_decode($this->options);
			if(json_last_error() == JSON_ERROR_NONE && !empty($temp_options)){
				$this->options = $temp_options;
			}

			if(empty($this->options)){
				$this->options = array();
			}
		}
	}
	/**
	 * @ignore
	 * @return boolean
	 */
    public function remove($key){
        if(is_array($this->options)){
            if(isset($this->options[$key])){
                unset($this->options[$key]);
                return true;
            }
        }elseif(is_object($this->options)){
            if(isset($this->options->{$key})){
                unset($this->options->{$key});
                return true;
            }
        }
        return false;
    }

	/**
	 * @param string $key 
	 * @return string
	 * @author amine safsafi
	 */
    public function get($key){
		if(is_array($this->options)){
			if(isset($this->options[$key])){
				return $this->options[$key];
			}
		} elseif(is_object($this->options)){
			if(isset($this->options->{$key})){
				return $this->options->{$key};
			}
		}

		return false;
    }
		
	/**
	 * @return array
	 * @author amine safsafi
	 */	
    public function get_all(){
		if(is_array($this->options)){
				return $this->options;
		} elseif(is_object($this->options)){
			$array = (array) $this->options;
				return $array;
		}
		return false;
	}
 
	/**
	 * @param string $key required
	 * @param string $value
	 * @author amine safsafi
	 * @return void
	 */
	public function set($key, $value){
		if(is_array($this->options)){
			$this->options[$key] = $value;
		} elseif(is_object($this->options)){
			$this->options->{$key} = $value;
		} else {
			// Do nothing
		}
	}
 	/**
	 * @ignore
	 */
	function __isset($key){
		if(is_array($this->options)){
			return array_key_exists($key, $this->options);
		} elseif(is_object($this->options)){
			return property_exists($this->options, $key);
		} else {
			return false;
		}
	}
 
	/**
	 * 
	 * @return void
	 * @author amine safsafi
	 */
	public function save(){
		if($this->is_site_option){
			update_site_option($this->option_name, json_encode($this->options));
		} else {
			update_option($this->option_name, json_encode($this->options));
		}
    }
    /**
	 * @author amine safsafi
	 * @return void
	 */
	public function reset(){
		if($this->is_site_option){
			delete_option($this->option_name);
		} else {
			delete_option($this->option_name);
		}
    }
}
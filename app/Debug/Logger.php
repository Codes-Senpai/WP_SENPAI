<?php
namespace WP_SENPAI\Debug;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Logger Class it's used to Log Stuff that can be accesible through dashboard
	 * 
	 * 
	 * 
	 * @todo add clear functionality
	 * @todo add admin widget
	 * @category Class
	 * @author amine safsafi
	 */
class Logger {
	/**
	 * @ignore
	 */
    var $senpai_base;

	/**
	 * $logger = new \WP_SENPAI\Debug\Logger();
	 * for the $base variable only URL valid characters allowed
	 * @param string $base
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($base = 'senpai-log'){
		$this->senpai_base = $base;
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/" . $base;
		if (!file_exists($log_filename)) 
		{
			mkdir($log_filename, 0700, true);
			$f = fopen($log_filename . "/.htaccess", "a+");
			fwrite($f, "deny from all");
			fclose($f);
		}
    }

	/**
	 * Display logs under tools page
	 * $logger->show_admin();
	 * @author amine safsafi
	 * @return void
	 */
	public function show_admin(){
		add_action( 'admin_menu', [ $this, 'add_logs_menu' ] );
	}
	/**
	 * Append variable to log file
	 * $logger->log('test');
	 * @param mixed $log_msg
	 * @author amine safsafi
	 * @return void
	 */
	public function log($log_msg)
	{
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/" . $this->senpai_base;	
		$log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
		$now = current_time( 'mysql' );
		$seperator = '---------[ ' . $now . ' ]---------';
		file_put_contents($log_file_data, $seperator . "\n", FILE_APPEND);
		// if you don't add `FILE_APPEND`, the file will be erased each time you add a log
		file_put_contents($log_file_data, print_r($log_msg,1) . "\n", FILE_APPEND);
	}

	/**
	 * Remove Log folder and files within
	 * $logger->destroy();
	 * @author amine safsafi
	 * @return int
	 */
	public function destroy() {
		$upload_dir   = wp_upload_dir();
		$dir = $upload_dir['basedir'] . "/" . $this->senpai_base;
		$retval = 0; 
		system('rm -rf -- ' . escapeshellarg($dir), $retval);
		return $retval == 0; // UNIX commands return zero on success
	}
	/**
	 * @ignore
	 */
	public function add_logs_menu(){
        add_submenu_page(
            'tools.php',
            'Senpai Logs', // page_title
            'SL ['.$this->senpai_base.']', // menu_title
            'manage_options', // capability
            'senpai_logs_viewer_'.$this->senpai_base, // menu_slug
            [ $this, 'logs_page_render'], // function
        );
    }
	/**
	 * @ignore
	 */
	public function logs_page_render(){
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/" . $this->senpai_base;
		$files = scandir($log_filename);
		$logs_contents = array();
		foreach ($files as $key => $file) {
			if($key >=2){
				if($file != '.htaccess'){
					$full_path = $log_filename . "/" .$file;
					$item = array();
					$item['title'] = $file;
					$item['content'] = nl2br(file_get_contents( $full_path ));
					array_push($logs_contents,$item);
				}
			}
		}
		$now = current_time( 'mysql' );
		$page_folder = $this->senpai_base;
		$HTML = "<div class='wrap'>";
		$HTML .= "<div style='padding:1rem;display:flex;align-items: center;justify-content:space-between;background: white;'><h1>$page_folder</h1><h1>server-time: $now</h1></div>";
		if(count($logs_contents)){
			foreach ($logs_contents as $key => $value){
				$title = $value['title'];
				$content = $value['content'];
				$HTML .= "<h1 style='padding:1rem;'>$title</h1>";
				$HTML .= "<br><div style='max-height:300px;overflow:scroll;background-color:white'>$content</div><br>";
			}
		}else{
			$HTML .= "<br><div style='padding:3rem;display:flex;align-items: center;justify-content:center;background: white;'><h1>NO Logs Available.</h1></div><br>";
		}
		$HTML .= "</div><div class='clear'></div>";
		echo $HTML;
	}

}
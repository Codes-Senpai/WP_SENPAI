<?php
namespace WP_SENPAI\Debug;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Logger Class it's used to Log Stuff that can be accesible through dashboard
	 * @category Class
	 * @author amine safsafi
	 */
class Logger {

    public function __construct(){
		add_action( 'admin_menu', [ $this, 'add_logs_menu' ] );
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/senpai-log";
		if (!file_exists($log_filename)) 
		{
			mkdir($log_filename, 0700, true);
			$f = fopen($log_filename . "/.htaccess", "a+");
			fwrite($f, "deny from all");
			fclose($f);
		}
    }

	public function log($log_msg)
	{
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/senpai-log";	
		$log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
		$now = current_time( 'mysql' );
		$seperator = '---------[ ' . $now . ' ]---------';
		file_put_contents($log_file_data, $seperator . "\n", FILE_APPEND);
		// if you don't add `FILE_APPEND`, the file will be erased each time you add a log
		file_put_contents($log_file_data, print_r($log_msg,1) . "\n", FILE_APPEND);
	}

	public function add_logs_menu(){
        add_submenu_page(
            'tools.php',
            'Senpai Logs', // page_title
            'Senpai Logs', // menu_title
            'manage_options', // capability
            'senpai_logs_viewer', // menu_slug
            [ $this, 'logs_page_render'], // function
            4// position
        );
    }

	public function logs_page_render(){
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/senpai-log";
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
		$HTML = "<div class='wrap'>";
		$HTML .= "<div style='display:flex;align-items: center;justify-content:center;background: white;'><h1>server-time: $now</h1></div>";
		foreach ($logs_contents as $key => $value){
			$title = $value['title'];
			$content = $value['content'];
			$HTML .= "<h1>$title</h1>";
			$HTML .= "<br><div style='max-height:300px;overflow:scroll;background-color:white'>$content</div><br>";
		}
		$HTML .= "</div><div class='clear'></div>";
		echo $HTML;
	}

}
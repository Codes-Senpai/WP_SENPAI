<?php
namespace WP_SENPAI\Debug;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Logger helper Class to Log Stuff that can be accessible through dashboard
	 * 
	 * ```
	 * $logger = new \WP_SENPAI\Debug\Logger();
	 * $logger->show_admin();
	 * $logger->log('test');
	 * $logger->destroy();
	 * ```
	 *
	 * @category Class
	 * @author amine safsafi
	 */
class Logger {
	/**
	 * @ignore
	 */
    var $senpai_base;

	/**
	 * @ignore
	 */
    var $senpai_expire;

	/**
	 * @ignore
	 */
    var $senpai_uri_base;

	/**
	 * @ignore
	 */
    var $senpai_token;

	/**
	 * Initiate new logger
	 * 
	 * ```
	 * $logger = new \WP_SENPAI\Debug\Logger();
	 * ```
	 * 
	 * @param string $base only URL valid characters allowed
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($base = 'senpai-log',$expire = 5){
		$this->senpai_base   = $base;
		$this->senpai_expire = $expire;
		$this->senpai_token = wp_create_nonce('senpai-token');
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/" . $base;
		if (!file_exists($log_filename)) 
		{
			mkdir($log_filename, 0700, true);
			$f = fopen($log_filename . "/.htaccess", "a+");
			fwrite($f, "deny from all");
			fclose($f);
		}else{
			if($expire != -1){
				$this->delete_expired_logs($log_filename,$this->senpai_expire);
			}
		}
		$this->senpai_uri_base = get_template_directory_uri() . '/vendor/senpai/wp-senpai';
		

    }

	/**
	 * Display logs under tools page
	 * 
	 * ```
	 * $logger->show_admin();
	 * ```
	 * 
	 * @author amine safsafi
	 * @return void
	 */
	public function show_admin(){
		add_action( 'admin_menu', array($this, 'add_logs_menu') );
		add_action( 'admin_enqueue_scripts', array($this,'load_assets') );
		add_action( 'init', array($this,'senpai_logs_downloading_handler') );
	}
	/**
	 * Log variable or string
	 * 
	 * ```
	 * $logger->log('test');
	 * ```
	 * 
	 * @param mixed $log_msg
	 * @author amine safsafi
	 * @return void
	 */
	public function log($log_msg, $hint = "NA")
	{
		$msg_ready = print_r($log_msg,1);
		$msg_ready = str_replace(array("\n","\r"), '', $msg_ready);
		$msg_ready = preg_replace('/\s+/', ' ', $msg_ready);
		$type = gettype($log_msg);
		$upload_dir   = wp_upload_dir();
		$log_filename = $upload_dir['basedir'] . "/" . $this->senpai_base;	
		$log_file_data = $log_filename.'/log_' . date('Y-m-d') . '.csv';
		$now = current_time( 'mysql' );
		if(file_exists($log_file_data)){
			$row = "$now,$msg_ready,$type,$hint";
			file_put_contents($log_file_data, $row . "\n", FILE_APPEND);
		}else{
			$header= "Time, Log, Type, Hint";
			$row = "$now,$msg_ready,$type,$hint";
			file_put_contents($log_file_data, $header . "\n", FILE_APPEND);
			file_put_contents($log_file_data, $row . "\n", FILE_APPEND);
		}

	}

	/**
	 * Remove Log folder and and all files
	 * 
	 * ```
	 * $logger->destroy();
	 * ```
	 * 
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
					
					$item['content'] = $this->csv_table_html($full_path);
					array_push($logs_contents,$item);
				}
			}
		}
		$now = current_time( 'mysql' );
		$page_folder = $this->senpai_base;
		$HTML = "<div class='wrap'>";
		$HTML .= "<div style='padding:1rem;display:flex;align-items: center;justify-content:space-between;background: white;'>
		<h1>Logger: $page_folder</h1>
		<h1>Expire: $this->senpai_expire Days</h1>
		<h1>Server Time: $now</h1>
		</div>";
		$site_base = get_site_url() . '/';
		if(count($logs_contents)){
			foreach ($logs_contents as $key => $value){
				$title = $value['title'];
				$content = $value['content'];
				$HTML .= "<h1 style='padding:1rem;'>$title</h1>";
				$link =  $site_base . '?log_token='.$this->senpai_token.'&log_file_name=' . $title;
				$HTML .= "<a  style='margin:0 0 1rem 1rem;' href='$link' target='_blank' class='button'>Download</a>";
				$HTML .= "<br><div class='table-container'>$content</div><br>";
			}
		}else{
			$HTML .= "<br><div style='padding:3rem;display:flex;align-items: center;justify-content:center;background: white;'><h1>NO Logs Available.</h1></div><br>";
		}
		$HTML .= "</div><div class='clear'></div>";
		echo $HTML;
	}

	/**
	 * @ignore
	 */
	public function csv_table_html($full_path){
		$file = fopen( $full_path, "r" );
		$html = "<table data-table-theme=\"dark zebra\">\n\n";
		while (($line = fgetcsv($file)) !== false) {
			$html .= "<tr>";
			foreach ($line as $cell) {
				$html .= "<td>" . htmlspecialchars($cell) . "</td>";
			}
			$html .= "</tr>\n";
		}
		fclose($file);
		$html .= "</table>";
		return $html;
	}

	/**
	 * @ignore
	 */
	public function delete_expired_logs($folderName,$days){
		if (file_exists($folderName)) {
			foreach (new \DirectoryIterator($folderName) as $fileInfo) {
				if ($fileInfo->isDot()) {
				continue;
				}
				$filename = $fileInfo->getFilename();
				if ($filename  != '.htaccess' && $fileInfo->isFile() && time() - $fileInfo->getCTime() >= $days*24*60*60) {
					unlink($fileInfo->getRealPath());
				}
			}
		}
	}


	/**
	 * @ignore
	 */
	public function load_assets($screen){
		if (strpos($screen, 'tools_page_senpai_logs_viewer') !== false) {
			wp_enqueue_style( 'debug-table-css', $this->senpai_uri_base . '/static/table.css', array(), NULL, 'all' );
			//wp_enqueue_style( 'prism-css', $this->senpai_uri_base . '/static/prism.css', array(), NULL, 'all' );
			//wp_enqueue_script( 'prism-js', $this->senpai_uri_base . '/static/prism.js', array(), NULL, true );
		}
	}

	/**
	 * @ignore
	 */
	public function senpai_logs_downloading_handler(){
		if(isset($_GET['log_token'])){
			if(isset($_GET['log_token']) && isset($_GET['log_file_name'])){
				if ( ! wp_verify_nonce( $_GET['log_token'], 'senpai-token' ) ){ die (); }
				$upload_dir   = wp_upload_dir();
				$log_filename = $upload_dir['basedir'] . "/" . $this->senpai_base;	
				$file = $log_filename.'/' . $_GET['log_file_name'];
				if(!file_exists($file)){ die (); }
				$s = new \diversen\sendfile();
				try {
					$s->send($file);
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
				die();
			}else{
				wp_die(404); // not legit
			}
		}
	}
}
<?php

/**
 * The admin-specific functionality of the plugin.
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wpage_Lcr
 * @subpackage Wpage_Lcr/admin
 */

/**
 * @package    Wpage_Lcr
 * @subpackage Wpage_Lcr/admin
 * @author     Md Junayed <devjoo.cantact@gmail.com>
 */
class Wpage_Lcr_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Locked page
		add_shortcode( 'wpage_locked', array($this,'wpage_locked_page') );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpage-lcr-admin.css', array(), microtime(), 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpage-lcr-admin.js', array( 'jquery' ), microtime(), false );
	}
	

	function get_post_slug($post_id) {
		global $wpdb;
		if($slug = $wpdb->get_var("SELECT post_name FROM {$wpdb->prefix}posts WHERE ID = $post_id")) {
			return $slug;
		} else {
			return '';
		}
	}

	public function wpage_links_generator(){
		require_once(WPAGE_PATH.'includes/wpage-link-generator.php');
	}

	public function wpage_menu(){
		add_menu_page( 'WPageLocker', 'WPage Locker', 'manage_options', 'wpage-lcr', array($this,"menupage_display"), 'dashicons-hidden', 45 );
	}

	function menupage_display(){
		require_once(plugin_dir_path( __FILE__ ).'partials/wpage-lcr-admin-display.php');
	}


	// option form
	function wpage_options(){
		add_settings_section( 'wpage_section', 'WPAGE LOCKER', '', 'wpage-settings' );
		// Add new trip page
		add_settings_field( 'wpage_referrals', 'Reffer Limit', array($this,'wpage_referrals_cb'), 'wpage-settings', 'wpage_section');
		register_setting( 'wpage_section', 'wpage_referrals');

		add_settings_field( 'lockedpagetitle', 'Locked Page Title', array($this,'lockedpagetitle_cb'), 'wpage-settings', 'wpage_section');
		register_setting( 'wpage_section', 'lockedpagetitle');

		add_settings_field( 'expirydate', 'Cookie Expiry Date', array($this,'expirydate_cb'), 'wpage-settings', 'wpage_section');
		register_setting( 'wpage_section', 'expirydate');

	}

	function expirydate_cb(){
		echo '<br>';
		?>
		<input type="number" name="expirydate" placeholder="90 Days" value="<?php echo get_option('expirydate') ?>">
		<?php
	}


	function lockedpagetitle_cb(){
		echo '<br>';
		?>
		<textarea name="lockedpagetitle" placeholder="Write a title" cols="36" rows="3"><?php echo __(get_option('lockedpagetitle'), 'wpage-lcr') ?></textarea>
		<br>
		<small>Please use <b style="color:red">%visits%</b> for showing conversion values!</small>
		<?php
		echo '<br>';
		echo '<br>';
	}

	
	function wpage_referrals_cb(){
		echo '<br>';
		?>
		<input type="number" name="wpage_referrals" placeholder="Limit" value="<?php echo get_option('wpage_referrals') ?>">
		<?php
		echo '<br>';
		echo '<br>';
	}

	public function getmy_ip(){
		$host = gethostname();
		$hostip = @gethostbyname( $host );
		$ip = ( $hostip == $host ) ? $host : long2ip( ip2long( $hostip ) );
		return $ip;
	}

	// Locked page content
	function show_aff_link(){
		global $wpdb,$wp_query;
		$post_id = get_post()->ID;
		
		$user_id = $this->wpage_user_id();

		if(!$user_id){
			?>
			<script>
			location.reload();
			</script>
			<?php
		}
		
		?>
		<div class="wpage-show-link">
			<?php
				
				$link = site_url().'/lcr/'.$user_id.'/p/'.$post_id;
				$counts = $wpdb->query("SELECT * FROM {$wpdb->prefix}wpage_locker WHERE owner_id = $user_id AND post_id = $post_id");

				if(!$counts){
					$counts = 0;
				}

				if(get_option( 'lockedpagetitle' )){
					echo '<h2 class="locked-page-title"> '.__(str_replace('%visits%', $counts, get_option( 'lockedpagetitle' )), 'wpage-lcr').' </h2>';
				}
				echo '<input type="text" value="' .esc_html($link). '" readonly/>';
			 ?>
		</div>
		<?php
	}

	function self_url(){
		// Show create link form
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
			$url = "https://";   
		else  
			$url = "http://";   
		// Append the host(domain name, ip) to the URL.   
		$url.= $_SERVER['HTTP_HOST'];   
		
		// Append the requested resource location to the URL   
		$url.= $_SERVER['REQUEST_URI'];
		return $url;
	}
	
	function wpage_user_id(){
		global $current_user;
		if(get_option( 'expirydate' )){
			$expiry = get_option( 'expirydate' );
		}else{
			$expiry = 90;
		}
		
		$user_id = 0;
		if(isset($_COOKIE['wpage_user_id'])){
			if(is_user_logged_in(  )){
				$user_id = get_user_meta( $current_user->ID,'wpage_uid',true );
				update_user_meta( $current_user->ID,'wpage_uid', $_COOKIE['wpage_user_id'] );
			}else{
				$user_id = $_COOKIE['wpage_user_id'];
			}
		}else{
			setcookie('wpage_user_id',rand(),time() + 60 * 60 * 24 * $expiry,'/');
			$user_id = $_COOKIE['wpage_user_id'];

			if(is_user_logged_in(  )){
				delete_user_meta( $current_user->ID,'wpage_uid', $user_id );
				add_user_meta( $current_user->ID,'wpage_uid', $user_id );
			}
		}
		
		return $user_id;
	}
	
	// Set for get locked page id
	function wpage_locked_page($atts){
		global $wpdb;
		// Get Id depending user login
		$user_id = $this->wpage_user_id();
		$post_id = get_post()->ID;
		$table = $wpdb->prefix.'wpage_locked_url';

		$url = '';

		if($atts){
			$url = $atts['url'];
		}

		$get = $wpdb->get_var("SELECT ID FROM $table WHERE user_id = $user_id AND post_id = $post_id");

		if(!$get){
			$wpdb->insert($table, array('user_id' => $user_id, 'post_id' => $post_id, 'url' => $url), array('%d', '%d', '%s'));
		}

		// Locked
		if(!get_post_meta( $post_id, 'wpage_locked', true )){
			add_post_meta( $post_id, 'wpage_locked', $post_id );
		}

		// Check refer count
		$get_locker = $wpdb->query("SELECT * FROM {$wpdb->prefix}wpage_locker WHERE owner_id = $user_id AND post_id = $post_id");

		if($get_locker < get_option( 'wpage_referrals' )){

			if(get_post_meta( $post_id, 'wpage_locked', true )){
				
				if(url_to_postid( $this->self_url() ) === $post_id){
					// if(!current_user_can( 'administrator' )){}
					ob_start();
					wp_enqueue_style( WPAGE_NAME );
					wp_enqueue_script( WPAGE_NAME );
					echo '<div class="wpage_contents">';
					$this->show_aff_link();
					echo '<a class="lockedLink nolink" href="#">Unlock page content</a>';
					
					return ob_get_clean();
				}
			}
		}else{
			ob_start();
			wp_enqueue_style( WPAGE_NAME );
			wp_enqueue_script( WPAGE_NAME );

			$get = $wpdb->get_var("SELECT url FROM {$wpdb->prefix}wpage_locked_url WHERE user_id = $user_id AND post_id = $post_id");

			echo '<div class="wpage_contents">';
			echo '<a target="_devjoo" class="lockedLink link" href="'.esc_url($get).'">Receive Your Code</a>';
			echo '</div>';
			return ob_get_clean();
		}

		return;
	}

}
<?php
/**
 * Plugin Name: skomfare2 save search 
 * Plugin URI: https://wordpress.org/plugins/search-statistics/
 * Description: Save what users search for
 * Version: 1.5.7
 * Author: Skomfare2
 * Author URI: https://profiles.wordpress.org/skomfare2

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Register Custom Post Type

class skomfare2_Save_Search {

	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
	
	//translate JS script
	private $translation_array;

	
	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}	

	
	/**
	 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
	 */	
	function __construct(){
		
		//define some constants
		define( 'skomfare2_SEARCH_TERM_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
		define( 'skomfare2_SEARCH_TERM_PLUGIN_DIR', plugin_dir_path(__FILE__) );
	
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );	
	
		//add the plugin options page on the admin menu
		add_action( 'admin_menu', array($this,'register_skomfare2_menu') );	
	
		//hook into template_redirect and check if is search
		add_action('template_redirect', array($this,'plugin_is_page'));
		
		
		//add_action( 'admin_enqueue_scripts',array($this, 'skomfare2_search_terms_load_datatable_js_css' ) );
		add_action('in_admin_footer', array($this,'skomfare2_search_terms_load_datatable_js_css'));

		// Localize the script with new data
		$this->translation_array = array(
			'admin_url' =>  admin_url('admin-ajax.php'),
		);

		
			
		//Ajax admin for deleting selected terms
		add_action( 'wp_ajax_skomfare2_delete_terms_ajax', array($this,'skomfare2_delete_terms_ajax') );
		
			//Ajax admin for deleting ALL terms
		add_action( 'wp_ajax_skomfare2_delete_all_terms_ajax', array($this,'skomfare2_delete_all_terms_ajax') );
		
		
	
		
	}		

	
	/*
	* Delete selected terms 
	*/
	function skomfare2_delete_terms_ajax(){
		
		if ( current_user_can( 'manage_options' ) ){

			echo 'Yes you can';
		
			$skomfare2_searchterms_text_array = get_option('_skomfare2_savedterms_query_text');

			
			if(isset($_POST['array_index_to_delete'])){
			
				foreach($_POST['array_index_to_delete'] as $single_term_to_delete_v){
				
					unset($skomfare2_searchterms_text_array[$single_term_to_delete_v]);
				}
				
				$skomfare2_searchterms_text_array = array_values($skomfare2_searchterms_text_array);
				
				update_option('_skomfare2_savedterms_query_text',$skomfare2_searchterms_text_array);
			
			}
			
		}

		die  (json_encode( array ( 'skomfare2_search_terms_delete_status' => 'OK' ) ) );
	}
	
	
	/*
	* Delete all terms 
	*/
	function skomfare2_delete_all_terms_ajax(){

		update_option('_skomfare2_savedterms_query_text',array());

		die  (json_encode( array ( 'skomfare2_search_terms_delete_all_status' => 'OK' ) ) );
		
	}	
	
	
	
	function skomfare2_search_terms_load_datatable_js_css() {

		if(get_current_screen()->id == 'toplevel_page_skomfare2_searchterms_reports'){
			
			// Register the script
			wp_register_script( 'skomfare2-search-terms-js', skomfare2_SEARCH_TERM_PLUGIN_URL.'/assets/js/plugin.js' );
			
			
			wp_localize_script( 'skomfare2-search-terms-js', 'skomfare2SearchTermsJsStrings', $this->translation_array );		
			
			wp_enqueue_script( 'skomfare2-search-terms-dt-js', skomfare2_SEARCH_TERM_PLUGIN_URL.'/assets/datatable/js/jquery.dataTables.min.js' );
			//wp_enqueue_script( 'skomfare2-search-terms-js', skomfare2_SEARCH_TERM_PLUGIN_URL.'/assets/js/plugin.js' );
			
			wp_enqueue_script( 'skomfare2-search-terms-js' );	
			
			wp_enqueue_style( 'skomfare2-search-terms-dt-css', skomfare2_SEARCH_TERM_PLUGIN_URL.'/assets/datatable/css/jquery.dataTables.min.css' );
			wp_enqueue_style( 'skomfare2-search-terms-css', skomfare2_SEARCH_TERM_PLUGIN_URL.'/assets/css/plugin.css' );
		
		}
		
	}


// Enqueued script with localized data.

	
	
	
	public function get_plugin_url() {
		return $this->plugin_url;
	}

	public function get_plugin_path() {
		return $this->plugin_path;
	}	

	
	public function register_skomfare2_menu(){
		
		//Add "View searches" as submenu to "Search Terms"
		
		add_menu_page( 'Searched for', 'Searched for','manage_options', 'skomfare2_searchterms_reports',array( $this, 'reports_submenu_page_callback' ) ) ;
		
		add_submenu_page( 'skomfare2_searchterms_reports', 'Option', 'Option', 'manage_options', 'skomfare2_searchterms_settings', array($this,'show_options_page' ));		
		
		
	}

	/*
	* Add "Reports" as submenu page
	*/
	function reports_submenu_page_callback(){
		require_once(plugin_dir_path(__FILE__). 'reports_page.php');
	}

	public function show_options_page(){
		require_once(plugin_dir_path(__FILE__). 'settings_page_form.php');
		
	}	
	
	function plugin_is_page() {
		
		if (is_search()) {
			
			global $wp_query;
			
			//get search term
			$searched_for = get_query_var( 's')  ;

			
			//get saved option
			$skomfare2_search_term_saved_options = get_option('skomfare2_search_terms_settings_options');
			
			
			//exit quickly if searched for is shorter or equal than the option saved
			if( strlen($searched_for) <=  $skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_if_keyword_bigger_than']){
				return true;
			}


			//save the search term ... only if no posts found
			if($skomfare2_search_term_saved_options['skomfare2_searchterm_save_only_on_404'] == 'yes'){
			
				if($wp_query->found_posts == 0 ) { 
				
					$this->add_new_keyword_to_db($searched_for);
					
				}
				
				return true;
			
			}
			
			$this->add_new_keyword_to_db($searched_for);

			//mail search query

		}
		
	}
	
	
	function add_new_keyword_to_db($searched_for){
			
			//save search query 
			$actual_saves = get_option('_skomfare2_savedterms_query_text');

			$found_actual  = false;
			
			//option on DB hasnt been created yet 
			if(!is_array($actual_saves)){
				
				$actual_saves = array();
				$actual_saves[] = array($searched_for => 1 );
				
			}else{
				
				//we have option on DB 
				
				if(count($actual_saves) > 0 ) {
				
				
					foreach($actual_saves as $actual_saves_k => $actual_saves_v){
						
						foreach($actual_saves_v as $actual_saves_v_single_k => $actual_saves_v_single_v){
							if($actual_saves_v_single_k == $searched_for){
								$found_actual = true;
								//print_r($actual_saves[$actual_saves_k][$actual_saves_v_single_k]);
								$actual_saves[$actual_saves_k][$actual_saves_v_single_k]+=1;
							}

						}
					}
					
					if($found_actual == false ){
						$actual_saves[] = array($searched_for => 1 );
					}
				}else{
					$actual_saves[] = array($searched_for => 1 );
				}
				
			}


			update_option('_skomfare2_savedterms_query_text',$actual_saves);
			
	}
	
}

//Start it all 
skomfare2_Save_Search::get_instance();
<?php
/*
 Plugin Name: Advertising Entery
 Plugin URI: #
 Description: Wizard for ads entering.
 Version: 1.0
 Author: Mohammed Issa Saleh
 Author URI: #
 License: GPLv2
 */

define( 'MISTHEMEADS_PlUGIN_PATH', plugin_dir_path( __FILE__ ) );
require_once MISTHEMEADS_PlUGIN_PATH . 'misthemeDatabase.php';
require_once MISTHEMEADS_PlUGIN_PATH . 'pages/ad-edit.php';
require_once MISTHEMEADS_PlUGIN_PATH . 'pages/ad-manage.php';
require_once MISTHEMEADS_PlUGIN_PATH . 'pages/ad-stat.php';
require_once MISTHEMEADS_PlUGIN_PATH . 'pages/ad-finance.php';
require_once MISTHEMEADS_PlUGIN_PATH . 'pages/ad-captain.php';

define('GOOGLE_MAPS_API','AIzaSyCaFIlw34FsDp1hvx4N7rhprs4Ya7_dnVU');
/*
 * Add custom style sheet to admin page 
*/

register_activation_hook( __FILE__, 'mistheme_quiz_activation' );
    function mistheme_quiz_activation() {
        // Get access to global database access classmistheme
        global $wpdb;
        // Create table on main blog in network mode or single blog
        mistheme_quiz_create_table( $wpdb->get_blog_prefix() );
}
function mistheme_quiz_create_table($prefix) {
    // Prepare SQL query to create database table
    // using function parameter
    $creation_query =
    'CREATE TABLE IF NOT EXISTS ' . $prefix . 'advertisement (
          Ad_id int(11) NOT NULL AUTO_INCREMENT,
          Ad_en_name varchar(70) NOT NULL,
          Ad_ar_name varchar(70) NOT NULL,
          Ad_link varchar(400) NOT NULL,
          Ad_link_type int(11) NOT NULL,
          Ad_type int(11) NOT NULL,
          Ad_priority int(11) NOT NULL,
          Ad_start_date date NOT NULL,
          Ad_end_date date NOT NULL,
          Ad_locations varchar(200) NOT NULL,
          Ad_show_to_captain int(11) NOT NULL,
          Ad_show_to_user int(11) NOT NULL,
          Ad_cap_notify int(11) NOT NULL,
          Ad_user_notify int(11) NOT NULL,
          Ad_showonmap_captain int(11) NOT NULL,
          Ad_showonmap_user int(11) NOT NULL,
          Ad_cap_view_no int(11) NOT NULL,
          Ad_user_view_no int(11) NOT NULL,
          Ad_cap_view_log int(11) NOT NULL,
          Ad_user_view_log int(11) NOT NULL,
		  Ad_cap_show500_log int(11) NOT NULL,
          Ad_cap_not_log int(11) NOT NULL,
          Ad_user_not_log int(11) NOT NULL,
		  Ad_price int(11) NOT NULL,
		  Ad_paid int(11) NOT NULL,
          Advertiser_name varchar(200) NOT NULL,
          Advertiser_type int(11) NOT NULL,
          Advertiser_phone varchar(20) NOT NULL,
          Advertiser_email varchar(100) NOT NULL,
          Advertiser_address varchar(200) NOT NULL,
          Advertiser_website varchar(200) NOT NULL,
          Advertiser_rep_name varchar(50) NOT NULL,
          Advertiser_rep_phone varchar(20) NOT NULL,
          Advertiser_rep_email varchar(100) NOT NULL,
          Advertiser_rep_type int(11) NOT NULL,
          PRIMARY KEY (Ad_id)
    )ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    $logTable =
        'CREATE TABLE IF NOT EXISTS ' . $prefix . 'adlogs (
          id int(11) NOT NULL AUTO_INCREMENT,
          capname varchar(200) NOT NULL,
          event varchar(30) CHARACTER SET utf8 NOT NULL,
          ad_id int(11) NOT NULL,
          timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          meta varchar(200) CHARACTER SET utf8 NULL,
          PRIMARY KEY (id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;';
		//prices
		
	$pricestable =
    'CREATE TABLE IF NOT EXISTS ' . $prefix . 'prices (
          id int(11) NOT NULL AUTO_INCREMENT,
          picture_price int(11) NOT NULL,
          video_price int(11) NOT NULL,
          priority_price int(11) NOT NULL,
          showtocap_price int(11) NOT NULL,
          notifycap_price int(11) NOT NULL,
          mapcap_price int(11) NOT NULL,
          showtouser_price int(11) NOT NULL,
          notifyuser_price int(11) NOT NULL,
          mapuser_price int(11) NOT NULL,
          cap100view_price int(11) NOT NULL,
          user100view_price int(11) NOT NULL,
          pluslocation_price int(11) NOT NULL,
          PRIMARY KEY (id)
    )ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=0;';
	$addprices = 
	'INSERT INTO ' . $prefix . 'prices (`id`, `picture_price`, `video_price`, `priority_price`, `showtocap_price`, `notifycap_price`, `mapcap_price`, `showtouser_price`, `notifyuser_price`, `mapuser_price`, `cap100view_price`, `user100view_price`, `pluslocation_price`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);';

    $capTable = '
    CREATE TABLE IF NOT EXISTS ' . $prefix . 'captains (
      Cap_ID int(11) NOT NULL AUTO_INCREMENT,
      Cap_Username varchar(50) NOT NULL,
      Cap_Password varchar(50) NOT NULL,
      Cap_WorkingTime int(11) NOT NULL DEFAULT 0,
      Cap_WorkingDistance int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (Cap_ID),
      UNIQUE KEY Cap_Username (Cap_Username)
    )  ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
    ';
    global $wpdb;
    $wpdb->query( $creation_query );
    $wpdb->query($logTable);
	$wpdb->query($pricestable);
	$wpdb->query($addprices);

    $wpdb->query($capTable);
}
add_filter('upload_size_limit', 'mistheme_increase_upload');
function mistheme_increase_upload($bytes) {
    return 1024000000;
}

add_action( 'admin_enqueue_scripts', 'load_mistheme_ads_admin_scripts' );
      function load_mistheme_ads_admin_scripts() {
          wp_enqueue_style( 'bootstrap_css', plugins_url( '/admin/css/bootstrap.min.css', __FILE__ ),'','3.4' );
          if(is_rtl()){
              wp_enqueue_style( 'bootstrap_rtl_css', plugins_url( '/admin/css/bootstrap-rtl.min.css', __FILE__ ),'','3.4' );
          }
          wp_enqueue_style( 'bootstrap_table_css', plugins_url( '/admin/css/bootstrap-table.min.css', __FILE__ ),'','1.11' );
          wp_enqueue_style( 'font-awesome', plugins_url( '/admin/css/font-awesome.min.css', __FILE__ ),'','4.7' );
          wp_enqueue_style( 'ads_admin_css', plugins_url( '/admin/css/mistheme-ads-admin.css', __FILE__ ),'','1.5' );
          wp_enqueue_script( 'jquery' );
          wp_enqueue_script( 'bootstrap_js', plugins_url( '/admin/js/bootstrap.min.js', __FILE__ ), array('jquery'),'3.4');
          wp_enqueue_script( 'bootstrap_table_js', plugins_url( '/admin/js/bootstrap-table.min.js', __FILE__ ), array('jquery'),'1.11');
          wp_enqueue_script( 'jquery_steps_js', plugins_url( '/admin/js/jquery.steps.min.js', __FILE__ ), array('jquery'), '1');
          if("%d8%a7%d9%84%d8%a5%d8%b9%d9%84%d8%a7%d9%86%d8%a7%d8%aa_page_ads-new-submenu" == get_current_screen() -> id){
              wp_enqueue_media ();
          }
          wp_enqueue_script( 'mistheme_admin_ajax_js', plugins_url( '/admin/js/mistheme-ads-admin_ajax.js', __FILE__ ), array('jquery','bootstrap_table_js','jquery_steps_js'),'1.3');
          wp_localize_script( 'mistheme_admin_ajax_js', 'admin_ajax', array(
              'url'           => admin_url( 'admin-ajax.php' ),
              'site_url'      => get_bloginfo('url'),
              'theme_url'     => get_bloginfo('template_directory')
          ));
      }

add_action( 'admin_menu', 'mistheme_advertise_menu' );
    function mistheme_advertise_menu() {
        add_menu_page( 'الإعلانات', 'الإعلانات', 'manage_options', 'ads-topmenu', 'display_mistheme_advertise', 'dashicons-megaphone', 5  );
    }


add_action('admin_menu','mistheme_advertise_submenu');
    function mistheme_advertise_submenu(){
        add_submenu_page('ads-topmenu', 'الإعلانات', 'قائمة الإعلانات', 'manage_options', 'ads-topmenu');
        add_submenu_page('ads-topmenu', 'اضف إعلان جديد', 'إعلان جديد', 'manage_options', 'ads-new-submenu','display_mistheme_newAd_submenu');
        add_submenu_page('ads-topmenu', 'احصائيات', 'احصائيات', 'manage_options', 'ads-stat-submenu','display_mistheme_statAd_submenu');
        add_submenu_page('ads-topmenu', 'مالية', 'مالية', 'manage_options', 'ads-fin-submenu','display_mistheme_adFinance_submenu');
		add_submenu_page('ads-topmenu', 'الكابتين', 'الكابتين', 'manage_options', 'ads-cap-submenu','display_mistheme_adCaptains_submenu');

    }


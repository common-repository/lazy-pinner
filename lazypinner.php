<?php 
/*
Plugin Name: Lazy Pinner
Plugin URI: http://www.biofects.com
Description: This plugin automatically post your image and title to pineterst on Publish or Save
Version: 2.3
Author: Lee Thompson and Nick Westerlund
Author URI: http://www.biofects.com
License: GPL2
*/

/*  Copyright 2013 Biofects  (email : biofects@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $lazypinner_version;
$lazypinner_version = "1.1";


function create_lazy_pinner_tables() {
    global $wpdb;
    $table = $wpdb->prefix . 'lazy_pinner_user';
    $table2 = $wpdb->prefix . 'lazy_pinner_logs'; 


        $sql = "CREATE TABLE " . $table . " (
            `email` varchar(100) NOT NULL DEFAULT '',
	    `password` varchar(100) NOT NULL DEFAULT '',
    	    `passkey` varchar(255) NOT NULL DEFAULT '',
  	    `board` varchar(100) NOT NULL DEFAULT '',
  	    `board_user` varchar(100) NOT NULL DEFAULT '',
  	    `board_id` bigint(20) NOT NULL,
  	     UNIQUE KEY `email` (`email`)
	);";
              
        $sql2 = "CREATE TABLE " . $table2 ." (
          	`pinner_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          	`pinner_postid` int(11) NOT NULL,
          	`pinner_comment` varchar(255) NOT NULL DEFAULT ''
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbdelta($sql2);
        add_option( "lazypinner_version", $lazypinner_version );
        update_option( "lazypinner_version", $lazypinner_version );
        $wpdb->query("INSERT INTO $table VALUES('your@email.com', 'cUyYnKRaIMHqW/ZSl1oWy9Um6kdRR6RH57pp6AH22SA=', 'He770', 'your board','your user', 12345 )");

if( $installed_ver != $lazypinner_version ) {

        $sqlnew = "CREATE TABLE " . $table2 ." (
            `pinner_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `pinner_postid` int(11) NOT NULL,
            `pinner_comment` varchar(255) NOT NULL DEFAULT ''
          );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbdelta($sqlnew);
        update_option( "lazypinner_version", $lazypinner_version );

    }
} 

register_activation_hook( __FILE__, 'create_lazy_pinner_tables' );
// adding menu to dashboard
        if(is_admin())
        {       
                add_action('admin_menu', 'lazy_pinner_init');
        }
        
        function lazy_pinner_init()
        {
			if(!get_option('lzpin')) {
	                add_option('lzpin');
			}
                        add_menu_page('Lazy Pinner', 'Lazy Pinner', 'administrator', 'lazy-pinner/includes/admin.php', '', plugins_url('lazy-pinner/images/icon.png'), 1000);
    			// create a new submenu
			add_submenu_page('lazy-pinner/includes/admin.php','Pinner Options', 'Pinner Options', 'administrator', 'lazy-pinner/includes/admin.php', '');
			add_submenu_page('lazy-pinner/includes/admin.php','Pinner Log', 'Pinner Log', 'administrator', 'lazy-pinner/includes/log.php', '');
       }

function LazyPinnerUninstall() {
  global $wpdb;
  $table1 = $wpdb->prefix."lazy_pinner_user";
  $table2 = $wpdb->prefix."lazy_pinner_logs";
  delete_option('lazypinner_version');
  $sql1 = "DROP TABLE IF EXISTS $table1";
  $sql2 = "DROP TABLE IF EXISTS $table2";
  $wpdb->query($sql1);
  $wpdb->query($sql2);
}
    register_uninstall_hook( __FILE__, 'LazyPinnerUninstall' );

include('includes/pinit.php');	

?>

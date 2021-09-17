<?php

/*
Plugin Name: Block Call Now
Plugin URI: https://www.blockcallsnow.com/
Description: Subscription Plugin
Version: 1.0.0
Author: Minhaj Uddin
License: GPLv2 or later
Text Domain: blockcallnow
*/

ob_clean();

ob_start();

define('BLOCKCALLNOW_VERSION', '1.0.0');
define('BLOCKCALLNOW__MINIMUM_WP_VERSION', '4.0');
define('BLOCKCALLNOW__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BLOCKCALLNOW_DELETE_LIMIT', 100000);

define('STRIPE_TRIAL_PLAN_ID', 'price_1JT2vaBHvtauYlYgEkVZc2PB');
define('STRIPE_STANDARD_PLAN_ID', 'price_1JT2vaBHvtauYlYgEkVZc2PB');
define('STRIPE_PRO_PLAN_ID', 'price_1JT2vaBHvtauYlYgEkVZc2PB');

define('API_BASE_URL', 'https://callblock.thesupportonline.net/');
define('STRIPE_PUBLISH_KEY', 'pk_test_51JSf7NBHvtauYlYgB4l4yfyU8z5AjbH5zNjTyut3UWtrYl3Uw9g5rdVAqlm15Ew264zRKlCuwx1OUPNA2u7TjzS200Vt2wePFo');
define('STRIPE_SECRET_KEY','sk_test_51JSf7NBHvtauYlYguxMwEsj0HHeJwBcmM8eA8V3NTIm7WNiuaQkGxhP7sAC7ifjQfjACTGrWutSLyKxY1Fzkg0aK00d9biuES0');

add_action('admin_menu',array('BlockCallNow','displayMenu'));
add_action('wp_enqueue_scripts', array('BlockCallNow', 'load_resources'));


register_activation_hook(__FILE__, array('BlockCallNow', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('BlockCallNow', 'plugin_deactivation'));
add_action(__FILE__, array('BlockCallNow', 'bcn_activate_notice'));

require_once(BLOCKCALLNOW__PLUGIN_DIR . 'class.blockcallnow.php');

add_action('init', array('BlockCallNow', 'init'));
add_action('admin_post_add_customer', array('BlockCallNow', 'add_customer'));

add_action('admin_post_cancel_subs', array('BlockCallNow', 'cancel_subs'));


add_shortcode('blockcallnow_pricing', array('BlockCallNow','pricing_shortcode'));
add_shortcode('blockcallnow_create_customer_form', array('BlockCallNow','create_customer_form_shortcode'));

if($_GET['page'] == 'bcn-subs-plugin' || $_GET['page'] == 'mmu-meeting-plugin-sub'){
    do_action('wp_enqueue_scripts');
}

add_action( 'admin_notices', array('BlockCallNow','activation_notice_func') );

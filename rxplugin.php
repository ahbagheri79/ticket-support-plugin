<?php
/*
Plugin Name: راکس پلاگین
Description: این پلاگین توسط مجموعه راکس برای جامعه وردپرس ایران توسعه داده شده است
Version: 1.0
Author: امیرحسین باقری
Author URI:  https://amirhosseinbagheri.ir
Text Domain: rxplugin
*/
if (!defined('ABSPATH')) {
    exit;
}
require_once(plugin_dir_path(__FILE__) . 'includes/ticket/ticket.php');
require_once(plugin_dir_path(__FILE__) . 'includes/ticket/panel-ticket.php');
require_once(plugin_dir_path(__FILE__) . 'includes/ticket/ticket-form.php');
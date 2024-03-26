<?php
/*
Plugin Name: تیکت می
Description: این پلاگین توسط مجموعه راکس برای جامعه وردپرس ایران توسعه داده شده است
Version: 1.1
Author: امیرحسین باقری
Author URI:  https://amirhosseinbagheri.ir
Text Domain: ticketme
*/
if (!defined('ABSPATH')) {
    exit;
}
require_once(plugin_dir_path(__FILE__) . 'ticket/ticket.php');
require_once(plugin_dir_path(__FILE__) . 'ticket/panel-ticket.php');
require_once(plugin_dir_path(__FILE__) . 'ticket/ticket-form.php');
require_once(plugin_dir_path(__FILE__) . 'panel/panel.php');
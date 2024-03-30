<?php
/*
Plugin Name: تیکت می
Description: تیکت می، یک افزونه قدرتمند و کارآمد برای مدیریت تیکت‌ها و پشتیبانی در وب‌سایت‌های وردپرسی است. با استفاده از این افزونه، شما قادر خواهید بود تا به راحتی تیکت‌های دریافتی از کاربران را مدیریت کرده و به آن‌ها پاسخ دهید. همچنین این افزونه امکاناتی از قبیل ضبط و مدیریت اطلاعات مشتریان، اولویت‌بندی تیکت‌ها، ارسال پیام‌های اطلاع‌رسانی و ... را فراهم می‌کند.
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
require_once(plugin_dir_path(__FILE__) . 'panel/LanguageLoader.php');

// Activation hook for your plugin
register_activation_hook(__FILE__, 'my_plugin_activation');

function my_plugin_activation() {
    $translations = LanguageLoader::load_language_json();
    // Create a new page
    $page_title = $translations["page_title"];
    $page_content = '[show_ticket_content]';
    $page_template = plugin_dir_path( __FILE__ ) . 'ticket/templates/page-ticket.php'; // Template file path
    $page_slug = 'my-ticket'; // Your desired page slug

// Add the page
    $page_args = array(
        'post_title'    => $page_title,
        'post_content'  => $page_content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => $page_slug,
        'meta_input'    => array(
            '_wp_page_template' => $page_template, // Set the template path
        ),
    );
    if (!file_exists($page_template)) {
        echo 'Template MY Ticket Not Found';
    }

    $page_id = wp_insert_post($page_args);

    // Set custom template
    if ($page_id && $page_template) {
        update_post_meta($page_id, '_wp_page_template', $page_template);
    }

    // Add badge inline to title
    add_filter('the_title', 'add_badge_to_title', 10, 2);
}

// Function to add badge inline to title
function add_badge_to_title($title, $id) {
    $translations = LanguageLoader::load_language_json();
    if (is_admin() || !is_page($id)) {
        return $title;
    }

    $badge_html = '<span class="badge">'.$translations["ticketme"].'</span>'; // Replace with your badge HTML
    $title .= ' ' . $badge_html;

    return $title;
}

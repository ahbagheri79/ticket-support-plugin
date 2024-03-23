<?php

// Register custom post type 'ticket'
function create_custom_ticket_post_type()
{
    $labels = array(
        'name' => _x('تیکت‌ها', 'نام عمومی نوع نوشته', 'rx_support_tickets'), // Adjusted text domain
        'singular_name' => _x('تیکت', 'نام تکیه‌گاه نوع نوشته', 'rx_support_tickets'), // Adjusted text domain
        'menu_name' => _x('تیکت‌ها', 'منوی مدیریت', 'rx_support_tickets'), // Adjusted text domain
        'name_admin_bar' => _x('تیکت', 'افزودن جدید در نوار مدیریت', 'rx_support_tickets'), // Adjusted text domain
        'add_new' => _x('افزودن تیکت جدید', 'تیکت', 'rx_support_tickets'), // Adjusted text domain
        'add_new_item' => __('افزودن تیکت جدید', 'rx_support_tickets'), // Adjusted text domain
        'new_item' => __('تیکت جدید', 'rx_support_tickets'), // Adjusted text domain
        'edit_item' => __('ویرایش تیکت', 'rx_support_tickets'), // Adjusted text domain
        'view_item' => __('مشاهده تیکت', 'rx_support_tickets'), // Adjusted text domain
        'all_items' => __('همه تیکت‌ها', 'rx_support_tickets'), // Adjusted text domain
        'search_items' => __('جستجوی تیکت‌ها', 'rx_support_tickets'), // Adjusted text domain
        'parent_item_colon' => __('تیکت‌های والد:', 'rx_support_tickets'), // Adjusted text domain
        'not_found' => __('تیکتی یافت نشد.', 'rx_support_tickets'), // Adjusted text domain
        'not_found_in_trash' => __('تیکتی در سطل زباله یافت نشد.', 'rx_support_tickets'), // Adjusted text domain
    );

    $args = array(
        'labels' => $labels,
        'description' => __('توضیحات.', 'rx_support_tickets'), // Adjusted text domain
        'public' => false, // پنهان از نمایش عمومی
        'publicly_queryable' => true, // فعال کردن جستجوی عمومی
        'show_ui' => true, // پنهان از رابط کاربری مدیریت
        'show_in_menu' => true, // پنهان از منوی مدیریت
        'query_var' => false,
        'rewrite' => array('slug' => 'ticket'), // شناسه سفارشی
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'comments')
    );


    register_post_type('ticket', $args);
}

add_action('init', 'create_custom_ticket_post_type');

//NavBar Manager
// افزودن تیکت به نوار مدیریت
function add_ticket_to_admin_bar()
{
    global $wp_admin_bar;

    // بررسی اینکه کاربر وارد شده است و اجازه ارسال نوشته را دارد
    if (!is_user_logged_in() || !current_user_can('publish_posts')) {
        return;
    }

    // افزودن یک گره والد برای تیکت
    $wp_admin_bar->add_menu(array(
        'id' => 'ticket_menu',
        'title' => __('تیکت', 'rx_support_tickets'), // دامنه متن تنظیم شده
        'href' => admin_url('post-new.php?post_type=ticket'),
    ));

    // افزودن یک آیکون به گره والد
    $wp_admin_bar->add_menu(array(
        'parent' => 'ticket_menu',
        'id' => 'ticket_new',
        'title' => __('تیکت جدید', 'rx_support_tickets'), // دامنه متن تنظیم شده
        'href' => admin_url('post-new.php?post_type=ticket'),
        'meta' => array(
            'title' => __('تیکت جدید', 'rx_support_tickets'), // دامنه متن تنظیم شده
        ),
    ));
}

// اتصال تابع به نوار مدیریت وردپرس
add_action('admin_bar_menu', 'add_ticket_to_admin_bar', 999);

// Register Tax
// ایجاد دسته‌بندی دپارتمان‌های پشتیبانی
function create_support_department_category()
{
    $labels = array(
        'name' => _x('دپارتمان‌های پشتیبانی', 'دسته‌بندی عمومی', 'rx_support_tickets'),
        'singular_name' => _x('دپارتمان پشتیبانی', 'دسته‌بندی تکی', 'rx_support_tickets'),
        'search_items' => __('جستجوی دپارتمان‌های پشتیبانی', 'rx_support_tickets'),
        'popular_items' => __('دپارتمان‌های پرطرفدار', 'rx_support_tickets'),
        'all_items' => __('همه دپارتمان‌های پشتیبانی', 'rx_support_tickets'),
        'edit_item' => __('ویرایش دپارتمان پشتیبانی', 'rx_support_tickets'),
        'update_item' => __('به‌روزرسانی دپارتمان پشتیبانی', 'rx_support_tickets'),
        'add_new_item' => __('افزودن دپارتمان پشتیبانی جدید', 'rx_support_tickets'),
        'new_item_name' => __('نام دپارتمان پشتیبانی جدید', 'rx_support_tickets'),
        'separate_items_with_commas' => __('دپارتمان‌های پشتیبانی را با کاما جدا کنید', 'rx_support_tickets'),
        'add_or_remove_items' => __('افزودن یا حذف دپارتمان‌های پشتیبانی', 'rx_support_tickets'),
        'choose_from_most_used' => __('از پرکاربردترین‌ها انتخاب کنید', 'rx_support_tickets'),
        'not_found' => __('دپارتمان پشتیبانی ای یافت نشد', 'rx_support_tickets'),
        'menu_name' => __('دپارتمان‌های پشتیبانی', 'rx_support_tickets'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false, // Set to false to make it behave like a regular category
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'meta_box_cb' => 'post_categories_meta_box', // Use the default category meta box UI
    );

    // Register the support_department taxonomy
    register_taxonomy('support_department', 'ticket', $args);
}

add_action('init', 'create_support_department_category');


// Register the "Support Ticket" user role
function add_support_ticket_role()
{
    add_role(
        'support_ticket',
        __('پشتیبان تیکت ها', 'support_ticket_domain'), // Role display name
        array(
            'read' => true, // Allow reading
            'edit_posts' => true, // Allow editing posts
            'edit_others_posts' => true, // Allow editing others' posts
            'edit_published_posts' => true, // Allow editing published posts
            'publish_posts' => true, // Allow publishing posts
            'read_private_posts' => true, // Allow reading private posts
            'edit_private_posts' => true, // Allow editing private posts
            'delete_posts' => true, // Disallow deleting posts
            'delete_private_posts' => true, // Disallow deleting private posts
            'delete_published_posts' => true, // Disallow deleting published posts
            'delete_others_posts' => true, // Disallow deleting others' posts
            'read_comments' => true, // Allow reading comments
            'edit_comment' => true, // Allow editing comments
            'publish_comments' => true, // Allow publishing comments
            'delete_comment' => true, // Allow deleting comments
        )
    );
}

add_action('init', 'add_support_ticket_role');
function remove_custom_roles()
{
    remove_role('fast_manager');
    remove_role('fast_agent');
    remove_role('fast_customer');
    remove_role('ticket_manager');
}

add_action('init', 'remove_custom_roles');
function restrict_tickets_access()
{
    $current_user = wp_get_current_user();

    // Check if the current user is not an administrator or support_ticket
    if (!in_array('administrator', $current_user->roles) && !in_array('support_ticket', $current_user->roles)) {
        // Remove access to the Tickets menu
        remove_menu_page('edit.php?post_type=ticket');

        // Redirect users trying to access the Tickets post type editing screen
        global $pagenow;
        if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'ticket') {
            wp_redirect(admin_url());
            exit;
        }
    }
}

add_action('admin_menu', 'restrict_tickets_access');

// Add metabox for ticket status
function ticket_status_metabox()
{
    add_meta_box(
        'ticket_status_metabox', // Metabox ID
        'تعیین وضعیت تیکت', // Metabox title
        'ticket_status_metabox_callback', // Callback function to display metabox content
        'ticket', // Post type
        'side', // Context: 'side', 'normal', or 'advanced'
        'default' // Priority: 'default', 'high', 'low'
    );
}

// Callback function to display metabox content
function ticket_status_metabox_callback($post)
{
    // Get current status
    $status = get_post_meta($post->ID, 'ticket_status', true);

    // Array of status options
    $status_options = array(
        'open' => 'باز',
        'close' => 'بسته',
        'answer' => 'پاسخ داده شده',
        'ended' => 'پایان یافته'
    );

    // Add nonce field for security
    wp_nonce_field('ticket_status_nonce', 'ticket_status_nonce');

    // Display select box
    ?>
    <label for="ticket_status">وضعیت:</label>
    <select name="ticket_status" id="ticket_status">
        <?php foreach ($status_options as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}

// Save ticket status when post is saved
function save_ticket_status($post_id)
{
    // Check if nonce is set
    if (!isset($_POST['ticket_status_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['ticket_status_nonce'], 'ticket_status_nonce')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save status
    if (isset($_POST['ticket_status'])) {
        update_post_meta($post_id, 'ticket_status', sanitize_text_field($_POST['ticket_status']));
    }
}

// Hook functions to add and save metabox
add_action('add_meta_boxes', 'ticket_status_metabox');
add_action('save_post', 'save_ticket_status');

// Register custom template redirect
// Register custom page templates
function custom_register_page_template($templates)
{
    $templates['page-ticket.php'] = __('Ticket Page Template', 'text-domain'); // Adjust the text domain accordingly
    return $templates;
}

add_filter('theme_page_templates', 'custom_register_page_template');
function custom_template_include($template)
{
    $pagetemplate = get_post_meta(get_the_ID(), '_wp_page_template', true);

    // Check if the current page is using your custom template
    if ($pagetemplate == 'page-ticket.php') {
        // Get the path to your custom template file
        $custom_template = plugin_dir_path(__FILE__) . 'templates/page-ticket.php';

        // Check if the custom template file exists
        if (file_exists($custom_template)) {
            // Return the custom template file
            return $custom_template;
        }
    }

    // If not using your custom template, return the original template
    return $template;
}

add_filter('template_include', 'custom_template_include');
function add_noindex_follow_to_ticket_archive()
{
    if (is_post_type_archive('ticket')) {
        echo '<meta name="robots" content="noindex,follow" />' . "\n";
    }
}

add_action('wp_head', 'add_noindex_follow_to_ticket_archive');
function custom_comment_form($comment_form)
{
    global $post;
    if ($post && $post->post_type === 'ticket') {
        $comment_form['comment_field'] = '<p class="comment-form-comment"><label for="comment">پاسخ خود را بنویسید<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required=""></textarea></p>';
    }
    return $comment_form;
}

add_filter('comment_form_defaults', 'custom_comment_form');
function custom_comment_submit_button_text($submit_button_text)
{
    global $post;
    if ($post && $post->post_type === 'ticket') {
        $submit_button_text = '<input name="submit" type="submit" id="submit" class="submit" value="ارسال پاسخ">';
    }
    return $submit_button_text;
}

add_filter('comment_form_submit_button', 'custom_comment_submit_button_text');
// Add the ticket priority metabox
function add_ticket_priority_metabox()
{
    add_meta_box(
        'ticket_priority_metabox', // Metabox ID
        'میزان اهمیت', // Metabox title
        'render_ticket_priority_metabox', // Callback function to render the metabox content
        'ticket', // Post type to display the metabox
        'side', // Context (where to display the metabox)
        'default' // Priority
    );
}

add_action('add_meta_boxes', 'add_ticket_priority_metabox');

// Render the ticket priority metabox content
function render_ticket_priority_metabox($post)
{
    // Retrieve the current priority value
    $priority = get_post_meta($post->ID, 'ticket_priority', true);

    // Output the HTML for the metabox
    ?>
    <label for="ticket_priority">میزان اهمیت:</label>
    <select name="ticket_priority" id="ticket_priority">
        <option value="high" <?php selected($priority, 'high'); ?>>بالا</option>
        <option value="medium" <?php selected($priority, 'medium'); ?>>متوسط</option>
        <option value="low" <?php selected($priority, 'low'); ?>>پایین</option>
    </select>
    <?php
}

// Save the ticket priority value
function save_ticket_priority($post_id)
{
    if (isset($_POST['ticket_priority'])) {
        $priority = sanitize_text_field($_POST['ticket_priority']);
        update_post_meta($post_id, 'ticket_priority', $priority);
    }
}

add_action('save_post_ticket', 'save_ticket_priority');
// Exclude comments of a specific post type from comment list
function exclude_comments_by_post_type($comment_query)
{
    // Check if we are on the admin comments page
    if (is_admin() && $GLOBALS['pagenow'] === 'edit-comments.php') {
        // Define the post type you want to exclude comments from
        $post_types = get_post_types(array('public' => true), 'names');

        // Remove 'ticket' post type from the list
        unset($post_types['ticket']);

        // Exclude comments associated with the specified post type
        $comment_query->query_vars['post_type'] = $post_types;
    }
}

add_action('pre_get_comments', 'exclude_comments_by_post_type');

// Add a custom meta box to the comments panel
function custom_comments_labels($translated_text, $text, $domain)
{
    if ($domain === 'default') {
        switch ($text) {
            case 'Comments':
                $translated_text = __('وضعیت تبادل تیکت ها', 'text-domain');
                break;
            case 'Comments awaiting moderation':
                $translated_text = __('مدیریت تیکت ها', 'text-domain');
                break;
            // Add more cases for other comment-related strings as needed
        }
    }
    return $translated_text;
}

add_filter('gettext', 'custom_comments_labels', 10, 3);
function custom_comment_button_label($translated_text, $text, $domain)
{
    // Check if we are on the post edit screen and the text matches the comment button label
    if (is_admin() && 'Reply' === $text && 'default' === $domain) {
        // Customize the button label
        $translated_text = 'تایید پاسخ';
    }
    return $translated_text;
}

// Apply the filter only on the post edit screen
if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    add_filter('gettext', 'custom_comment_button_label', 20, 3);
}

function enqueue_custom_admin_script()
{
    // Get the current post ID
    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
    // Check if the current page is the post edit screen for post ID 1480
    if ($post_id == 1480 && isset($_GET['action']) && $_GET['action'] == 'edit') {
        $plugin_dir = plugin_dir_url(__FILE__);
        wp_enqueue_script('custom-admin-script', $plugin_dir . 'assets/script/panelscript.js', array('jquery'), '1.0', true);

    }
}

add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script');

function add_ticket_caps_to_subscriber_role() {
    $subscriber_role = get_role( 'subscriber' );
    if ( ! $subscriber_role ) {
        return;
    }

    $subscriber_role->add_cap( 'publish_tickets' );
    $subscriber_role->add_cap( 'read' ); // Ensure subscribers can read tickets
}
add_action( 'init', 'add_ticket_caps_to_subscriber_role' );




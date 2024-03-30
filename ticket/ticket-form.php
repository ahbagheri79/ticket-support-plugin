<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'panel/languageloader.php');

//$translations = LanguageLoader::load_language_json();
function render_popup_html($text, $class) {
    // Construct the HTML for the popup
    $html = "<div class='_popup_div'>";
    $html .= '<div class="ShowResult ' . esc_attr($class) . '" id="_ticket_popup" style="display: block; padding: 20px; position: relative;">';
    $html .= esc_html($text); // Insert the text into the popup
    $html .= '<button onclick="_togglePopUpTicket()" style="position: absolute; top: 0; left: 0;    border: none;
    outline: none;
    background: transparent;
    color: black;
    font-weight: 900;cursor: pointer; font-size: 16px">×</button>';
    $html .= '</div>';
    $html .= '</div>';

    // Return the HTML
    return $html;
}
// Shortcode for rendering the form
function ticket_submission_form_shortcode()
{
    // Enqueue styles
    wp_enqueue_style('rxsupport-style', plugin_dir_url(__FILE__) . 'assets/styles/style.css', array(), '1.0', 'all');
    wp_enqueue_script('rxsupport-script', plugin_dir_url(__FILE__) . 'assets/script/ticket-single.js', array('jquery'), '1.0', true);
    ob_start();
    ?>
    <div class="ShowResult" style="display: none;"></div> <!-- Placeholder for success/error messages -->
    <form id="ticket-submission-form" method="post">
        <?php wp_nonce_field('submit_ticket', 'ticket_submission_nonce'); ?>
        <div class="title">
            <label for="ticket-title">عنوان تیکت:</label><br>
            <input type="text" id="ticket-title" name="ticket_title" required><br>
        </div>

        <div class="content">
            <label for="ticket-content">متن سوال خود را وارد کنید:</label><br>
            <textarea id="ticket-content" name="ticket_content" rows="4" required></textarea><br>
        </div>

        <div class="items_form">
            <div class="departman">
                <label for="support-department">دپارتمان مورد نظر را انتخاب کنید:</label><br>
                <?php
                // Render taxonomy selector for support_department
                $departments = get_terms(array(
                    'taxonomy' => 'support_department',
                    'hide_empty' => false,
                ));

                if (!empty($departments) && !is_wp_error($departments)) {
                    echo '<select id="support-department" name="ticket_department">';
                    echo '<option value="">انتخاب دپارتمان مرتبط</option>';
                    foreach ($departments as $department) {
                        echo '<option value="' . esc_attr($department->term_id) . '">' . esc_html($department->name) . '</option>';
                    }
                    echo '</select>';
                } else {
                    echo '<p>No departments found.</p>';
                }
                ?>
            </div>
            <div class="priority">
                <label for="ticket-priority">میزان اهمیت تیکت:</label><br>
                <select id="ticket-priority" name="ticket_priority">
                    <option value="low">پایین</option>
                    <option value="medium">متوسط</option>
                    <option value="high">بالا</option> <!-- Corrected typo -->
                </select>
            </div>
        </div>

        <input type="submit" name="submit_ticket" value="ارسال پیام به پشتیبانی">
    </form>
    <?php
    return ob_get_clean();
}

// Add the function to register the shortcode
add_shortcode('ticket_submission_form', 'ticket_submission_form_shortcode');

// Hook into the form submission action
add_action('init', 'handle_ticket_submission');

function handle_ticket_submission() {
    $translations = LanguageLoader::load_language_json();
    $rx_send_link_ticket = get_option('rx_send_link_ticket') ?? "";
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (isset($_POST['submit_ticket']) && $current_path !== $rx_send_link_ticket) {
        $text = $translations["start_up_setting_err"];
        $class = '_error';
        $popup_html = render_popup_html($text, $class);
        echo $popup_html;
        return;
    }
    // Check if the form is submitted and the nonce is verified
    if(isset($_POST['submit_ticket']) && wp_verify_nonce($_POST['ticket_submission_nonce'], 'submit_ticket')) {

        // Sanitize and retrieve form data
        $ticket_title = sanitize_text_field($_POST['ticket_title']);
        $ticket_content = sanitize_textarea_field($_POST['ticket_content']);
        $ticket_department = isset($_POST['ticket_department']) ? intval($_POST['ticket_department']) : 0; // Ensure it's an integer
        $ticket_priority = sanitize_text_field($_POST['ticket_priority']);
        if ($ticket_department === 0) {
            $text = $translations["select_departman"];
            $class = '_error';
            $popup_html = render_popup_html($text, $class);
            echo $popup_html;
            return;
        }
        if ($ticket_title == "" || $ticket_content == "") {
            $text = $translations["title_content_empty"];
            $class = '_error';
            $popup_html = render_popup_html($text, $class);
            echo $popup_html;
            return;
        }
        // Create the ticket post
        $current_user_id = get_current_user_id();
        $ticket_args = array(
            'post_title'    => $ticket_title,
            'post_content'  => $ticket_content,
            'post_status'   => 'publish', // Set status as pending for review
            'post_type'     => 'ticket', // Assuming 'ticket' is your custom post type for tickets
            'post_author'   => $current_user_id,
        );

        $ticket_id = wp_insert_post($ticket_args);

        // Check if the ticket is created successfully
        if (!is_wp_error($ticket_id)) {
            // If ticket is created successfully, assign department (if selected)
            if ($ticket_department !== 0) {
                wp_set_post_terms($ticket_id, array($ticket_department), 'support_department');
            }

            // Add custom meta data for ticket priority
            update_post_meta($ticket_id, 'ticket_priority_metabox', $ticket_priority);

            // Redirect or display success message
            // Example: wp_redirect(home_url('/thank-you'));
            // exit;
            // Or you can display a success message on the same page
            $text = $translations["Success_Saved_mess"];
            $class = '_success';
            $popup_html = render_popup_html($text, $class);
            echo $popup_html;
            return;
        } else {
            // If there's an error while creating the ticket
            $text = $translations["unable_to_submit"];
            $class = '_error';
            $popup_html = render_popup_html($text, $class);
            echo $popup_html;
            return;
        }
    }
}







































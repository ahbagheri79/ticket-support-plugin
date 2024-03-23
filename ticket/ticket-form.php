<?php
// Shortcode for rendering the form
// Function to register the shortcode and render the HTML form elements
function ticket_submission_form_shortcode() {
    wp_enqueue_style('rxsupport-style', plugin_dir_url(__FILE__) . 'assets/styles/style.css', array(), '1.0', 'all');
    ob_start();
    ?>
    <div class="ShowResult" style="display: none;"></div> <!-- Placeholder for success/error messages -->
    <form id="ticket-submission-form" method="post">
        <?php wp_nonce_field( 'submit_ticket', 'ticket_submission_nonce' ); ?>
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

// Start session if not started already
session_start();

// Function to handle ticket submission
function handle_ticket_submission() {
    // Check if the current URL matches the desired URL
    if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/dashboard/send-ticket/') {
        if ( isset( $_POST['submit_ticket'] ) && isset( $_POST['ticket_submission_nonce'] ) && wp_verify_nonce( $_POST['ticket_submission_nonce'], 'submit_ticket' ) ) {
            // Sanitize and validate form data
            $title = sanitize_text_field($_POST['ticket_title']);
            $content = wp_kses_post($_POST['ticket_content']);
            $department = isset($_POST['ticket_department']) ? intval($_POST['ticket_department']) : 0;
            $priority = isset($_POST['ticket_priority']) ? $_POST['ticket_priority'] : 'low'; // Set default priority

            // Check if Support Department is empty
            if ( empty($department) ) {
                $_SESSION['ticket_submission_result'] = json_encode(array('error' => 'Please select a Support Department.', 'success' => ''));
                wp_redirect($_SERVER['REQUEST_URI']);
                exit;
            } else {
                // Check user capabilities
                if (!current_user_can('publish_tickets')) {
                    $_SESSION['ticket_submission_result'] = json_encode(array('error' => 'You do not have permission to submit a ticket.', 'success' => ''));
                    wp_redirect($_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    // Create new ticket post
                    $new_ticket = array(
                        'post_title' => $title,
                        'post_content' => $content,
                        'post_type' => 'ticket',
                        'post_status' => 'publish', // Ensure the post is published
                    );

                    // Insert the post and get its ID
                    $ticket_id = wp_insert_post($new_ticket);

                    if (!is_wp_error($ticket_id)) {
                        // Set ticket department and priority
                        if ($department) {
                            wp_set_post_terms($ticket_id, array($department), 'support_department');
                        }
                        wp_set_post_terms($ticket_id, array($priority), 'ticket_priority'); // Corrected the taxonomy name

                        // Update ticket status metabox
                        update_post_meta($ticket_id, 'ticket_status', 'answer');

                        $_SESSION['ticket_submission_result'] = json_encode(array('error' => '', 'success' => 'Ticket submitted successfully!'));
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    } else {
                        $_SESSION['ticket_submission_result'] = json_encode(array('error' => 'An error occurred while submitting the ticket. Please try again later.', 'success' => ''));
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    }
                }
            }
        }
    }
}

// Conditionally render JavaScript only on 'send-ticket' page
function render_ticket_submission_script() {
    if ( is_page( 'send-ticket' ) ) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var resultDiv = document.querySelector('.ShowResult');
                if (resultDiv) {
                    resultDiv.style.display = 'block';
                    <?php if ( isset( $_SESSION['ticket_submission_result'] ) ) :
                        $result = json_decode( $_SESSION['ticket_submission_result'], true ); ?>
                        <?php if ( ! empty( $result['success'] ) ) : ?>
                            resultDiv.innerHTML = '<div class="success notice"><p><?php echo esc_html( $result['success'] ); ?></p></div>';
                        <?php elseif ( ! empty( $result['error'] ) ) : ?>
                            resultDiv.innerHTML = '<div class="error notice"><p><?php echo esc_html( $result['error'] ); ?></p></div>';
                        <?php endif; ?>
                        <?php unset( $_SESSION['ticket_submission_result'] ); ?>
                    <?php endif; ?>
                }
            });
        </script>
        <?php
    }
}

// Add the action hooks
add_action('init', 'handle_ticket_submission');
add_action('wp_footer', 'render_ticket_submission_script');
?>

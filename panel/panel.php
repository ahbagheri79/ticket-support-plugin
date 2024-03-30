<?php
function rx_custom_button_enqueue_styles($hook) {
    wp_enqueue_style('ticketme-style', plugins_url('style.css', __FILE__));
    if ($hook === 'toplevel_page_rxsupport-setting') { // Change hook toplevel_page_rxsupport-setting
        // Enqueue the stylesheet
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    }
}
add_action('admin_enqueue_scripts', 'rx_custom_button_enqueue_styles');

// Add menu item to the dashboard menu
function rx_dashboard_button_menu() {
    $translations = load_language_json();
    add_menu_page(
        $translations['ticketme_page'],    // Page title
        $translations["ticketme"],         // Menu title
        'manage_options',        // Capability
        'rxsupport-setting',    // Menu slug
        'rxsupport_page_content', // Callback function to display page content
        'dashicons-admin-generic', // Icon (optional)
        99                        // Menu position (optional)
    );
}
add_action('admin_menu', 'rx_dashboard_button_menu');
function load_language_json() {
    // Get current language
    $language = get_locale();

    // Construct path to language file
    $file_path = plugin_dir_path( __FILE__ ) . 'languages/' . $language . '.json';

    // Check if language file exists
    if ( file_exists( $file_path ) ) {
        // Load and decode JSON data
        $json_data = file_get_contents( $file_path );
        $translations = json_decode( $json_data, true );

        return $translations; // Return translations
    } else {
        // If the Persian language file also doesn't exist, provide default translations
        return plugin_dir_path( __FILE__ ) . 'languages/fa_IR.json';
    }
}

function rxsupport_page_content()
{
    $translations = load_language_json();
    // Check if form is submitted
    if (isset($_POST['submit'])) { // Change 'rx_submit' to 'submit'
        // Save form data
        update_option('rx_show_link_ticket', sanitize_text_field($_POST['rx_show_link_ticket']));
        update_option('rx_send_link_ticket', sanitize_text_field($_POST['rx_send_link_ticket']));
        update_option('rx_list_ticket', sanitize_text_field($_POST['rx_list_ticket']));
        echo '<div class="updated"><p>'.$translations["savedMessage"].'</p></div>';
    }

    // Retrieve saved options
    // Get the values of options and return an empty string if they are null
    $rx_show_link_ticket = get_option('rx_show_link_ticket') ?? "";
    $rx_send_link_ticket = get_option('rx_send_link_ticket') ?? "";
    $rx_list_ticket = get_option('rx_list_ticket') ?? "";

    // Display form
    ?>
    <section>
        <div id="dashboard-container">
            <header class="dashboard-header">
                <div class="header-content">
                    <h1 class="dashboard-title"><?php echo esc_html($translations['dashboard_title']); ?></h1>
                    <div class="social-links">
                        <a href="https://www.amirhosseinbagheri.ir" target="_blank" class="social-link"><i class="fas fa-globe"></i></a>
                        <!-- Website Link -->
                        <a href="https://github.com/ahbagheri79" target="_blank" class="social-link"><i class="fab fa-github"></i></a>
                        <!-- GitHub Link -->
                        <a href="https://www.linkedin.com/in/ahbagheri79" target="_blank" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <!-- LinkedIn Link -->
                        <a href="https://www.instagram.com/ahbagheri79" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
                        <!-- Instagram Link -->
                        <a class="version" style="color: white"><?php echo esc_html($translations['version']); ?></a>
                    </div>
                </div>
            </header>
            <div class="sidebar">
                <ul class="sidebar-menu">
                    <li class="sidebar-item active" data-section="home"><a href="#"><i class="fas fa-home"></i> <?php echo esc_html($translations['home']); ?></a></li>
                </ul>
            </div>
            <div class="content">
                <section id="home" class="section active">
                    <h1><?php echo esc_html($translations['my_plugin_settings']); ?></h1>
                    <form method="post" action="" class="form-container">
                        <div class="form_item">
                            <label for="rx_show_link_ticket"><?php echo esc_html($translations['show_link_ticket']); ?>:</label>
                            <input type="text" id="rx_show_link_ticket" name="rx_show_link_ticket" value="<?php echo $rx_show_link_ticket; ?>">
                        </div>
                        <div class="form_item">
                            <label for="rx_send_link_ticket"><?php echo esc_html($translations['send_link_ticket']); ?>:</label>
                            <input type="text" id="rx_send_link_ticket" name="rx_send_link_ticket" value="<?php echo $rx_send_link_ticket; ?>">
                        </div>
                        <div class="form_item">
                            <label for="rx_list_ticket"><?php echo esc_html($translations['list_ticket']); ?>:</label>
                            <input type="text" id="rx_list_ticket" name="rx_list_ticket" value="<?php echo $rx_list_ticket; ?>">
                        </div>
                        <div class="form_item_buttton">
                            <input type="submit" name="submit" class="button button-primary" value="<?php echo esc_attr($translations['save_settings']); ?>">
                        </div>
                    </form>
                </section>
            </div>
            <footer class="dashboard-footer">
                <div class="footer-content">
                    <p>&copy; 2024 <?php echo esc_html($translations["reserved"]); ?> | <?php echo esc_html($translations['version']); ?></p>
                    <p><?php echo esc_html($translations['email_support']); ?> <a href="mailto:bagheri.webdeveloper@gmail.com">bagheri.webdeveloper@gmail.com</a></p>
                </div>
            </footer>
        </div>

    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            const sections = document.querySelectorAll('.section');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    const sectionId = this.getAttribute('data-section');

                    // Hide all sections
                    sections.forEach(section => {
                        section.classList.remove('active');
                    });

                    // Show the related section
                    const activeSection = document.getElementById(sectionId);
                    activeSection.classList.add('active');

                    // Highlight the clicked sidebar item
                    sidebarItems.forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });

            // Activate the first sidebar item and its related section by default
            sidebarItems[0].classList.add('active');
            sections[0].classList.add('active');
        });
    </script>
    <?php
}

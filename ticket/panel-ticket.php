<?php
function rxsupport_ticket_posts_shortcode()
{
    // Get the ID of the current user
    $current_user_id = get_current_user_id();
    $image_url = plugins_url('/assets/', __FILE__);

    // Query arguments
    $args = array(
        'post_type' => 'ticket',
        'author' => $current_user_id,
        'posts_per_page' => -1,
    );

    // The Loop
    $query = new WP_Query($args);
    function generate_status_badge($status) {
        switch ($status) {
            case 'open':
                $badge_class = 'badge-open';
                $badge_text = "باز";
                break;
            case 'close':
                $badge_class = 'badge-close';
                $badge_text = "بسته";
                break;
            case 'answer':
                $badge_class = 'badge-answer';
                $badge_text = "پاسخ داده شده";
                break;
            case 'ended':
                $badge_class = 'badge-ended';
                $badge_text = "پایان یافته";
                break;
            default:
                $badge_class = 'badge-default'; // Default badge class for unknown status
                $badge_text = "بدون وضعیت";
                break;
        }
        return [$badge_text, $badge_class];
    }
    wp_enqueue_style('rxsupport-style', plugin_dir_url(__FILE__) . 'assets/styles/style.css', array(), '1.0', 'all');
    ob_start();
    ?>
    <div class="rxsupport-parent-container">
    <?php
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="rxsupport-ticket-item" id="rxsupport-ticket-<?php the_ID(); ?>"
                 style="border-right-color: #f0932b">
                <div class="rxsupport-item-title">
                    <div class="rxsupport-item-inner">
                        <a href="<?php echo esc_url(get_option('home') .'/' . (get_option('rx_show_link_ticket') ?? '') . '?id=' . get_the_ID()); ?>" class="rxsupport-ticket-title"><?php the_title(); ?></a>
                        <div>
                            <div class="rxsupport-ticket-department">
                                <span class="rxsupport-department rxsupport-department-6 rxsupport-badge rxsupport-badge-blue">دپارتمان: <?php echo wp_get_post_terms(get_the_ID(), 'support_department')[0]->name; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sectionCenter">
                    <div class="rxsupport-item-user">
                        <div class="rxsupport-item-inner-author">
                            <span class="rxsupport-creator"><?php echo get_the_author(); ?></span>
                            <div class="rxsupport-reply-count rxsupport-reply-0">
                                <img decoding="async" src="<?php echo $image_url; ?>/image/message.svg" width="20"
                                     height="20" alt="message">
                            </div>
                        </div>
                    </div>
                    <div class="rxsupport-item-status">
                        <div class="rxsupport-item-inner">
                            <div class="rxsupport-status">
                                <?php

                                // Example usage:
                                $status = get_post_meta(get_the_ID(), 'ticket_status', true);// Change this to test different statuses
                                $badge = generate_status_badge($status);
                                echo '<span class="badge ' . $badge[1] . '">' . $badge[0] . '</span>';
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="rxsupport-item-date">
                    <div class="rxsupport-item-inner">
                        <div class="rxsupport-date" dir="ltr"><?php echo get_the_date('Y-m-d H:i'); ?></div>
                        <div class="rxsupport-reply-date" title="<?php echo get_the_date('Y-m-d H:i'); ?>">
                            <span><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' پیش'; ?></span>
                        </div>
                    </div>
                </div>
                <div class="rxsupport-item-actions">
                    <div class="rxsupport-item-inner">
                        <a href="<?php echo esc_url(get_option('home') .'/'. (get_option('rx_show_link_ticket') ?? '') . '/?id=' . get_the_ID()); ?>"
                           class="rxsupport-btn rxsupport-btn-secondary rxsupport-badge-purple rxsupport-btn-small">مشاهده تیکت</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
    else : ?>
        <p><?php _e('No tickets found.', 'textdomain'); ?></p>
    <?php endif;

    $output = ob_get_clean();
    return $output;
}

add_shortcode('rxsupport_ticket_posts', 'rxsupport_ticket_posts_shortcode');



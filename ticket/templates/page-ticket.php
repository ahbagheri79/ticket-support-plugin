<?php
/*
Template Name: Ticket Page Template
*/
$plugin_dir = plugin_dir_url(dirname(__FILE__));
wp_enqueue_style('rxsupport-style', plugin_dir_url(dirname(__FILE__)) . 'assets/styles/style.css', array(), '1.0', 'all');
wp_enqueue_script('accordion-script', plugin_dir_url(dirname(__FILE__)) . 'assets/script/ticket-single.js', array('jquery'), '1.0', true);
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<div id="loading-overlay">
    <div class="loading-spinner"></div>
</div>


<div class="rxsupport">
    <?php
    if ($post_id == 0) {
        echo '<div class="message-container url-incorrect-message">تیکت شما ثبت نشده است لطفا آدرس تیکت را با دقت بررسی کنید</div>';
    } else {
        $current_user_id = get_current_user_id();

        $args = array(
            'post_type' => 'ticket',
            'p' => $post_id,
            'posts_per_page' => 1,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $author_id = get_the_author_meta('ID');
                if ($author_id == $current_user_id) {
                    // Start of your HTML code
                    ?>
                    <div class="section-top">
                        <a href="/dashboard/ticket-list/">همه تیکت ها</a>
                        <div class="divider"></div>
                        <div class="TitleSection">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none">
                                <path fill="rgb(227,234,242)"
                                      d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                      stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                                <path d="M10.74 15.53L14.26 12L10.74 8.47" stroke="rgb(148,160,175)"
                                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="Text">
                                <span><?php the_title(); ?></span>
                                <b>شناسه تیکت: <?php the_ID(); ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="rxsupport_ticket">
                        <div class="right-section">
                            <div class="comments-accordion">
                                <div class="accordion-header">
                                    <div class="divider"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none">
                                        <path d="M13.26 3.59997L5.04997 12.29C4.73997 12.62 4.43997 13.27 4.37997 13.72L4.00997 16.96C3.87997 18.13 4.71997 18.93 5.87997 18.73L9.09997 18.18C9.54997 18.1 10.18 17.77 10.49 17.43L18.7 8.73997C20.12 7.23997 20.76 5.52997 18.55 3.43997C16.35 1.36997 14.68 2.09997 13.26 3.59997Z"
                                              stroke="#22211E" stroke-width="1.5" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11.89 5.05005C12.32 7.81005 14.56 9.92005 17.34 10.2" stroke="#22211E"
                                              stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                        <path d="M3 22H21" stroke="#22211E" stroke-width="1.5" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>ارسال پاسخ جدید</span>
                                    <div class="divider"></div>
                                </div>
                                <div class="accordion-content">
                                    <?php
                                    // Customizing the comment form
                                    comment_form(array(
                                        'title_reply' => 'Leave a Reply',
                                        'comment_notes_after' => '', // Remove the additional notes after the comment form
                                        'class_form' => 'submit-comment-ajax', // Add a custom class to the form
                                    ));
                                    ?>
                                </div>

                            </div>

                            <div class="msgbox">
                                <?php
                                // Get comments associated with the post
                                $comments = get_comments(array(
                                    'post_id' => get_the_ID()
                                ));

                                // Loop through each comment
                                foreach ($comments as $comment) {
                                    // Check if the current user is the post author or an admin or support ticket sender
//                            $post_author_id = get_post_field('post_author', get_the_ID());
//                            $comment_author_id = $comment->user_id;
//                            $is_ticket_sender = $current_user_id === $comment_author_id && $current_user_id !== $post_author_id;
                                    $current_user_id = get_current_user_id();
                                    $comment_author = get_userdata($comment->user_id);
                                    $is_admin = in_array('administrator', $comment_author->roles);
                                    $is_ticket_support = in_array('ticket_support', $comment_author->roles); // Check if user has 'ticket_support' role
                                    if ($is_admin || $is_ticket_support) {
                                        $ticketClass = "msgreplay";
                                    } else {
                                        $ticketClass = "msgsend";
                                    }
                                    ?>
                                    <div class="<?php echo $ticketClass; ?>">
                                        <?php if ($is_admin || $is_ticket_support) : ?>
                                            <div class="date">
                                                <?php echo get_comment_date('Y/m/d - H:i:s', $comment->comment_ID); ?>
                                                <img src="<?php echo $plugin_dir ?>assets/image/clock.svg" alt="Clock">
                                            </div>
                                            <div class="message">
                                                <?php echo esc_html($comment->comment_content); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="message">
                                                <?php echo esc_html($comment->comment_content); ?>
                                            </div>
                                            <div class="date">
                                                <img src="<?php echo $plugin_dir ?>assets/image/clock.svg" alt="Clock">
                                                <?php echo get_comment_date('Y/m/d - H:i:s', $comment->comment_ID); ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                    <?php
                                }
                                ?>
                                <div class="msgsend">
                                    <div class="message"><?php echo wpautop(get_the_content()); ?></div>
                                    <div class="date">
                                        <img src="<?php echo $plugin_dir ?>assets/image/clock.svg" alt="Clock">
                                        <?php
                                        echo get_the_date('Y/m/d - H:m:s');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- End of your HTML code -->
                        </div>
                        <div class="left-section">
                            <div class="status">
                                <?php
                                function generate_status_badge($status)
                                {
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

                                // Example usage:
                                $status = get_post_meta(get_the_ID(), 'ticket_status', true);// Change this to test different statuses
                                $badge = generate_status_badge($status);
                                echo '<li class="rpstatus ' . $badge[1] . '">' . $badge[0] . '</li>';
                                ?>
                            </div>
                            <div class="divider"></div>
                            <div class="Importance">
                                <?php
                                function generate_status_ticket_priority($status)
                                {
                                    switch ($status) {
                                        case 'high':
                                            $badge_class = 'badge-top-level';
                                            $badge_text = "اولویت بالا";
                                            break;
                                        case 'medium':
                                            $badge_class = 'badge-mid-level';
                                            $badge_text = "اولویت متوسط";
                                            break;
                                        case 'low':
                                            $badge_class = 'badge-down-level';
                                            $badge_text = "اولویت پایین";
                                            break;
                                        default:
                                            $badge_class = 'badge-default'; // Default badge class for unknown status
                                            $badge_text = "بدون اولویت";
                                            break;
                                    }
                                    return [$badge_text, $badge_class];
                                }

                                // Example usage:
                                $status = get_post_meta(get_the_ID(), 'ticket_priority', true);// Change this to test different statuses
                                $badge = generate_status_ticket_priority($status);
                                echo '<li class="rpstatus ' . $badge[1] . '">' . $badge[0] . '</li>';
                                ?>
                            </div>
                            <div class="divider"></div>
                            <div class="rpstatus departman badge-departman">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 10H7C9 10 10 9 10 7V5C10 3 9 2 7 2H5C3 2 2 3 2 5V7C2 9 3 10 5 10Z" stroke="rgb(77, 85, 93)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 10H19C21 10 22 9 22 7V5C22 3 21 2 19 2H17C15 2 14 3 14 5V7C14 9 15 10 17 10Z" stroke="rgb(77, 85, 93)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 22H19C21 22 22 21 22 19V17C22 15 21 14 19 14H17C15 14 14 15 14 17V19C14 21 15 22 17 22Z" stroke="rgb(77, 85, 93)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5 22H7C9 22 10 21 10 19V17C10 15 9 14 7 14H5C3 14 2 15 2 17V19C2 21 3 22 5 22Z" stroke="rgb(77, 85, 93)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="">
                                    <?php
                                    $departments = wp_get_post_terms(get_the_ID(), 'support_department');

                                    // Now you can use $departments variable to display or use the department(s)
                                    if (!empty($departments)) {
                                        echo 'دپارتمان: ';
                                        foreach ($departments as $department) {
                                            echo esc_html($department->name);
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                    </div>

                    <?php
                } else {
                    echo '<div class="message-container access-denied-message">دسترسی شما به اینک لینک محدود است</div>';
                }
            }
            wp_reset_postdata();
        } else {
            echo '<div class="message-container url-incorrect-message">تیکت پیدا نشد، لطفا آدرس را با دقت بررسی کنید</div>';
        }
    }
    ?>
</div>

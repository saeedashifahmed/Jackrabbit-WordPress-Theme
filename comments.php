<?php
/**
 * Comments Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

// Bail if password-protected and visitor hasn't entered it.
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area" data-jk-reveal>

    <?php if (have_comments()): ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                esc_html(_nx('%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'jackrabbit')),
                number_format_i18n($comment_count)
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 50,
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation(array(
            'prev_text' => esc_html__('&larr; Older Comments', 'jackrabbit'),
            'next_text' => esc_html__('Newer Comments &rarr;', 'jackrabbit'),
        ));
        ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
        <p class="no-comments">
            <?php esc_html_e('Comments are closed.', 'jackrabbit'); ?>
        </p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply' => esc_html__('Leave a Comment', 'jackrabbit'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h3>',
    ));
    ?>

</div><!-- #comments -->

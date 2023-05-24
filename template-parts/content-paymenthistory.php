<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BuddyBoss_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    $switch_title = get_post_meta(get_the_ID(), 'fullwidth_options', true);
    if (! empty($switch_title)) {
        $show_title = $switch_title['fullwidth_title_switch'];
    }

    if (is_page_template('page-fullwidth.php')) {
        if (empty($show_title)) { ?>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>
        <?php } else {
            // hidden title
        }
    } elseif (! is_search() and ! is_page_template('page-fullscreen.php') and ! is_page_template('page-fullwidth-content.php')) { ?>
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>
    <?php } ?>

    <div class="entry-content my-dashboard">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'buddyboss-theme'),
            'after' => '</div>',
        ));

        // if (is_user_logged_in()) {
        if (current_user_can('manage_options')) {
            ?>
            <table id="paymenthistorytable">
                <thead>
                    <tr>
                        <th>DateTime</th>
                        <th>Item</th>
                        <th>Message</th>
                        <th>Amount</th>
                        <th>Buyer's Email</th>
                        <th>Buyer's Name</th>
                        <th>Affiliate ID</th>
                        <th>Payment ID</th>
                        <th>Order ID</th>
                        <th>Payment Type</th>
                    </tr>
                </thead>
            </table>
            <?php

        }

        ?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'buddyboss-theme'), array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ), get_the_title()
                ), '<span class="edit-link">', '</span>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>

</article>
<?php
/**
 * The template for displaying pages
 * Template Name: Payment History Page
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header();
?>
<div id="content" class="no-padding twist">
    <?php
    // Start the loop.
    while (have_posts()) :
        the_post();

        // Include the page content template.
        get_template_part('template-parts/content', 'paymenthistory');

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            // comments_template();
        }
        // End of the loop.
    endwhile;
    ?>

</div>
<?php get_footer(); ?>
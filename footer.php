<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BuddyBoss_Theme
 */

?>

<?php do_action(THEME_HOOK_PREFIX . 'end_content'); ?>

</div><!-- .bb-grid -->
</div><!-- .container -->
</div><!-- #content -->

<?php do_action(THEME_HOOK_PREFIX . 'after_content'); ?>

<?php do_action(THEME_HOOK_PREFIX . 'before_footer'); ?>
<?php do_action(THEME_HOOK_PREFIX . 'footer'); ?>
<?php do_action(THEME_HOOK_PREFIX . 'after_footer'); ?>

</div><!-- #page -->

<?php do_action(THEME_HOOK_PREFIX . 'after_page'); ?>

<?php wp_footer(); ?>

<!-- Start Engagement Widget Script -->
<script async crossorigin type="module" id="engagementWidget"
    src="https://cdn.chatwidgets.net/widget/livechat/bundle.js" data-env="portal-api" data-instance="Y_QPRL3E77eOcTHi"
    data-container="#engagement-widget-container"></script>
<!-- End Engagement Widget Script -->
</body>

</body>

</html>
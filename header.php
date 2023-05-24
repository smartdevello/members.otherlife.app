<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BuddyBoss_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
	
</html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
<?php
if(is_page('286')){
?>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/style.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/responsive.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/bootstrap.min.css" rel="stylesheet">
<?php
}
?>
<?php
if(is_page('855')){
?>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/style.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/responsive.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/bootstrap.min.css" rel="stylesheet">
<?php
}
?>
<?php
if(is_page('303')){
?>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/style.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/responsive.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/roadmap-assets/css/bootstrap.min.css" rel="stylesheet">
<?php
}
?>
<style type="text/css">
.fwpl-item .ld-progress-percentage.ld-secondary-color.course-completion-rate {
    display: none;
}
.fwpl-item .learndash-wrapper.learndash-widget {
    /* overflow: hidden; */
    padding: 0px 10px 0px 0px;
}
.fwpl-item .ld-progress.ld-progress-inline {
    margin: 0px 0px 10px;
}
</style>
		<script type="text/javascript">
 (function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src='https://cdn.firstpromoter.com/fprom.js',t.onload=t.onreadystatechange=function(){var t=this.readyState;if(!t||"complete"==t||"loaded"==t)try{$FPROM.init("ytgw3q3y",".members.vencher.com")}catch(t){}};var e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(t,e)})();</script>
	</head>

	<body <?php body_class(); ?>>
		
</body>

		<?php do_action( THEME_HOOK_PREFIX . 'before_page' ); ?>

		<div id="page" class="site">

			<?php do_action( THEME_HOOK_PREFIX . 'before_header' ); ?>

			<header id="masthead" class="<?php echo apply_filters( 'buddyboss_site_header_class', 'site-header fff' ); ?>">
				<?php do_action( THEME_HOOK_PREFIX . 'header' ); ?>
			</header>
           	<div id="side-head" class="">
        <div id="primary-navbar" class="primary-navbar container">
    		<?php
    		 $menu = is_user_logged_in() ? 'buddypanel-loggedin' : 'buddypanel-loggedout';
    		wp_nav_menu( array(
    			'theme_location' => $menu,
    			'menu_id'		 => 'primary-menu',
    			'container'		 => false,
    			'fallback_cb'	 => 0,
    			'walker'         => new BuddyBoss_BuddyPanel_Menu_Walker(),
    			'menu_class'	 => 'primary-menu', )
			);
    		?>
			
        </div>
<div id="only-aside" class="<?php echo apply_filters( 'buddyboss_site_header_class', 'site-header fff' ); ?>">
	<?php do_action( THEME_HOOK_PREFIX . 'header' ); ?>
				</div>
	</div>
			<?php do_action( THEME_HOOK_PREFIX . 'after_header' ); ?>

			<?php do_action( THEME_HOOK_PREFIX . 'before_content' ); ?>

			<div id="content" class="site-content">

				<?php do_action( THEME_HOOK_PREFIX . 'begin_content' ); ?>
<?php
if(is_page('160')){
?>
<div class="">
<?php
}elseif(is_page('286')){
	?>
<div class="container">
<?php
}elseif(is_page('855')){
?>
<div class="">
<?php
}
else{
?>
<div class="container">
<?php
}
?>

				
					<div class="<?php echo apply_filters( 'buddyboss_site_content_grid_class', 'bb-grid site-content-grid' ); ?>">
<script type="text/javascript">
jQuery( document ).ready(function() {					
	jQuery( ".header-search-link" ).click(function(e) {
	  e.preventDefault();
	  jQuery('body').toggleClass('search-visible');
	  if (!navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
		setTimeout(
		  function() {
			jQuery('body').find('.header-search-wrap .search-field-top').focus();
		  },
		  90
		);
	  }
	});
	
}); 
</script>
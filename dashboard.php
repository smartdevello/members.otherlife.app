<?php
/**
 * Template Name: Dashboard
 */
 
 get_header();
 global $wp, $wpdb;
 $current_user = get_current_user_id();
 
 ?>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    

 <?php
 if($current_user!=""){

$user_info = get_userdata($current_user);
$first_name = $user_info->first_name;
	
$username=  $user_info->user_login;
$uname = isset($first_name) ? $first_name : $username;
?>

<section id="welcome-main">
    <div class="container-fluid">
        <div class="welcome-main-inner">

            <h2>Hey <?php echo $uname;?>, Welcome back to the Academy!</h2>
            <h5>Pick up where you left off with your <strong>7 Day Shift</strong></h5>

            <div id="new_dashboard">

                <div class="dashboard_top_section oh roadmap_page_top roadmap_index new_roadmap_header mb0" id="roadmap_head">
                    <div class="inner">

                        <a href="javascript:void(0);" onclick="roadmap_stages_toggle()" class="roadmap_stages_toggle">Roadmap Navigation</a>

                        <div class="roadmap_timeline" style="margin:0 auto;float:none;">
                            <div class="timeline">

<?php
$courses_registered = ld_get_mycourses($current_user);
$rocord = array_reverse($courses_registered );
$i=1;
foreach(array_slice($rocord, 0, 7) as $row) {
 $current_user = get_current_user_id();
$course_id = $row;
$course_complete = learndash_course_completed( $current_user, $course_id );

$course_title =  get_the_title( $course_id );
$course_permalink = get_permalink( $course_id );
$course_progress = learndash_course_progress( array(
                        'user_id'   => get_current_user_id(),
                        'course_id' => $course_id,
                        'array'     => true
                    ) );

//print_r($course_progress);
$cp = $course_progress['percentage'];


?>
    <a  href="<?php echo $course_permalink; ?>" title="<?php echo $course_title; ?>" class="roadmap_stage_container <?php
if($course_complete==1){
   echo 'completed_stage'; 
}else{
    echo 'active_stage'; 
}
?> stage<?php echo $i;?>">
                                    <div class="stage_wrap">
                                        <div class="roadmap_stage" data-roadmap-="">

<img id="iconimg" src="<?php 
switch ($i) {
    case 1:
        if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/1.png";
        }else{
            echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/1.png";
            
        }
        break;
    case 2:
        if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/2.png";
        }else{
             echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/2.png";
        }
        break;
    case 3:
        if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/3.png";
        }else{
           echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/3.png";
        }
       
        break;
    case 4:
        if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/4.png";
        }else{
              echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/4.png";
        }
   
       
     break;
     case 5:
         if($course_complete==1){
          echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/5.png";
        }else{
             echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/5.png";
        }
        break;
    case 6:
         if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/6.png";
        }else{
             echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/6.png";
        }
    break;
    case 7:
         if($course_complete==1){
         echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/grey/7.png";
        }else{
             echo "".get_stylesheet_directory_uri()."/roadmap-assets/icons/black/7.png";
        }
    break;
    
}
  ?>">

                                        <span class="per_pro"><?php echo $cp; ?>%</span>
                                            <div <?php


                                            ?>class="roadmap_stage_title">Stage <?php echo $i; ?>: <?php echo $course_title; ?></div>
                                        </div>
                                    </div>
                                </a>
<?php
$i++;

}
?>

<script type="text/javascript">
var numItems = jQuery('.completed_stage').length;
console.log(numItems);
</script>
                                <div class="timeline_active" ></div>

                            </div>
                        </div>


    <h4>Current Stage: <span><a  class="stage-value" href="#"><!-- Stage 2: Your Content Strategy --></a></span></h4>


                     

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="entry-content-page-dashboard">
			<div class="container">
				<?php the_content(); ?> <!-- Page Content -->
			</div>
        </div><!-- .entry-content-page -->

    <?php
endwhile; //resetting the page loop
wp_reset_query(); //resetting the page query
?>
    <?php
}else{
    echo "<h3>Please login to access Roadmap Dashboard!</h3>";
}
?>
 
<?php
 get_footer();
 ?>
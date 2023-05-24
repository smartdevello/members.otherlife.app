<?php
/**
 * This file contains the code that displays the pager.
 * 
 * @since 2.5.4
 * 
 * @package LearnDash
 */

/**
* Available Variables:
* $pager_context	: (string) value defining context of pager output. For example 'course_lessons' would be the course template lessons listing.   
* $pager_results    : (array) query result details containing 
* $href_query_arg	: query string parameter to use. 
* $href_val_prefix  : prefix added to value. default is empty ''.
* results<pre>Array
* (
*    [paged] => 1
*    [total_items] => 30
*    [total_pages] => 2
* )
*/
?>
<?php
/* echo '<pre>pager_results';
print_r($pager_results); */
if ( ( isset( $pager_results ) ) && ( !empty( $pager_results ) ) ) {
	if ( !isset( $pager_context ) ) $pager_context = '';
	if ( !isset( $href_val_prefix ) ) $href_val_prefix = '';
	
	// Generic wrappers. These can be changes via the switch below
	$wrapper_before = '<div class="buddypress-wrap learndash-pager learndash-pager-'. $pager_context .'">';
	$wrapper_after = '</div>';
	
	$pager_results['total_items'] += $pager_results['total_items'];
	
	$col   = empty( $shortcode_atts['col'] ) ? LEARNDASH_COURSE_GRID_COLUMNS :intval( $shortcode_atts['col'] );
	$col   = $col > 6 ? 6 : $col;
	$smcol = $col == 1 ? 1 : $col / 2;
	
	if ( $pager_results['total_pages'] > 1 ) {
		if ( ( ! isset( $href_query_arg ) ) || ( empty( $href_query_arg ) ) ) {
			switch( $pager_context ) {
				case 'course_lessons':
					$href_query_arg = 'ld-lesson-page';
					break;

				case 'course_lesson_topics':
					$href_query_arg = 'ld-topic-page';
					break;

				case 'profile':
					$href_query_arg = 'ld-profile-page';
					break;

				case 'course_content':
					$href_query_arg = 'ld-courseinfo-lesson-page';
					break;
				
				// These are just here to show the existing different context items. 	
				case 'course_info_registered':
				case 'course_info_courses':
				case 'course_info_quizzes':
				case 'course_navigation_widget':
				case 'course_navigation_admin':
				case 'course_list':
				default:
					break;
			}
		}
		
		$pager_left_disabled = '';
		$pager_left_class = '';
		if ( $pager_results['paged'] == 1 ) {
			$pager_left_disabled = ' disabled="disabled" ';
			$pager_left_class = 'disabled';
		} 
		$prev_page_number = ( $pager_results['paged'] > 1 ) ? $pager_results['paged'] - 1 : 1;

		$pager_right_disabled = '';
		$pager_right_class = '';
		if ( $pager_results['paged'] == $pager_results['total_pages'] ) {
			$pager_right_disabled = ' disabled="disabled" ';
			$pager_right_class = 'disabled';
		}
		$next_page_number = ( $pager_results['paged'] < $pager_results['total_pages'] ) ? $pager_results['paged'] + 1 : $pager_results['total_pages'];

		echo $wrapper_before;

		$range = 2;

		$showitems = ($range * 2)+1;

		$paged = $pager_results['paged'];

	   $pages = $pager_results['total_pages'];
       $rul1= $paged - 1;
	   if(1 != $pages)
		{ 
		
		/*?>
		<a  class='prev-left' data-paged='<?php echo $rul1; ?>'><i class='bb-icon-chevron-left' style="display: <?php if( $rul1==0) { echo none; }?>"></i></a>
		<?php  */
		$style = $pages == 	$paged ? 'none' : '';
          //echo "<a class='prev-right' data-paged='".($paged + 1)."'><i class='bb-icon-chevron-right' style='display:".$style."'></i></a>"
        ?>
		<div class='bp-pagination bottom'>
			<div class='bp-pagination-links bottom'>
				<p class='pag-data'>
		          
				<?php
					// if($paged > 1 && $showitems < $pages) echo "<a data-paged='".($paged - 1)."'>←</a>";

					// for ($i=1; $i <= $pages; $i++)
					// {
					// 	if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
					// 	{
					// 		echo ($paged == $i)? "<span class='page-numbers current'>".$i."</span>":"<a data-paged='".$i."' class='inactive' >".$i."</a>";
					// 	}
					// }

					// if ($paged < $pages && $showitems < $pages) echo "<a data-paged='".($paged + 1 )."'>&rarr;</a>";
				?>

				</p>
			</div>
		</div>
        
		<?php
		}
		echo $wrapper_after;

	}
}?>
<script>

/* jQuery( document ).ready(function() {
	jQuery(".ld-course-list-items").not('.slick-initialized').slick({
	  infinite: true,
	  slidesToShow: 5,
	  slidesToScroll: 5
	});
}); */

</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.7.1/slick.min.js"></script>
<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery(".ld-course-list-items").not('.slick-initialized').slick({
		dots: false,
		vertical: false,
		centerMode: true,
		slidesToShow: 4,
		cssEase: 'linear',
		variableWidth: true,
		//slidesToScroll: 1,
		//prevArrow:"<a  class='slick-prev'><i class='bb-icon-chevron-left'></i></a>",
        //nextArrow:"<a class='slick-next'><i class='bb-icon-chevron-right'></i></a>",
		prevArrow:"<button type='button' class='slick-prev pull-left'><i class='bb-icon-chevron-left'></i></button>",
        nextArrow:"<button type='button' class='slick-next pull-right'><i class='bb-icon-chevron-right'></i></button>",
		responsive: [
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			  }
			},
		]
	});
});
	/* jQuery(".bb-cover-list-item").slickLightbox({
	src: "target",
	itemSelector: ".team-image img"
  }); */
</script>
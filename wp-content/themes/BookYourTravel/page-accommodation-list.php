<?php
/*	Template Name: Accommodation list
 * The template for displaying all accommodations in a list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

global $byt_theme_globals, $post, $byt_accommodations_post_type;

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$posts_per_page = $byt_theme_globals->get_accommodations_archive_posts_per_page();

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$sort_by = 'title';
if (isset($page_custom_fields['accommodation_list_sort_by'])) {
	$sort_by = $page_custom_fields['accommodation_list_sort_by'][0];
	$sort_by = empty($sort_by) ? 'title' : $sort_by;
}

$sort_descending = false;
if (isset($page_custom_fields['accommodation_list_sort_descending'])) {
	$sort_descending = $page_custom_fields['accommodation_list_sort_descending'][0] == '1' ? true : false;
}

$sort_order = $sort_descending ? 'DESC' : 'ASC';

$accommodation_tags = wp_get_post_terms($page_id, 'acc_tag', array("fields" => "all"));
$accommodation_tag_ids = array();
if (count($accommodation_tags) > 0) {
	foreach ($accommodation_tags as $accommodation_tag) {
		$accommodation_tag_ids[] = $accommodation_tag->term_id;
	}
}

$accommodation_types = wp_get_post_terms($page_id, 'accommodation_type', array("fields" => "all"));
$accommodation_type_ids = array();
if (count($accommodation_types) > 0) {
	$accommodation_type_ids[] = $accommodation_types[0]->term_id;
}

$parent_location_id = 0;
if (isset($page_custom_fields['accommodation_list_location_post_id'])) {
	$parent_location_id = $page_custom_fields['accommodation_list_location_post_id'][0];
	$parent_location_id = empty($parent_location_id) ? 0 : (int)$parent_location_id;
}

$catering_type = null;
if (isset($page_custom_fields['accommodation_list_catering_type'])) {
	$catering_type = $page_custom_fields['accommodation_list_catering_type'][0];
	$catering_type = empty($catering_type) ? '' : $catering_type;
}

$is_self_catered = null;
if ($catering_type == 'self-catered')
	$is_self_catered = true;
elseif ($catering_type == 'hotel')
	$is_self_catered = false; 

$page_sidebar_positioning = null;
if (isset($page_custom_fields['page_sidebar_positioning'])) {
	$page_sidebar_positioning = $page_custom_fields['page_sidebar_positioning'][0];
	$page_sidebar_positioning = empty($page_sidebar_positioning) ? '' : $page_sidebar_positioning;
}

$section_class = 'full';
if ($page_sidebar_positioning == 'both')
	$section_class = 'one-half';
else if ($page_sidebar_positioning == 'left' || $page_sidebar_positioning == 'right') 
	$section_class = 'three-fourth';

if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'left')
	get_sidebar('left');
?>
<section class="<?php echo esc_attr($section_class); ?>">
	<?php  while ( have_posts() ) : the_post(); ?>
	<article <?php post_class("static-content"); ?> id="page-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
		<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
	</article>
	<?php endwhile; ?>
	<?php
		$accommodation_results = $byt_accommodations_post_type->list_accommodations($paged, $posts_per_page, $sort_by, $sort_order, $parent_location_id, $accommodation_type_ids, $accommodation_tag_ids, array(), false, $is_self_catered);
	?>	
	<div class="deals clearfix">
		<?php if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) { ?>
		<div class="inner-wrap">
		<?php
		foreach ($accommodation_results['results'] as $accommodation_result) {
			global $post, $accommodation_class;
			$post = $accommodation_result;
			setup_postdata( $post ); 
			$accommodation_class = 'one-fourth';
			get_template_part('includes/parts/accommodation', 'item');
		}
		?>
		</div>
		<nav class="page-navigation bottom-nav">
			<!--back up button-->
			<a href="#" class="scroll-to-top" title="<?php esc_attr_e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
			<!--//back up button-->
			<!--pager-->
			<div class="pager">
				<?php 
				$total_results = $accommodation_results['total'];
				BYT_Theme_Utils::display_pager( ceil($total_results/$posts_per_page) );
				?>
			</div>
		</nav>
	<?php } // end if ( $query->have_posts() ) ?>
	</div><!--//deals clearfix-->
</section>
<?php
wp_reset_postdata();
wp_reset_query();

if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');
	
get_footer(); 
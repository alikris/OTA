<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
global $byt_theme_globals;
?></div><!--//main content-->
</div><!--//wrap-->
<?php get_sidebar('above-footer'); ?>
</div><!--//main-->
<!--footer-->
<footer class="footer" role="contentinfo">
	<?php get_sidebar('footer'); ?>
	<div class="wrap clearfix">		
		<section class="bottom">
			<p class="copy"><?php echo $byt_theme_globals->get_copyright_footer(); ?></p>				
			<!--footer navigation-->				
			<?php if ( has_nav_menu( 'footer-menu' ) ) {
				wp_nav_menu( array( 
					'theme_location' => 'footer-menu', 
					'container' => 'nav', 
				) ); 
			} else { ?>
			<nav class="menu-main-menu-container">
				<ul class="menu">
					<li class="menu-item"><a href="<?php echo esc_url(home_url()); ?>"><?php _e('Home', "bookyourtravel"); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "bookyourtravel"); ?></a></li>
				</ul>
			</nav>
			<?php } ?>
			<!--//footer navigation-->
		</section>
	</div>
</footer>
<!--//footer-->
<?php 

get_template_part('includes/parts/login', 'lightbox');
get_template_part('includes/parts/register', 'lightbox'); 
wp_footer();
if (WP_DEBUG) {
	$num_queries = get_num_queries();
	$timer = timer_stop(0);
	echo '<!-- ' . $num_queries . ' queries in ' . $timer . ' seconds. -->';
} 
?>
</body>
</html>
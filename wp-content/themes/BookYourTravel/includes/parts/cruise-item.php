<?php
	global $post, $cruise_class, $display_mode, $byt_theme_globals, $byt_cruises_post_type, $byt_reviews_post_type;
	$cruise_id = $post->ID;
	$cruise_obj = new byt_cruise($post);
	$base_id = $cruise_obj->get_base_id();
	$reviews_total = $byt_reviews_post_type->get_reviews_count($base_id);
	
	$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
	$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
	$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();
	
	$is_price_per_person = $cruise_obj->get_is_price_per_person();
	
	$cruise_image = $cruise_obj->get_main_image();	
	if (empty($cruise_image)) {
		$cruise_image = BYT_Theme_Utils::get_file_uri('/images/uploads/img.jpg');
	}

	$score_out_of_10 = 0;
	if ($reviews_total > 0) {
		$review_score = $cruise_obj->get_custom_field('review_score', false);
		$score_out_of_10 = round($review_score * 10);
	}	
	
	$cruise_description_html = BYT_Theme_Utils::strip_tags_and_shorten($cruise_obj->get_description(), 100) . '<a href="' . $cruise_obj->get_permalink() . '">' . __('More info', 'bookyourtravel') . '</a>';
	
	$current_date = date('Y-m-d', time());
	$cruise_min_price = $byt_cruises_post_type->get_cruise_min_price($cruise_id, 0, $current_date);
	
	if (empty($display_mode) || $display_mode == 'card') {
?>
<!--cruise item-->
<article class="cruise_item <?php echo $cruise_class; ?>">
	<div>
		<figure>
			<a href="<?php echo esc_url($cruise_obj->get_permalink()); ?>" title="<?php echo esc_attr($cruise_obj->get_title()); ?>">
				<img src="<?php echo esc_url($cruise_image); ?>" alt="<?php echo esc_attr($cruise_obj->get_title()); ?>" />
			</a>
		</figure>
		<div class="details">
			<h2><?php echo $cruise_obj->get_title(); ?></h2>
			<?php
			// display cruise address
			if ($score_out_of_10 > 0) { 
				// display score out of 10
				BYT_Theme_Utils::render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
			} 
			if ($cruise_min_price > 0) { ?>
			<div class="price">
				<?php 
				if ($is_price_per_person) 
					_e('Price per person from ', 'bookyourtravel');
				else
					_e('Price per cabin from ', 'bookyourtravel');
				?>
				<em>
				<?php if (!$show_currency_symbol_after) { ?>
				<span class="curr"><?php echo $default_currency_symbol; ?></span>
				<span class="amount"><?php echo number_format_i18n( $cruise_min_price, $price_decimal_places ); ?></span>
				<?php } else { ?>
				<span class="amount"><?php echo number_format_i18n( $cruise_min_price, $price_decimal_places ); ?></span>
				<span class="curr"><?php echo $default_currency_symbol; ?></span>
				<?php } ?>
				</em>
			</div>
			<?php 
			} 
			BYT_Theme_Utils::render_field("description clearfix", "", "", $cruise_description_html, '', false, true);
			echo "<div class='actions'>";
			BYT_Theme_Utils::render_link_button($cruise_obj->get_permalink(), "gradient-button", "", __('Book now', 'bookyourtravel')); 
			echo "</div>";
			?>
		</div>
	</div>
</article>
<!--//cruise item-->
<?php 
	} else {
?>
	<li>
		<a href="<?php echo esc_url($cruise_obj->get_permalink()); ?>">
			<h3><?php echo $cruise_obj->get_title(); ?> <?php if ($cruise_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
			</h3>
			<?php if ($cruise_min_price > 0) { ?>
			<p>
				<?php 
				$price_string = '';
				if (!$show_currency_symbol_after) { 
					$price_string = '<span class="curr">' . $default_currency_symbol . '</span>';
					$price_string .= '<span class="amount">' . number_format_i18n( $cruise_min_price, $price_decimal_places ) . '</span>';
				} else { 
					$price_string = '<span class="amount">' . number_format_i18n( $cruise_min_price, $price_decimal_places ) . '</span>';
					$price_string .= '<span class="curr">' . $default_currency_symbol . '</span>';
				}
				if ($is_price_per_person) 
					echo sprintf(__('From %s per person', 'bookyourtravel'), $price_string);
				else
					echo sprintf(__('From %s per cabin', 'bookyourtravel'), $price_string);
				?>
			</p>
			<?php } ?>
			<?php
			if ($score_out_of_10 > 0) {
				// display score out of 10
				BYT_Theme_Utils::render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
			}	
			?>
		</a>
	</li>
<?php }
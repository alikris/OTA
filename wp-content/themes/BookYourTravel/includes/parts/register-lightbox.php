<?php
global $byt_theme_globals;

$terms_page_url = $byt_theme_globals->get_terms_page_url();
$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
$enc_key = $byt_theme_globals->get_enc_key();
$register_page_url = $byt_theme_globals->get_register_page_url();

$c_val_1_reg = mt_rand(1, 20);
$c_val_2_reg = mt_rand(1, 20);

$c_val_1_reg_str = BYT_Theme_Utils::encrypt($c_val_1_reg, $enc_key);
$c_val_2_reg_str = BYT_Theme_Utils::encrypt($c_val_2_reg, $enc_key);
?>
<div class="lightbox" style="display:none;" id="register_lightbox">
	<div class="lb-wrap">
		<a onclick="toggleLightbox('register_lightbox');" href="javascript:void(0);" class="close">x</a>
		<div class="lb-content">
			<form action="<?php echo esc_url($register_page_url); ?>" method="post">
				<h1><?php _e('Register', 'bookyourtravel'); ?></h1>
				<div class="f-item">
					<label for="user_login"><?php _e('Username', 'bookyourtravel'); ?></label>
					<input tabindex="27" type="text" id="user_login" name="user_login" />
				</div>
				<div class="f-item">
					<label for="user_email"><?php _e('Email', 'bookyourtravel'); ?></label>
					<input tabindex="28" type="email" id="user_email" name="user_email" />
					<input type="hidden" name="email" id="email" value="" />
					<input type="hidden" name="password" id="password" value="" />
				</div>
				<?php if ($add_captcha_to_forms) { ?>
				<div class="row captcha">
					<div class="f-item">
						<label><?php echo sprintf(__('How much is %d + %d', 'bookyourtravel'), $c_val_1_reg, $c_val_2_reg) ?>?</label>
						<input tabindex="29" type="text" required="required" id="c_val_s_reg" name="c_val_s_reg" />
						<input type="hidden" name="c_val_1_reg" id="c_val_1_reg" value="<?php echo esc_attr($c_val_1_reg_str); ?>" />
						<input type="hidden" name="c_val_2_reg" id="c_val_2_reg" value="<?php echo esc_attr($c_val_2_reg_str); ?>" />
					</div>
				</div>
				<?php } ?>
				<?php do_action( 'woocommerce_register_form' ); ?>
				<?php do_action( 'register_form' ); ?>
				<div class="row">					
					<div class="f-item checkbox">
						<div class="checker" id="uniform-check"><span><input tabindex="32" type="checkbox" value="ch1" id="checkboxagree" name="checkboxagree" style="opacity: 0;"></span></div>
						<label><?php echo sprintf(__('I agree to the <a href="%s">terms &amp; conditions</a>.', 'bookyourtravel'), $terms_page_url); ?></label>
						<?php if( isset( $errors['agree'] ) ){ ?>
							<div class="error"><p><?php echo $errors['agree']; ?></p></div>
						<?php } ?>
					</div>
				</div>
				<?php wp_nonce_field( 'woocommerce-register' ); ?>
				<?php wp_nonce_field( 'bookyourtravel_register_form', 'bookyourtravel_register_form_nonce' ) ?>
				<input tabindex="33" type="submit" id="register" name="register" value="<?php esc_attr_e('Create account', 'bookyourtravel'); ?>" class="gradient-button"/>
			</form>
		</div>
	</div>
</div>
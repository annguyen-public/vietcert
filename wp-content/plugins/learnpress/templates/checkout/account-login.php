<?php
/**
 * Template for displaying log in form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/form-login.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( is_user_logged_in() ) {
	return;
}
?>

<input type="radio" id="checkout-account-switch-to-login" checked="checked" name="checkout-account-switch-form" value="login"/>
<div id="checkout-account-login" class="lp-checkout-block left">

	<h4><?php esc_html_e( 'Đăng nhập', 'learnpress' ); ?></h4>

	<ul class="lp-form-fields">
		<li class="form-field">
			<label for="username"><?php esc_html_e( 'Tên đăng nhập hoặc email', 'learnpress' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" name="username" id="username" placeholder="<?php esc_attr_e( 'Tên đăng nhập hoặc email', 'learnpress' ); ?>" autocomplete="username" />
		</li>
		<li class="form-field">
			<label for="password"><?php esc_html_e( 'Mật khẩu', 'learnpress' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="password" name="password" id="password" placeholder="<?php esc_attr_e( 'Mật khẩu', 'learnpress' ); ?>" autocomplete="current-password" />
		</li>
	</ul>

	<?php do_action( 'learn-press/after-checkout-account-login-fields' ); ?>

	<input type="hidden" name="learn-press-checkout-nonce" value="<?php echo wp_create_nonce( 'learn-press-checkout-login' ); ?>">
	<p class="lp-checkout-remember">
		<label>
			<input type="checkbox" name="rememberme"/>
			<?php esc_html_e( 'Ghi nhớ đăng nhập', 'learnpress' ); ?>
		</label>

		<a class="lp-lost-password-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
			<?php esc_html_e( 'Quên mật khẩu?', 'learnpress' ); ?>
		</a>
	</p>

	<p class="lp-checkout-sign-up-link">
		<?php if ( LP()->checkout()->is_enable_register() ) : ?>
			<?php esc_html_e( 'DChưa có tài khoản?', 'learnpress' ); ?>
			<a href="javascript: void(0);">
				<label for="checkout-account-switch-to-register"><?php echo esc_html_x( 'Đăng ký', 'checkout sign up link', 'learnpress' ); ?></label>
			</a>.
		<?php endif; ?>

		<?php learn_press_get_template( 'checkout/guest-checkout-link' ); ?>
	</p>
</div>

<?php
/**
 * Template for displaying user email field which enable checkout as guest.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/guest-email.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$checkout  = LP()->checkout();
$is_exists = $checkout->checkout_email_exists();
?>

<input type="radio" id="checkout-account-switch-to-guest" name="checkout-account-switch-form" value="guest" checked="checked"/>

<div id="checkout-account-guest" class="lp-checkout-block <?php echo $is_exists ? 'email-exists' : ''; ?>">
	<h4><?php esc_html_e( 'As Guest', 'learnpress' ); ?></h4>
	<ul class="lp-form-fields">
		<li class="form-field">
			<input size="30" placeholder="<?php esc_attr_e( 'Email của bạn...', 'learnpress' ); ?>" type="text" id="guest_email" name="guest_email" autocomplete="off">
			<div class="lp-guest-checkout-notice">
				<?php esc_html_e( 'Mã đặt hàng để kích hoạt khóa học sẽ được gửi tới địa chỉ email của bạn sau khi quá trình thanh toán hoàn tất.', 'learnpress' ); ?>
			</div>

			<?php
			$signin = $signup = $divider = '';

			if ( LP()->checkout()->is_enable_login() ) {
				$signin = sprintf( '<a href="javascript:void(0)"><label for="checkout-account-switch-to-login">%s</label></a>', esc_html( _x( 'Đăng nhập', 'checkout sign in link', 'learnpress' ) ) );
			}

			if ( LP()->checkout()->is_enable_login() && LP()->checkout()->is_enable_register() ) {
				$divider = ',';
			}

			if ( LP()->checkout()->is_enable_register() ) {
				$signup = sprintf( '<a href="javascript:void(0)"><label for="checkout-account-switch-to-register">%s</label></a>', esc_html( _x( 'Đăng ký', 'checkout sign up link', 'learnpress' ) ) );
			}
			?>

			<?php if ( LP()->checkout()->is_enable_login() || LP()->checkout()->is_enable_register() ) : ?>
				<div class="lp-guest-switch-login"><?php echo sprintf( __( 'Hoặc bạn có thể %1$s%2$s %3$s ngay bây giờ.', 'learnpress' ), $signin, $divider, $signup ); ?></div>
			<?php endif; ?>
		</li>
	</ul>
	<input type="hidden" name="learn-press-checkout-nonce" value="<?php echo esc_attr( wp_create_nonce( 'learn-press-guest-checkout' ) ); ?>"/>
</div>

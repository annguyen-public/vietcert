<?php
/**
 * Template for displaying logged in form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/form-logged-in.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! is_user_logged_in() ) {
	return;
}

global $user_identity;
?>

<div id="checkout-account-logged-in" class="lp-checkout-block left">
	<p>
		<?php printf( __( 'Đã đăng nhập <a href="%1$s">%2$s</a>.', 'learnpress' ), get_edit_user_link(), $user_identity ); ?>

		<a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" title="<?php esc_attr_e( 'Đăng xuất', 'learnpress' ); ?>">
			<?php esc_html_e( 'Đăng xuất &raquo;', 'learnpress' ); ?>
		</a>
	</p>
</div>

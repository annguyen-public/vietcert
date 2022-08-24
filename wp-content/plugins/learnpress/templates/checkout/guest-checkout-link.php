<?php
/**
 * Template for displaying link to show form for Guest checkout.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! LP()->checkout()->is_enable_guest_checkout() ) {
	return;
}

esc_html_e( 'Hoặc mua nhanh với vai trò', 'learnpress' ); ?>

<a href="javascript: void(0);">
	<label for="checkout-account-switch-to-guest">
		<?php echo esc_html_x( 'Khách', 'checkout guest link', 'learnpress' ); ?>
	</label>
</a>.

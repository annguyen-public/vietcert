<?php
/**
 * Template for displaying payment form for checkout page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/term-conditions.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$term_link = learn_press_get_page_link( 'term_conditions' );
$term_text = learn_press_get_page_title( 'term_conditions' );

if ( ! $term_link ) {
	return;
}

if ( ! $term_text ) {
	$term_text = esc_html__( 'Điều khoản dịch vụ', 'learnpress' );
}
?>

<p class="lp-terms-and-conditions">
	<?php
	echo apply_filters(
		'learn_press_content_item_protected_message',
		sprintf( __( 'Bằng việc hoàn thành mua hàng, bạn đồng ý với các điều khoản <a href="%1$s" target="_blank">%2$s</a>.', 'learnpress' ), $term_link, $term_text )
	);
	?>
</p>

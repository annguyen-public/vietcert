<?php
/**
 * Template for displaying form allow user get back their order by the key in user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/orders/recover-my-order.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

defined( 'ABSPATH' ) || exit();
?>

<?php if ( isset( $order ) && is_a( $order, 'LP_Order' ) ) : ?>
	<?php if ( $order->is_guest() ) : ?>
		<div class="profile-recover-order">
			<p><?php esc_html_e( 'Đơn đặt hàng này đã được duyệt nhưng không có người dùng nào được chỉ định.' ); ?></p>
			<p><?php esc_html_e( 'Nếu đơn hàng này cho cái người khác, bạn có thể gửi mã sau cho họ.' ); ?></p>
			<p><?php esc_html_e( 'Nếu đơn hàng này cho bạn, bạn có thể sử dụng ở đây.' ); ?></p>
			<?php learn_press_get_template( 'order/recover-form.php', array( 'order' => $order ) ); ?>
		</div>

		<?php
	endif;
endif;


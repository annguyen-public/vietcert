<?php
/**
 * Template for displaying order detail.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/order-received.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();
?>

<?php
/**
 * @var LP_Order $order_received
 */
if ( ! isset( $order_received ) ) {
	echo wp_sprintf(
		'<p>%s</p>',
		esc_html(
			apply_filters(
				'learn-press/order/received-invalid-order-message',
				__( 'Đơn đặt hàng không hợp lệ.', 'learnpress' )
			)
		)
	);

	return;
}

echo wp_sprintf(
	'<p>%s</p>',
	esc_html(
		apply_filters(
			'learn-press/order/received-order-message',
			__( 'Cảm ơn. Đơn hàng của bạn đã được tiếp nhận.', 'learnpress' )
		)
	)
);

?>
<table class="order_details">
	<?php if ( isset( $_GET['key'] ) && ! is_user_logged_in() ) : ?>
		<tr class="order-key">
			<th><?php esc_html_e( 'Mã đơn hàng', 'learnpress' ); ?></th>
			<td>
				<?php echo esc_html( $_GET['key'] ); ?>
			</td>
		</tr>
	<?php endif; ?>
	<tr class="order">
		<th><?php esc_html_e( 'Số đơn hàng', 'learnpress' ); ?></th>
		<td>
			<?php echo esc_html( $order_received->get_order_number() ); ?>
		</td>
	</tr>
	<tr class="item">
		<th><?php esc_html_e( 'Mục', 'learnpress' ); ?></th>
		<td>
			<?php
			$links = array();
			$items = $order_received->get_items();
			$count = count( $items );

			foreach ( $items as $item ) {
				if ( empty( $item['course_id'] ) || get_post_type( $item['course_id'] ) !== LP_COURSE_CPT ) {
					$links[] = apply_filters(
						'learn-press/order-item-not-course-id',
						__( 'Khóa học không tồn tại', 'learnpress' ),
						$item
					);
				} else {
					$link = '<a href="' . get_the_permalink( $item['course_id'] ) . '">' . get_the_title( $item['course_id'] ) . ' (#' . $item['course_id'] . ')' . '</a>';
					if ( $count > 1 ) {
						$link = sprintf( '<li>%s</li>', $link );
					}
					$links[] = apply_filters( 'learn-press/order-received-item-link', $link, $item );
				}
			}

			if ( $count > 1 ) {
				echo sprintf( '<ol>%s</ol>', join( '', $links ) );
			} elseif ( 1 == $count ) {
				echo wp_kses_post( implode( '', $links ) );
			} else {
				echo esc_html__( '(Không có mục nào)', 'learnpress' );
			}
			?>
		</td>
	</tr>
	<tr class="date">
		<th><?php esc_html_e( 'Ngày', 'learnpress' ); ?></th>
		<td>
			<?php
			echo wp_kses_post(
				date_i18n(
					get_option( 'date_format' ),
					strtotime( $order_received->get_order_date() )
				)
			);
			?>
		</td>
	</tr>
	<tr class="total">
		<th><?php esc_html_e( 'Tổng', 'learnpress' ); ?></th>
		<td>
			<?php echo wp_kses_post( $order_received->get_formatted_order_total() ); ?>
		</td>
	</tr>
	<?php
	$method_title = $order_received->get_payment_method_title();
	if ( $method_title ) :
		?>
		<tr class="method">
			<th><?php esc_html_e( 'Phương thức thanh toán', 'learnpress' ); ?></th>
			<td>
				<?php echo esc_html( $method_title ); ?>
			</td>
		</tr>
	<?php endif; ?>
</table>

<?php do_action( 'learn-press/order/received/' . $order_received->payment_method, $order_received->get_id() ); ?>
<?php do_action( 'learn-press/order/received', $order_received ); ?>

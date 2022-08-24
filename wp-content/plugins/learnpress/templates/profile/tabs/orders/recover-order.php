<?php
/**
 * Template for displaying recover order in user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/orders/recover-order.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

defined( 'ABSPATH' ) || exit();
?>

<div class="profile-recover-order">
	<p class="recover-order__title"><?php esc_html_e( 'Nếu bạn có một mã đặt hàng bị lỗi bạn có thể khôi phục nó ở đây.', 'learnpress' ); ?></p>
	<p class="recover-order__description"><?php esc_html_e( 'Khi bạn thanh toán hóa đơn tài khoản khách, mã đặt hàng sẽ được gửi về email, bạn có thể dùng mã đó để tạo đơn hàng ở đây.', 'learnpress' ); ?></p>

	<?php learn_press_get_template( 'order/recover-form.php' ); ?>
</div>


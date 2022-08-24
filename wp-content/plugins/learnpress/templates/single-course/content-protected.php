<?php
/**
 * Template for displaying message for course content protected.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/content-protected.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $can_view_item ) || $can_view_item->flag ) {
	return;
}

$message = '';

if ( ! is_user_logged_in() ) {
	$message = sprintf(
		__(
			'Khóa học không được công khai, vui lòng <a class="lp-link-login" href="%s">đăng nhập</a> và đăng ký khóa học để xem nội dung!',
			'learnpress'
		),
		learn_press_get_login_url( learn_press_get_current_url() )
	);
} else {
	$message = $can_view_item->message;
}

learn_press_display_message( $message, 'learn-press-content-protected-message error' );

<?php
/**
 * Template for displaying message in profile dashboard if user is logged in.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/not-logged-in.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

defined( 'ABSPATH' ) || exit();

$profile = LP_Global::profile();

learn_press_display_message( sprintf( __( 'Vui lòng <a href="%s">đăng nhập</a> để xem hồ sơ của bạn', 'learnpress' ), $profile->get_login_url() ) );

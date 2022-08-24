<?php
/**
 * Template for displaying general statistic in user profile overview.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $statistic ) ) {
	return;
}

$user = LP_Profile::instance()->get_user();
?>

<div id="dashboard-general-statistic">

	<?php do_action( 'learn-press/before-profile-dashboard-general-statistic-row' ); ?>

	<div class="dashboard-general-statistic__row">

		<?php do_action( 'learn-press/before-profile-dashboard-user-general-statistic' ); ?>

		<div class="statistic-box">

				<span class="statistic-box__number"><?php echo $statistic['enrolled_courses']; ?></span>
				<p class="statistic-box__text"><?php esc_html_e( 'Khóa học đã đăng ký', 'learnpress' ); ?></p>
 		</div>
		<div class="statistic-box">

				<span class="statistic-box__number"><?php echo $statistic['active_courses']; ?></span>
				<p class="statistic-box__text"><?php esc_html_e( 'Khóa học đang hoạt động', 'learnpress' ); ?></p>
 		</div>
		<div class="statistic-box">

				<span class="statistic-box__number"><?php echo $statistic['completed_courses']; ?></span>
				<p class="statistic-box__text"><?php esc_html_e( 'Khóa học đã hoàn thành', 'learnpress' ); ?></p>
 		</div>

		<?php do_action( 'learn-press/after-profile-dashboard-user-general-statistic' ); ?>

		<?php if ( $user->can_create_course() ) : ?>
			<?php do_action( 'learn-press/before-profile-dashboard-instructor-general-statistic' ); ?>
			<div class="statistic-box">

					<span class="statistic-box__number"><?php print_r( $statistic['total_courses'] ); ?></span>
					<p class="statistic-box__text"><?php esc_html_e( 'Tổng khóa học', 'learnpress' ); ?></p>
 			</div>
			<div class="statistic-box">
 					<span class="statistic-box__number"><?php echo $statistic['total_users']; ?></span>
					<p class="statistic-box__text"><?php esc_html_e( 'Tổng học viên', 'learnpress' ); ?></p>
 			</div>
			<?php do_action( 'learn-press/after-profile-dashboard-instructor-general-statistic' ); ?>

		<?php endif; ?>

	</div>

	<?php do_action( 'learn-press/profile-dashboard-general-statistic-row' ); ?>

	<?php do_action( 'learn-press/after-profile-dashboard-general-statistic-row' ); ?>
</div>

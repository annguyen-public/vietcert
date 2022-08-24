<?php
/**
 * Template for displaying item section meta in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/item-meta.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $item ) ) {
	return;
}

$course = LP_Global::course();
$user = LP_Global::user();
?>

<div class="course-item-meta">

	<?php do_action( 'learn-press/course-section-item/before-' . $item->get_item_type() . '-meta', $item ); ?>

	<?php if ( $item->is_preview() && ! $user->has_completed_item( $item->get_id(), $course->get_id() ) ) : ?>
		<a title="<?php esc_html_e( 'Xem trước', 'eduma' ); ?>" class="lesson-preview button-load-item" href="<?php echo $item->get_permalink(); ?>">
			<i class="fa fa-eye"
			   data-preview="<?php esc_html_e( 'Xem trước', 'eduma' ); ?>"></i>
		</a>
 	<?php else : ?>
		<span class="item-meta course-item-status" title="<?php echo esc_attr( $item->get_status_title() ); ?>"></span>
	<?php endif; ?>

	<?php do_action( 'learn-press/course-section-item/after-' . $item->get_item_type() . '-meta', $item ); ?>
</div>

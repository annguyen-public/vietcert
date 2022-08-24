<?php
// remove action LP3 in child theme
remove_action( 'thim-udemy-course-buttons', 'learn_press_course_external_button', 5 );
remove_action( 'thim-udemy-course-buttons', 'learn_press_course_purchase_button', 10 );
remove_action( 'thim-udemy-course-buttons', 'learn_press_course_enroll_button', 15 );

add_action( 'thim-udemy-course-buttons', 'thim_udemy_button_redmore_course', 5 );
function thim_udemy_button_redmore_course(){
	echo '<a href="'.esc_url( get_the_permalink( get_the_ID() ) ).'" class="read-more-course">'.esc_html__('Read More','eduma').'</a>';
}
// add cusom field for course
remove_action( 'learn-press/course-info-right', 'thim_udemy_course_payment', 20 );
add_action( 'learn-press/course-info-right', 'thim_udemy_course_payment_v4', 20 );
remove_action( 'learn-press/course-info-right', 'thim_udemy_course_wishlist', 25 );


if ( ! function_exists( 'thim_remove_learnpress_hooks_udemy_child' ) ) {
	function thim_remove_learnpress_hooks_udemy_child() {
		add_action( 'learn-press/course-info-right', LP()->template( 'course' )->func( 'user_progress' ), 19 );
		remove_action( 'thim_single_course_meta', LP()->template( 'course' )->func( 'user_progress' ), 30 );
	}
}
add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks_udemy_child', 20 );

if ( ! function_exists( 'thim_udemy_course_payment_v4' ) ) {
	function thim_udemy_course_payment_v4() {
		?>
		<div class="course-payment">
			<?php
			LP()->template( 'course' )->course_pricing() ;
			LP()->template( 'course' )->course_buttons(); ?>
		</div>
		<?php
	}
}

if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) != 'new-1' ) {
	if ( ! function_exists( 'thim_course_content_lp4' ) ) {
		function thim_course_content_lp4() {
			learn_press_get_template( 'single-course/tabs/tabs-2.php' );
		}
	}

 	add_action( 'learn-press/course-content-summary', 'thim_course_content_lp4', 40 );
	remove_action( 'learn-press/single-course-summary', 'learn_press_course_thumbnail', 2 );
	remove_all_actions( 'learn-press/course-content-summary', 60 );
}

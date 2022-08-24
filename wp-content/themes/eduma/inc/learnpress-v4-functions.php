<?php
add_filter( 'learn-press/override-templates', '__return_true' );

if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		remove_action( 'learn-press/course-section-item/before-lp_lesson-meta', LP()->template( 'course' )->func( 'item_meta_duration' ), 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', LP()->template( 'course' )->func( 'quiz_meta_questions' ), 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', LP()->template( 'course' )->func( 'item_meta_duration' ), 20 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_item_meta_duration', 10 );
		//remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_quiz_meta_questions', 5 );

		LP()->template( 'course' )->remove( 'learn-press/single-button-toggle-sidebar', array( '<input type="checkbox" id="sidebar-toggle" />', 'single-button-toggle-sidebar' ), 5 );

		remove_action( 'learn-press/single-button-toggle-sidebar', 'single-button-toggle-sidebar', 5 );

		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_pricing' ), 5 );
		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_buttons' ), 15 );
 		add_action( 'thim_single_course_meta', LP()->template( 'course' )->callback( 'single-course/instructor' ), 5 );
		add_action( 'thim_single_course_meta', LP()->template( 'course' )->callback( 'single-course/meta/category' ), 15 );
		add_action( 'thim_single_course_meta', 'thim_course_ratings', 25 );
		add_action( 'thim_single_course_meta', LP()->template( 'course' )->func( 'user_progress' ), 30 );

		add_action( 'thim_single_course_featured_review', LP()->template( 'course' )->func( 'course_featured_review' ), 5 );


		add_action( 'learnpress/template/pages/profile/before-content', 'thim_wapper_page_title', 5 );
		add_action( 'learnpress/template/pages/profile/before-content', 'thim_wrapper_loop_start', 10 );
		add_action( 'learnpress/template/pages/profile/after-content', 'thim_wrapper_loop_end', 10 );

		add_action( 'learnpress/template/pages/checkout/before-content', 'thim_wapper_page_title', 5 );
		add_action( 'learnpress/template/pages/checkout/before-content', 'thim_wrapper_loop_start', 10 );
		add_action( 'learnpress/template/pages/checkout/after-content', 'thim_wrapper_loop_end', 10 );

		add_action( 'thim_single_course_before_meta', 'thim_course_thumbnail_item', 5 );

		add_action(
			'init', function () {

			if ( class_exists( 'LP_Addon_Wishlist' ) && is_user_logged_in() && thim_is_version_addons_wishlist( '3' ) ) {
				$instance_addon = LP_Addon_Wishlist::instance();
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'wishlist_button' ), 100 );
				add_action( 'thim_after_course_info', array( $instance_addon, 'wishlist_button' ), 10 );
				add_action( 'thim_inner_thumbnail_course', array( $instance_addon, 'wishlist_button' ), 10 );
			}
			if ( class_exists( 'LP_Addon_bbPress' ) ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
			if ( class_exists( 'LP_Addon_Woo_Payment' ) ) {
				$instance_addon = LP_Addon_Woo_Payment::instance();
				remove_action(
					'learn-press/before-course-buttons', array(
						$instance_addon,
						'purchase_course_notice'
					)
				);
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'after_course_buttons' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'purchase_course_notice' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'after_course_buttons' ) );
			}
			if ( class_exists( 'LP_Addon_Assignment' ) ) {
				$instance_addon = LP_Addon_Assignment::instance();
				remove_action(
					'learn-press/course-section-item/before-lp_assignment-meta', array(
					$instance_addon,
					'learnpress_assignment_show_duration'
				), 10
				);
				add_action( 'learn-press/course-section-item/before-lp_assignment-meta', 'thim_assignment_show_duration', 10 );
				if ( ! function_exists( 'thimthim_assignment_show_duration_assignment_show_duration' ) ) {
					function thim_assignment_show_duration( $item ) {
						$duration = get_post_meta( $item->get_id(), '_lp_duration', true );
						if ( absint( $duration ) > 1 ) {
							$duration .= 's';
						}
						$duration_number = absint( $duration );
						$time            = trim( str_replace( $duration_number, '', $duration ) );
						switch ( $time ) {
							case 'minutes' :
								$time = _x( "phút", 'duration', 'eduma' );
								break;
							case 'hours' :
								$time = _x( "giờ", 'duration', 'eduma' );
								break;
							case 'days' :
								$time = _x( "ngày", 'duration', 'eduma' );
								break;
							case 'weeks':
								$time = _x( "tuần", 'duration', 'eduma' );
								break;
							case 'minute' :
								$time = _x( "phút", 'duration', 'eduma' );
								break;
							default:
								$time = _x( "tuần", 'duration', 'eduma' );
						}
						echo '<span class="meta duration">' . $duration_number . ' ' . $time . '</span>';
					}
				}
			}

			if ( class_exists( 'LP_Addon_Coming_Soon_Courses' ) ) {
				$instance_addon = LP_Addon_Coming_Soon_Courses::instance();
				remove_action( 'learn-press/course-content-summary', array( $instance_addon, 'coming_soon_countdown' ), 10 );
				add_action( 'learn-press/single-course-summary', array( $instance_addon, 'coming_soon_countdown' ), 5 );
			}
			if ( class_exists( 'LP_Addon_Prerequisites_Courses' ) ) {
				$instance_addon = LP_Addon_Prerequisites_Courses::instance();
				remove_action( 'learn-press/course-buttons', array( $instance_addon, 'enroll_notice' ), 34 );
				add_action( 'learn-press/single-course-summary', array( $instance_addon, 'enroll_notice' ), 5 );
			}
		}, 99
		);

		remove_action( 'learn-press/after-checkout-form', LP()->template( 'checkout' )->func( 'account_logged_in' ), 20 );
		remove_action( 'learn-press/after-checkout-form', LP()->template( 'checkout' )->func( 'order_comment' ), 60 );
		add_action( 'learn-press/before-checkout-form', LP()->template( 'checkout' )->func( 'account_logged_in' ), 9 );
		add_action( 'learn-press/before-checkout-form', LP()->template( 'checkout' )->func( 'order_comment' ), 11 );
		//		remove_action( 'learn-press/before-user-profile', LP()->template( 'profile' )->func( 'header' ), 10 );
		//		add_action( 'learn-press/before-profile-content', LP()->template( 'profile' )->func( 'header' ), 10 );
		// remove action for page profile - tuanta
		// remove html in begin loop and end loop
		add_filter(
			'learn_press_course_loop_begin', function () {
			return '';
		}
		);
		add_filter(
			'learn_press_course_loop_end', function () {
			return '';
		}
		);

		remove_action( 'learn-press/profile/dashboard-summary', LP()->template( 'profile' )->func( 'dashboard_featured_courses' ), 20 );

		/**
		 * @see LP_Template_Course::popup_footer_nav()
		 */
		//add_action( 'learn-press/after-course-item-content', 'learn_press_lesson_comment_form', 10 );


	}
}
// add div for thumb image when us coming soon
function thim_class_before_thumb_image() {
	$course = LP_Global::course();
	if ( ! $course ) {
		echo '<div>';
	}
	$no_thumbnail = ' no-thumbnail';
	if ( has_post_thumbnail() ) {
		$no_thumbnail = '';
	}
	if ( class_exists( 'LP_Addon_Coming_Soon_Courses' ) ) {
		$instance_addon = LP_Addon_Coming_Soon_Courses::instance();
		if ( $instance_addon->is_coming_soon( $course->get_id() ) ) {
			echo '<div class="thim-top-course' . $no_thumbnail . '">';
		} else {
			echo '<div>';
		}
	} else {
		echo '<div>';
	}

}

function thim_class_after_thumb_image() {
	echo '</div>';
}

add_action( 'learn-press/single-course-summary', 'thim_class_before_thumb_image', 1 );
add_action( 'learn-press/single-course-summary', 'thim_class_after_thumb_image', 6 );
// end
add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );

remove_all_actions( 'learn-press/course-content-summary', 10 );
remove_all_actions( 'learn-press/course-content-summary', 15 );
remove_all_actions( 'learn-press/course-content-summary', 85 );
remove_all_actions( 'learn-press/before-main-content' );

add_filter( 'lp_item_course_class', 'thim_item_course_class_custom' );
function thim_item_course_class_custom( $class ) {
	$class[] = 'thim-course-grid';

	return $class;
}

/**
 * @see LP_Template_Course::popup_header()
 * @see LP_Template_Course::popup_sidebar()
 * @see LP_Template_Course::popup_content()
 * @see LP_Template_Course::popup_footer()
 */


add_action( 'learn-press/before-main-content', 'lp_archive_courses_open', - 100 );
if ( ! function_exists( 'lp_archive_courses_open' ) ) {
	function lp_archive_courses_open() {
		$courses_page_id  = learn_press_get_page_id( 'courses' );
		$courses_page_url = $courses_page_id ? get_page_link( $courses_page_id ) : learn_press_get_current_url();
		if ( thim_check_is_course_taxonomy() || thim_check_is_course() ) {
			?>
			<div id="lp-archive-courses" data-all-courses-url="<?php echo esc_url( $courses_page_url ) ?>">
			<?php
		} elseif ( is_singular( LP_COURSE_CPT ) ) {
			?>
			<div id="lp-single-course" class="lp-single-course learn-press-4">
			<?php
		}
	}
}


if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		add_action(
			'init', function () {
			if ( thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) && class_exists( 'LP_Addon_bbPress' ) && thim_is_version_addons_bbpress( '3' ) ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
		}, 99
		);
	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );


if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) != 'new-1' ) {
	//add_action( 'learn-press/single-course-summary', 'learn_press_course_thumbnail', 2 );
}

function eduma_add_video_lesson() {
	lp_meta_box_textarea_field(
		array(
			'id'          => '_lp_lesson_video_intro',
			'label'       => esc_html__( 'Media', 'eduma' ),
			'description' => esc_html__( 'Add an embed link like video, PDF, slider...', 'eduma' ),
			'default'     => '',
		)
	);
}

add_action( 'learnpress/lesson-settings/after', 'eduma_add_video_lesson' );

add_action(
	'learnpress_save_lp_lesson_metabox', function ( $post_id ) {
	$video = ! empty( $_POST['_lp_lesson_video_intro'] ) ? $_POST['_lp_lesson_video_intro'] : '';

	update_post_meta( $post_id, '_lp_lesson_video_intro', $video );
}
);
// add cusom field for course
if ( ! function_exists( 'eduma_add_custom_field_course' ) ) {
	function eduma_add_custom_field_course() {
		lp_meta_box_text_input_field(
			array(
				'id'          => 'thim_course_duration',
				'label'       => esc_html__( 'Duration Info', 'eduma' ),
				'description' => esc_html__( 'Display duration info', 'eduma' ),
				'default'     => esc_html__( '50 hours', 'eduma' )
			)
		);
		lp_meta_box_text_input_field(
			array(
				'id'          => 'thim_course_language',
				'label'       => esc_html__( 'Languages', 'eduma' ),
				'description' => esc_html__( 'Language\'s used for studying', 'eduma' ),
				'default'     => esc_html__( 'English', 'eduma' )
			)
		);

		lp_meta_box_textarea_field(
			array(
				'id'          => 'thim_course_media_intro',
				'label'       => esc_html__( 'Media Intro', 'eduma' ),
				'description' => esc_html__( 'Enter media intro', 'eduma' ),
				'default'     => '',
			)
		);
	}
}

add_action( 'learnpress/course-settings/after-general', 'eduma_add_custom_field_course' );

add_action(
	'learnpress_save_lp_course_metabox', function ( $post_id ) {
	$video         = ! empty( $_POST['thim_course_media_intro'] ) ? $_POST['thim_course_media_intro'] : '';
	$language      = ! empty( $_POST['thim_course_language'] ) ? $_POST['thim_course_language'] : '';
	$duration_info = ! empty( $_POST['thim_course_duration'] ) ? $_POST['thim_course_duration'] : '';

	update_post_meta( $post_id, 'thim_course_media_intro', $video );
	update_post_meta( $post_id, 'thim_course_language', $language );
	update_post_meta( $post_id, 'thim_course_duration', $duration_info );
}
);

/**
 * @param Remaining time
 */
function thim_get_remaining_time() {
	$user   = LP_Global::user();
	$course = LP_Global::course();

	if ( ! $course ) {
		return false;
	}

	if ( ! $user ) {
		return false;
	}

	if ( ! $user->has_enrolled_course( $course->get_id() ) ) {
		return false;
	}

	if ( $user->has_finished_course( $course->get_id() ) ) {
		return false;
	}

	$remaining_time = $user->get_course_remaining_time( $course->get_id() );

	if ( false === $remaining_time ) {
		return false;
	}

	$time = '';
	$time .= '<div class="course-remaining-time message message-warning">';
	$time .= '<p>';
	$time .= sprintf( __( 'Bạn còn %s để học khóa học này', 'eduma' ), $remaining_time );
	$time .= '</p>';
	$time .= '</div>';
	echo $time;
}

add_action( 'learn-press/before-single-course-curriculum', 'thim_get_remaining_time', 5 );

add_action( 'learn-press/course-content-summary', 'thim_landing_tabs', 22 );

// Before Curiculumn on item single course
add_action( 'learn-press/before-single-course-curriculum', 'thim_before_curiculumn_item_func', 6 );

// add class fix style use don't description in page profile
add_filter( 'learn-press/profile/class', 'thim_class_has_description_user' );
function thim_class_has_description_user( $classes ) {
	$profile = LP_Profile::instance();
	$user    = $profile->get_user();
	if ( ! isset( $user ) ) {
		return;
	}
	$bio = $user->get_description();
	if ( ! $bio ) {
		$classes[] = 'no-bio-user';
	}

	return $classes;
}


if ( ! function_exists( 'thim_courses_loop_item_thumbnail' ) ) {
	function thim_courses_loop_item_thumbnail( $course = null ) {
		$course                      = LP_Global::course();
		$course_thumbnail_dimensions = learn_press_get_course_thumbnail_dimensions();
		$with_thumbnail              = $course_thumbnail_dimensions['width'];
		$height_thumbnail            = $course_thumbnail_dimensions['height'];
		if ( $course ) {
			echo '<div class="course-thumbnail">';
			echo '<a class="thumb" href="' . esc_url( get_the_permalink( $course->get_id() ) ) . '" >';
			echo thim_get_feature_image( get_post_thumbnail_id( $course->get_id() ), 'full', $with_thumbnail, $height_thumbnail, $course->get_title() );
			echo '</a>';
			do_action( 'thim_inner_thumbnail_course' );
			echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course->get_id() ) ) . '" >' . esc_html__( 'Tìm hiểu thêm', 'eduma' ) . '</a>';
			echo '</div>';
		}
	}
}
add_action( 'thim_courses_loop_item_thumb', 'thim_courses_loop_item_thumbnail' );
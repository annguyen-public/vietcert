<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/Prerequisites-Courses/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_Prerequisites_Courses' ) ) {
	/**
	 * Class LP_Addon_Prerequisites_Courses
	 */
	class LP_Addon_Prerequisites_Courses extends LP_Addon {

		/**
		 * @var string
		 */
		public $version = LP_ADDON_PREREQUISITES_COURSES_VER;

		/**
		 * @var string
		 */
		public $require_version = LP_ADDON_PREREQUISITES_COURSES_REQUIRE_VER;

		/**
		 * Path file addon
		 *
		 * @var string
		 */
		public $plugin_file = LP_ADDON_PREREQUISITES_COURSES_FILE;

		/**
		 * LP_Addon_Prerequisites_Courses constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Define Learnpress Prerequisites Courses constants.
		 *
		 * @since 3.0.0
		 */
		protected function _define_constants() {
			define( 'LP_PREREQUISITES_COURSES_PATH', dirname( LP_ADDON_PREREQUISITES_COURSES_FILE ) );
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @since 3.0.0
		 */
		protected function _includes() {
			// code
		}

		/**
		 * Hook into actions and filters.
		 */
		protected function _init_hooks() {

			// filter condition enroll, purchase course
			add_filter( 'learn-press/user/can-enroll-course', array( $this, 'can_enroll' ), 99, 3 );
			add_filter(
				'learn-press/user/can-purchase-course',
				array(
					$this,
					'can_purchase_course',
				),
				99,
				3
			);
			// show notice required pass prerequisites courses
			add_action( 'learn-press/course-buttons', array( $this, 'enroll_notice' ), 34 );
			if ( version_compare( LEARNPRESS_VERSION, '4.0.0-beta-0', '>=' ) ) {
				add_filter( 'lp/course/meta-box/fields/general', array( $this, 'admin_meta_box' ), 11 );
			} else {
				// add course meta box
				add_filter( 'learn_press_course_settings_meta_box_args', array( $this, 'admin_meta_box' ), 11 );
			}
			add_filter( 'learnpress/course/can-view-content', array( $this, 'can_view_content_course' ), 99, 3 );
			add_filter( 'learnpress/course/template/button-continue/can-show', array( $this, 'course_continue_button' ), 99, 3 );
		}

		/**
		 * Add prerequisites courses in course meta box.
		 *
		 * @param $meta_boxes
		 *
		 * @return mixed
		 * @since 3.0.0
		 */
		public function admin_meta_box( $meta_boxes ) {
			global $wpdb;
			$post_id = ! empty( $_REQUEST['post'] ) ? $_REQUEST['post'] : 0;
			if ( $post_id ) {
				$post_author = get_post_field( 'post_author', $post_id );
			} else {
				$post_author = get_current_user_id();
			}
			$current_user_id = get_current_user_id();
			$post_authors    = array( $post_author );

			if ( $current_user_id != $post_author ) {
				$post_authors[] = $current_user_id;
			}
			settype( $post_id, 'array' );
			$post_ids = join( ', ', $post_id );

			// admin select all publish courses
			if ( is_super_admin() ) {
				if ( get_post_status( $post_ids ) == false ) {
					$query = $wpdb->prepare(
						"
						SELECT ID, post_title FROM {$wpdb->posts}
						WHERE post_type = %s AND post_status = %s",
						'lp_course',
						'publish'
					);

				} else {
					$query = $wpdb->prepare(
						"
						SELECT ID, post_title FROM {$wpdb->posts}
						WHERE post_type = %s AND post_status = %s
						AND ID NOT IN(" . $post_ids . ")
						AND ID NOT IN( SELECT `post_id`FROM {$wpdb->postmeta} WHERE `meta_key`='_lp_course_prerequisite' and `meta_value` IN ({$post_ids}))",
						'lp_course',
						'publish'
					);
				}
			} else {
				// other author select all own publish courses
				$query = $wpdb->prepare(
					"
						SELECT ID, post_title FROM {$wpdb->posts}
						WHERE post_type = %s AND post_author in(" . implode( ',', $post_authors ) . ') AND post_status = %s
						AND ID NOT IN(' . $post_ids . ") AND ID NOT IN(
						SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key`='_lp_course_prerequisite' and `meta_value` IN ({$post_ids}))",
					'lp_course',
					'publish'
				);
			}

			$_options = array();
			$options  = $wpdb->get_results( $query );

			if ( $options ) {
				foreach ( $options as $option ) {
					// option for select courses
					$_options[ $option->ID ] = $option->post_title;
				}
			} else {
				$prerequisite[1]['desc'] = __( 'There is no course to select.', 'learnpress-prerequisites-courses' );
				unset( $prerequisite[0] );
			}

			if ( version_compare( LEARNPRESS_VERSION, '4.0.0-beta-0', '>=' ) ) {
				$meta_boxes['_lp_prerequisite_allow_purchase'] = new LP_Meta_Box_Checkbox_Field(
					esc_html__( 'Allow Purchase', 'learnpress-prerequisites-courses' ),
					esc_html__( 'Allow purchase course without finish prerequisites.', 'learnpress-prerequisites-courses' ),
					'no'
				);

				$meta_boxes['_lp_course_prerequisite'] = new LP_Meta_Box_Select_Field(
					esc_html__( 'Prerequisites Courses', 'learnpress-prerequisites-courses' ),
					esc_html__( 'Courses you have to finish before you can enroll to this course.', 'learnpress-prerequisites-courses' ),
					'',
					array(
						'options'  => $_options,
						'multiple' => true,
					)
				);

			} else {
				$prerequisite = array(
					array(
						'name' => __( 'Allow Purchase', 'learnpress-prerequisites-courses' ),
						'id'   => '_lp_prerequisite_allow_purchase',
						'type' => 'yes_no',
						'desc' => __(
							'Allow purchase course without finish prerequisites.',
							'learnpress-prerequisites-courses'
						),
						'std'  => 'no',
					),
					array(
						'name'        => __( 'Prerequisites Courses', 'learnpress-prerequisites-courses' ),
						'id'          => '_lp_course_prerequisite',
						'type'        => 'select_advanced',
						'multiple'    => true,
						'desc'        => __(
							'Courses you have to finish before you can enroll to this course.',
							'learnpress-prerequisites-courses'
						),
						'placeholder' => __( 'Select courses', 'learnpress-prerequisites-courses' ),
						'std'         => '',
						'options'     => $_options,
					),
				);

				foreach ( $prerequisite as $field ) {
					// add prerequisites option on top of admin settings course
					array_unshift( $meta_boxes['fields'], $field );
				}
			}

			return $meta_boxes;
		}

		/**
		 * Filer user can enroll course condition.
		 *
		 * @param bool|object|Mixed $output
		 * @param LP_Course $course
		 * @param bool $return_bool
		 *
		 * @return false|mixed|object
		 * @since 3.0.0
		 * @version 3.0.1
		 * @editor minhpd
		 */
		public function can_enroll( $output, LP_Course $course, bool $return_bool ) {

			$user_id   = get_current_user_id();
			$user      = learn_press_get_user( $user_id );
			$course_id = $course->get_id();

			// get prerequisites of course
			$prerequisites = $this->get_prerequisite_courses( $course_id );
			if ( $prerequisites ) {
				foreach ( $prerequisites as $course_prerequisite ) {
					if ( ! $user->has_passed_course( $course_prerequisite ) ) {
						if ( $return_bool ) {
							$output = false;
						} else {
							$output->check = false;
						}
						break;
					}
				}
			}

			return $output;
		}
		/**
		 * Filer user can view content course condition.
		 *
		 * @param $view
		 * @param $user_id
		 * @param $course
		 *
		 * @since 4.0.0
		 */
		public function can_view_content_course( $view, $user_id, $course ) {

			$user      = learn_press_get_user( $user_id );
			$course_id = $course->get_id();
			// get prerequisites of course
			$prerequisites = $this->get_prerequisite_courses( $course_id );
			if ( $prerequisites ) {

				foreach ( $prerequisites as $course_prerequisite ) {
					if ( ! $user->has_passed_course( $course_prerequisite ) ) {
						$view->flag    = false;
						$view->message = __(
							'This content is protected, please pass the prerequisites course(s) to view this content!',
							'learnpress-prerequisites-courses'
						);
						break;
					}
				}
			}
			return $view;

		}

		/**
		 * Check course of user is enrolled or finished
		 *
		 * @param bool   $can_show
		 * @param $user
		 * @param $course
		 * @return bool
		 * @throws Exception
		 */
		public function course_continue_button( bool $can_show, $user, $course ) {
			// get prerequisites of course
			$prerequisites = $this->get_prerequisite_courses( $course->get_id() );
			if ( $prerequisites ) {
				foreach ( $prerequisites as $value ) {
					if ( ! $user->has_passed_course( $value ) ) {
						$can_show = false;
						break;
					}
				}
			}

			return $can_show;
		}


		/**
		 * Filer user can purchase course condition.
		 *
		 * @param $purchasable
		 * @param $user_id
		 * @param $course_id
		 *
		 * @return bool
		 */
		public function can_purchase_course( $purchasable, $user_id, $course_id ) {

			if ( ! $purchasable ) {
				return false;
			}

			$user   = learn_press_get_user( $user_id );
			$course = learn_press_get_course( $course_id );
			// get prerequisites of course
			$prerequisites = $this->get_prerequisite_courses( $course_id );
			if ( $prerequisites ) {
				// allow purchase
				$allow_purchase   = get_post_meta( $course_id, '_lp_prerequisite_allow_purchase', true );
				$allow_repurchase = $course->allow_repurchase();

				if ( ! empty( $allow_purchase ) && $allow_purchase == 'yes' ) {
					if ( $user->has_purchased_course( $course_id ) ) {
						if ( ! empty( $allow_repurchase ) && $allow_repurchase == 'yes' ) {
							if ( $user->has_passed_course( $course_id ) ) {
								$purchasable = true;
							}
						}
					}
				} else {
					foreach ( $prerequisites as $course_prerequisite ) {
						if ( ! $user->has_passed_course( $course_prerequisite ) ) {
							$purchasable = false;
							break;
						}
					}
				}
			}

			return $purchasable;
		}

		/**
		 * Check user has passed course condition.
		 *
		 * @param      $course_id
		 * @param null      $user_id
		 *
		 * @return mixed
		 * @since 3.0.0
		 */
		public function has_passed_course( $course_id, $user_id = null ) {
			if ( ! $user_id ) {
				$user_id = learn_press_get_current_user_id();
			}
			$user = learn_press_get_user( $user_id );
			// if ( ! $user->has_enrolled_course( $course_id ) ) {
			// return false;
			// }
			$has_passed = $user->has_passed_course( $course_id );

			return $has_passed !== false;
		}

		/**
		 * Get prerequisites of course.
		 *
		 * @param $course_id
		 *
		 * @return array|bool
		 */
		public function get_prerequisite_courses( $course_id ) {
			if ( ! $course_id ) {
				$course_id = learn_press_get_course_id();
			}
			if ( version_compare( LEARNPRESS_VERSION, '4.0.0-beta-0', '>=' ) ) {
				return get_post_meta( $course_id, '_lp_course_prerequisite', true );
			} else {
				return get_post_meta( $course_id, '_lp_course_prerequisite', false );
			}

		}

		/**`
		 * Show notice required pass prerequisites courses.
		 *
		 * @since 3.0.0
		 */
		public function enroll_notice() {
			global $post;
			// course
			$course = learn_press_get_course( $post->ID );

			if ( $course->get_external_link() ) {
				return;
			}

			// get prerequisites of course
			$prerequisites = $this->get_prerequisite_courses( $post->ID );
			if ( ! $prerequisites ) {
				return;
			}

			$required_courses = array();

			foreach ( $prerequisites as $course_id ) {
				if ( ! $this->has_passed_course( $course_id ) ) {
					$required_courses[] = $course_id;
				}
			}

			if ( ! $required_courses ) {
				return;
			}

			$notice       = __(
				'NOTE: You have to pass these courses before you can enroll this course.',
				'learnpress-prerequisites-courses'
			);
			$user         = learn_press_get_current_user();
			$list_courses = array();
			foreach ( $prerequisites as $course_id ) {
				$status = '';
				$grade  = '';
				if ( $this->has_passed_course( $course_id ) ) {
					$status      = $user->get_course_status( $course_id );
					$course_data = $user->get_course_data( $course_id );
					$grade       = '<span style="color: green;">( ' . learn_press_get_graduation_text( $course_data->get_grade() ) . ' )</span>';
				}
				$list_courses[] = '<a href="' . get_permalink( $course_id ) . '">' . get_the_title( $course_id ) . '</a>' . $grade;
			}
			if ( $list_courses ) {
				$message = sprintf(
					'<ul style="margin: 0 0 0 20px;"><li>%s</li></ul>',
					join( '</li><li>', $list_courses )
				);
				$notice .= $message;
			}
			learn_press_display_message( $notice, 'error' );
		}
	}
}

add_action( 'plugins_loaded', array( 'LP_Addon_Prerequisites_Courses', 'instance' ) );

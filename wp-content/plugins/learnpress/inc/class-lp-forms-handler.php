<?php
/**
 * Class LP_Forms_Handler
 *
 * Process action for submitting forms
 *
 * @since 4.0.0
 * @author ThimPress <nhamdv>
 */
class LP_Forms_Handler {

	/**
	 * Become a teacher form
	 */
	public static function process_become_teacher() {
		$args = array(
			'bat_name'    => isset( $_POST['bat_name'] ) ? LP_Helper::sanitize_params_submitted( $_POST['bat_name'] ) : '',
			'bat_email'   => isset( $_POST['bat_email'] ) ? LP_Helper::sanitize_params_submitted( $_POST['bat_email'] ) : '',
			'bat_phone'   => isset( $_POST['bat_phone'] ) ? LP_Helper::sanitize_params_submitted( $_POST['bat_phone'] ) : '',
			'bat_message' => isset( $_POST['bat_message'] ) ? LP_Helper::sanitize_params_submitted( $_POST['bat_message'] ) : '',
		);

		$result = array(
			'message' => array(),
			'result'  => 'success',
		);

		if ( ( empty( $args['bat_name'] ) ) && $result['result'] !== 'error' ) {
			$result = array(
				'message' => learn_press_get_message( __( 'Vui lòng nhập  vào tên đăng nhập hợp lệ.', 'learnpress' ), 'error' ),
				'result'  => 'error',
			);
		}

		if ( ( empty( $args['bat_email'] ) || ! is_email( $args['bat_email'] ) ) && $result['result'] !== 'error' ) {
			$result = array(
				'message' => learn_press_get_message( __( 'Vui lòng cung cấp địa chỉ email hợp lệ.', 'learnpress' ), 'error' ),
				'result'  => 'error',
			);
		}

		if ( ( ! email_exists( $args['bat_email'] ) ) && $result['result'] !== 'error' ) {
			$result = array(
				'message' => learn_press_get_message( __( 'Email không tồn tại!', 'learnpress' ), 'error' ),
				'result'  => 'error',
			);
		}

		$result = apply_filters( 'learn-press/become-teacher-request-result', $result );

		if ( $result['result'] === 'success' ) {
			$result['message'][] = learn_press_get_message( __( 'Cảm ơn! Yêu cầu đã được gửi.', 'learnpress' ), 'success' );
			$user                = get_user_by( 'email', $args['bat_email'] );

			update_user_meta( $user->ID, '_requested_become_teacher', 'yes' );
			do_action( 'learn-press/become-a-teacher-sent', $args );
		}

		learn_press_maybe_send_json( $result );
	}

	/**
	 * Process the login form.
	 *
	 * @throws Exception On login error.
	 * @author Thimpress <nhamdv>
	 */
	public static function process_login() {
		if ( ! LP_Request::verify_nonce( 'learn-press-login' ) ) {
			return;
		}

		if ( isset( $_POST['username'], $_POST['password'] ) ) {
			try {
				$username = trim( LP_Helper::sanitize_params_submitted( $_POST['username'] ) );
				$password = $_POST['password'];
				$remember = LP_Request::get_string( 'rememberme' );

				if ( empty( $username ) ) {
					throw new Exception( '<strong>' . __( 'Lỗi:', 'learnpress' ) . '</strong> ' . __( 'Tên đăng nhập là mục bắt buộc nhập', 'learnpress' ) );
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $username ) ? 'email' : 'login', $username );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
					}
				}

				$user = wp_signon(
					apply_filters(
						'learnpress_login_credentials',
						array(
							'user_login'    => $username,
							'user_password' => $password,
							'remember'      => $remember,
						)
					),
					is_ssl()
				);

				if ( is_wp_error( $user ) ) {
					throw new Exception( $user->get_error_message() );
				} else {
					if ( ! empty( $_POST['redirect'] ) ) {
						$redirect = wp_unslash( $_POST['redirect'] );
					} elseif ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
						$redirect = wp_unslash( $_REQUEST['_wp_http_referer'] );
					} else {
						$redirect = LP_Request::get_redirect( learn_press_get_page_link( 'profile' ) );
					}

					wp_redirect( wp_validate_redirect( $redirect, learn_press_get_current_url() ) );
					exit();
				}
			} catch ( Exception $e ) {
				learn_press_add_message( $e->getMessage(), 'error' );
			}
		}
	}

	/**
	 * Process register form.
	 *
	 * @throws Exception On Error register.
	 * @author ThimPress <nhamdv>
	 */
	public static function process_register() {
		if ( ! LP_Request::verify_nonce( 'learn-press-register' ) ) {
			return;
		}

		$username         = isset( $_POST['reg_username'] ) ? LP_Helper::sanitize_params_submitted( $_POST['reg_username'] ) : '';
		$email            = isset( $_POST['reg_email'] ) ? LP_Helper::sanitize_params_submitted( $_POST['reg_email'] ) : '';
		$password         = $_POST['reg_password'] ?? '';
		$confirm_password = $_POST['reg_password2'] ?? '';
		$first_name       = isset( $_POST['reg_first_name'] ) ? LP_Helper::sanitize_params_submitted( $_POST['reg_first_name'] ) : '';
		$last_name        = isset( $_POST['reg_last_name'] ) ? LP_Helper::sanitize_params_submitted( $_POST['reg_last_name'] ) : '';
		$display_name     = isset( $_POST['reg_display_name'] ) ? LP_Helper::sanitize_params_submitted( $_POST['reg_display_name'] ) : '';
		$update_meta      = isset( $_POST['_lp_custom_register_form'] ) ? LP_Helper::sanitize_params_submitted( $_POST['_lp_custom_register_form'] ) : array();

		try {
			$new_customer = self::learnpress_create_new_customer(
				sanitize_email( $email ),
				$username,
				$password,
				$confirm_password,
				array(
					'first_name'   => $first_name,
					'last_name'    => $last_name,
					'display_name' => $display_name,
				),
				$update_meta
			);

			if ( is_wp_error( $new_customer ) ) {
				throw new Exception( $new_customer->get_error_message() );
			} else {
				wp_new_user_notification( $new_customer );
			}

			// Send email become a teacher.
			$is_become_a_teacher = false;
			if ( LP_Settings::get_option( 'instructor_registration', 'no' ) == 'yes' && isset( $_POST['become_teacher'] ) ) {
				update_user_meta( $new_customer, '_requested_become_teacher', 'yes' );
				do_action(
					'learn-press/become-a-teacher-sent',
					array(
						'bat_email'   => $email,
						'bat_phone'   => '',
						'bat_message' => apply_filters( 'learnpress_become_instructor_message', esc_html__( 'Tôi muốn trở thành cộng tác viên.', 'learnpress' ) ),
					)
				);

				$is_become_a_teacher = true;
			}

			/**
			 * Auto login user
			 * Must set code below after Send email become a teacher
			 * because 'none' check by "check_ajax_referer" will not valid for send mail background on WP_Async_Request
			 */
			wp_set_current_user( $new_customer );
			wp_set_auth_cookie( $new_customer, true );

			$message_success = $username . __( ' đã được tạo thành công!', 'learnpress' );

			if ( $is_become_a_teacher ) {
				$message_success .= '<br/>' . __( 'Yêu cầu trở thành cộng tác viên của bạn đã được ghi nhận. Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!', 'learnpress' );
			}

			learn_press_add_message( $message_success, 'success' );

			if ( ! empty( $_POST['redirect'] ) ) {
				$redirect = wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) );
			} elseif ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
				$redirect = wp_unslash( $_REQUEST['_wp_http_referer'] );
			} else {
				$redirect = LP_Request::get_redirect( learn_press_get_page_link( 'profile' ) );
			}

			wp_redirect( wp_validate_redirect( $redirect, learn_press_get_current_url() ) );
			exit();

		} catch ( Exception $e ) {
			if ( $e->getMessage() ) {
				learn_press_add_message( $e->getMessage(), 'error' );
			}
		}
	}

	/**
	 * New create customer.
	 *
	 * @author ThimPress <nhamdv>
	 */
	public static function learnpress_create_new_customer( $email, $username = '', $password = '', $confirm_password = '', $args = array(), $update_meta = array() ) {
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'registration-error-invalid-email', __( 'Vui lòng cung cấp email hợp lệ.', 'learnpress' ) );
		}

		if ( email_exists( $email ) ) {
			return new WP_Error( 'registration-error-email-exists', apply_filters( 'learnpress_registration_error_email_exists', __( 'Email này đã được đăng ký.', 'learnpress' ), $email ) );
		}

		$username = sanitize_user( $username );

		if ( empty( $username ) || ! validate_username( $username ) ) {
			return new WP_Error( 'registration-error-invalid-username', __( 'Vui lòng cung cấp tên đăng nhập hợp lệ.', 'learnpress' ) );
		}

		if ( username_exists( $username ) ) {
			return new WP_Error( 'registration-error-username-exists', __( 'Tên đăng nhập không khả dụng.', 'learnpress' ) );
		}

		if ( apply_filters( 'learnpress_registration_generate_password', false ) ) {
			$password = wp_generate_password();
		}

		if ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', __( 'Vui lòng nhập mật khẩu.', 'learnpress' ) );
		}

		if ( strlen( $password ) < 6 ) {
			return new WP_Error( 'registration-error-short-password', __( 'Mật khẩu quá ngắn!', 'learnpress' ) );
		}

		if ( preg_match( '#\s+#', $password ) ) {
			return new WP_Error( 'registration-error-spacing-password', __( 'Mật khẩu không được chứa dấu cách!', 'learnpress' ) );
		}

		if ( empty( $confirm_password ) ) {
			return new WP_Error( 'registration-error-missing-confirm-password', __( 'Ô xác nhận mật khẩu chưa được nhập.', 'learnpress' ) );
		}

		if ( $password !== $confirm_password ) {
			return new WP_Error( 'registration-error-confirm-password', __( 'Mật khẩu xác nhận không khớp!.', 'learnpress' ) );
		}

		$custom_fields = LP()->settings()->get( 'register_profile_fields' );

		if ( $custom_fields && ! empty( $update_meta ) ) {
			foreach ( $custom_fields as $field ) {
				if ( $field['required'] === 'yes' && empty( $update_meta[ $field['id'] ] ) ) {
					return new WP_Error( 'registration-custom-exists', $field['name'] . __( ' là mục bắt buộc nhập.', 'learnpress' ) );
				}
			}
		}

		$errors = new WP_Error();

		do_action( 'learnpress_register_post', $username, $email, $errors );

		$errors = apply_filters( 'learnpress_registration_errors', $errors, $username, $email );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$new_customer_data = apply_filters(
			'learnpress_new_customer_data',
			array_merge(
				$args,
				array(
					'user_login' => $username,
					'user_pass'  => $password,
					'user_email' => $email,
				)
			)
		);

		$customer_id = wp_insert_user( $new_customer_data );

		if ( ! empty( $update_meta ) ) {
			lp_user_custom_register_fields( $customer_id, $update_meta );
		}

		if ( is_wp_error( $customer_id ) ) {
			return $customer_id;
		}

		return $customer_id;
	}

	public static function update_user_data( $update_data, $update_meta ) {
		$user_id      = get_current_user_id();
		$current_user = get_user_by( 'id', $user_id );

		if ( empty( $update_data['user_email'] ) ) {
			return new WP_Error( 'exist_email', esc_html__( 'Email là mục bắt buộc nhập.', 'learnpress' ) );
		}

		if ( empty( $update_data['display_name'] ) ) {
			return new WP_Error( 'exist_display_name', esc_html__( 'Tên hiển thị là mục bắt buộc nhập.', 'learnpress' ) );
		}

		if ( is_email( $update_data['display_name'] ) ) {
			return new WP_Error( 'error_display_name', esc_html__( 'Tên hiển thị không thể là email vì vấn đề bảo mật.', 'learnpress' ) );
		}

		if ( ! is_email( $update_data['user_email'] ) ) {
			return new WP_Error( 'error_email', esc_html__( 'Tên hiển thị không thể là email vì vấn để liên quan đến chính sách.', 'learnpress' ) );
		} elseif ( email_exists( $update_data['user_email'] ) && $update_data['user_email'] !== $current_user->user_email ) {
			return new WP_Error( 'error_email', esc_html__( 'Email này đã được đăng ký.', 'learnpress' ) );
		}

		$custom_fields = LP()->settings()->get( 'register_profile_fields' );

		if ( $custom_fields && ! empty( $update_meta ) ) {
			foreach ( $custom_fields as $field ) {
				if ( $field['required'] === 'yes' && empty( $update_meta[ $field['id'] ] ) ) {
					return new WP_Error( 'registration-custom-exists', $field['name'] . __( ' là mục bắt buộc nhập.', 'learnpress' ) );
				}
			}
		}

		$update_data = apply_filters( 'learn-press/update-profile-basic-information-data', $update_data );

		$return = wp_update_user( $update_data );

		if ( ! empty( $update_meta ) ) {
			lp_user_custom_register_fields( $user_id, $update_meta );
		}

		if ( is_wp_error( $return ) ) {
			return $return;
		}

		return $return;
	}

	public static function retrieve_password( $user_login ) {
		$login = isset( $user_login ) ? sanitize_user( wp_unslash( $user_login ) ) : '';

		if ( empty( $login ) ) {
			return new WP_Error( 'error_santize_login', esc_html__( 'Vui lòng nhập tên đăng nhập hoặc email.', 'learnpress' ) );
		} else {
			// Check on username first, as customers can use emails as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) && apply_filters( 'learnpress_get_username_from_email', true ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new WP_Error();

		do_action( 'lostpassword_post', $errors, $user_data );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		if ( ! $user_data ) {
			return new WP_Error( 'error_not_user', esc_html__( 'Tên đăng nhặp hoặc email không hợp lệ.', 'learnpress' ) );
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			return new WP_Error( 'error_not_user', esc_html__( 'Tên đăng nhặp hoặc email không hợp lệ.', 'learnpress' ) );
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		do_action( 'retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
			return new WP_Error( 'error_not_allow', esc_html__( 'Người dùng này không được phép đặt lại mật khẩu.', 'learnpress' ) );
		} elseif ( is_wp_error( $allow ) ) {
			return $allow;
		}

		$key = get_password_reset_key( $user_data );

		if ( class_exists( 'LP_Email_Reset_Password' ) ) {
			$email = new LP_Email_Reset_Password();

			$email->handle(
				array(
					'reset_key'  => $key,
					'user_login' => $user_login,
				)
			);
		}

		return true;
	}

	public static function init() {
		self::process_login();
		self::process_register();
	}
}


<?php
/**
 * Class LP_Strings
 */
class LP_Strings {

	/**
	 * @since 3.3.0
	 *
	 * @var array
	 */
	protected static $strings = array();

	/**
	 * @since 3.2.0
	 * @TODO should remove - tungnx, no important
	 */
	public static function load() {
		$strings = apply_filters(
			'learnpress/strings',
			array(
				'you_have_completed_quiz' => __( 'Bạn đã hoàn thành bài tập rồi.', 'learnpress' ),
				'confirm-redo-quiz'       => __( 'Bạn có muốn làm lại bài tập "%s" không?', 'learnpress' ),
				'confirm-complete-quiz'   => __( 'Bạn có muốn hoàn thành bài tập "%s" không?', 'learnpress' ),
				'confirm-complete-lesson' => __( 'Bạn có muốn hoàn thành bài học "%s" không?', 'learnpress' ),
				'confirm-finish-course'   => __( 'Bạn có muốn kết thúc khóa học "%s" không?', 'learnpress' ),
				'confirm-retake-course'   => __( 'Bạn có muốn học lại khóa học "%s" không?', 'learnpress' ),
			)
		);

		self::$strings = $strings;
	}

	/**
	 * @param string $str
	 * @param string $context
	 * @param string $args
	 *
	 * @return mixed|string
	 */
	public static function get( $str, $context = '', $args = '' ) {
		$string = $str;

		$strings = self::$strings;

		if ( $strings ) {
			if ( array_key_exists( $str, $strings ) ) {
				$texts = $strings[ $str ];

				if ( is_string( $texts ) ) {
					$string = $texts;
				} elseif ( $context && array_key_exists( $context, $texts ) ) {
					$string = $texts[ $context ];
				} else {
					$string = reset( $texts );
				}
			}
		}

		return is_array( $args ) ? vsprintf( $string, $args ) : $string;
	}

	public static function esc_attr( $str, $context = '', $args = '' ) {
		return esc_attr( self::get( $str, $context, $args ) );
	}

	public static function esc_attr_e( $str, $context = '', $args = '' ) {
		echo esc_attr( self::get( $str, $context, $args ) );
	}

	public static function output( $str, $context = '', $args = '' ) {

	}
}

LP_Strings::load();

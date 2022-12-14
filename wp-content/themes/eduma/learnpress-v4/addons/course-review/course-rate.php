<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course_id       = get_the_ID();
$course_rate_res = learn_press_get_course_rate( $course_id, false );
$course_rate     = $course_rate_res['rated'];
$total           = $course_rate_res['total'];

?>
<div class="course-rating">
	<h3><?php esc_html_e( 'Đánh giá', 'eduma' ); ?></h3>
	<div class="average-rating">
		<p class="rating-title"><?php esc_html_e( 'Điểm đánh giá trung bình', 'eduma' ); ?></p>

		<div class="rating-box">
			<div class="average-value" itemprop="ratingValue"><?php echo ( $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></div>
			<div class="review-star">
				<?php thim_print_rating( $course_rate ); ?>
			</div>
			<div class="review-amount">
				<meta itemprop="ratingCount" content="<?php echo $total?>>"/>
				<?php $total ? printf( _n( '%1$s đánh giá', '%1$s đánh giá', $total, 'eduma' ), number_format_i18n( $total ) ) : esc_html_e( '0 đánh giá', 'eduma' ); ?>
			</div>
		</div>
	</div>
	<div class="detailed-rating">
		<p class="rating-title"><?php esc_html_e( 'Chi tiết đánh giá', 'eduma' ); ?></p>

		<div class="rating-box">
			<div class="detailed-rating">
				<?php
				if ( isset( $course_rate_res['items'] ) && !empty( $course_rate_res['items'] ) ):
					foreach ( $course_rate_res['items'] as $item ):
						$percent = round( $item['percent'], 0 );
						?>
						<div class="stars">
							<div class="key">
								<?php if ( $item['rated'] == '1' ) {
									esc_html_e( $item['rated'] ); ?><?php esc_attr__( ' Sao', 'eduma' );
								} else {
									esc_html_e( $item['rated'] ); ?><?php esc_attr__( ' Sao', 'eduma' );
								} ?>
							</div>
							<div class="bar">
								<div class="full_bar">
									<div style="width:<?php echo $percent; ?>% "></div>
								</div>
							</div>
							<span><?php echo esc_html( $percent ); ?>%</span>
						</div>
					<?php
					endforeach;
				endif;
				?>
			</div>
		</div>
	</div>
</div>

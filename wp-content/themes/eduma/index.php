<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Eduma
 * @since Eduma
 */

get_header();
/**
 * thim_wrapper_loop_start hook
 *
 * @hooked thim_wrapper_loop_end - 1
 * @hooked thim_wapper_page_title - 5
 * @hooked thim_wrapper_loop_start - 30
 */
do_action( 'thim_wrapper_loop_start' );

?>

<?php
$class_archive  = '';
$is_grid_layout = get_theme_mod( 'thim_front_page_cate_display_layout', false );
$layout_type    = $is_grid_layout ? 'grid' : '';
if ( $layout_type == 'grid' ) {
	$class_archive = ' blog-switch-layout blog-list';
	global $post, $wp_query;

	if ( is_category() ) {
		$total = get_queried_object();
		$total = $total->count;
	} elseif ( ! empty( $_REQUEST['s'] ) ) {
		$total = $wp_query->found_posts;
	} else {
		$total = wp_count_posts( 'post' );
		$total = $total->publish;
	}

	if ( $total == 0 ) {
		echo '<p class="message message-error">' . esc_html__( 'Không có bài đăng nào!', 'eduma' ) . '</p>';

		return;
	} elseif ( $total == 1 ) {
		$index = esc_html__( 'Đang hiển thị chỉ một bài đăng', 'eduma' );
	} else {
		$courses_per_page = absint( get_option( 'posts_per_page' ) );
		$paged            = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		$from = 1 + ( $paged - 1 ) * $courses_per_page;
		$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

		if ( $from == $to ) {
			$index = sprintf(
				esc_html__( 'Hiển thị kết quả cuối cùng của %s kết quả', 'eduma' ),
				$total
			);
		} else {
			$index = sprintf(
				esc_html__( ' %s kết quả kết quả', 'eduma' ),
				$from,
				$to,
				$total
			);
		}
	}
}


if ( have_posts() ) :?>
	<div id="blog-archive" class="blog-content<?php echo esc_attr( $class_archive ); ?>">
		<?php if ( $layout_type == 'grid' ): ?>
			<div class="thim-blog-top switch-layout-container">
				<div class="switch-layout">
					<a href="#" class="list switchToGrid  switch-active"><i class="fa fa-th-large"></i></a>
					<a href="#" class="grid switchToList"><i class="fa fa-list-ul"></i></a>
				</div>
				<div class="post-index"><?php echo esc_html( $index ); ?></div>
			</div>
			<div class="row">
				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content-grid' );
				endwhile;
				?>
			</div>
		<?php else: ?>
			<div class="row">
				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content' );
				endwhile;
				?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	thim_paging_nav();
else :
	get_template_part( 'content', 'none' );
endif;

/**
 * thim_wrapper_loop_end hook
 *
 * @hooked thim_wrapper_loop_end - 10
 * @hooked thim_wrapper_div_close - 30
 */
do_action( 'thim_wrapper_loop_end' );

get_footer();
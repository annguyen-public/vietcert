<?php
/**
 * Template for displaying block lesson content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-lesson/block-content.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();
?>

<div class="learn-press-content-protected-message content-item-block">
	<?php esc_html_e( 'Không thể truy cập được nội dung vì đã vượt quá thời hạn khóa học.', 'learnpress' ); ?>
</div>

<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bootstarter4
 */

?>

	</div></div><!-- #content -->

	<footer id="colophon" class="site-footer">
			<div class="container-fluid">
	            <a target="_blank" href="<?php echo esc_url( __('https://github.com/alexweblab/bootstarter-4-wordpress-theme', 'bootstarter') ); ?>" title="<?php esc_attr_e('Bootstarter 4 WordPress Theme', 'bootstarter'); ?>"><?php esc_html_e('Bootstarter 4 WordPress Theme', 'bootstarter'); ?></a>
	            <?php printf( esc_html__( 'powered by %s.', 'bootstarter' ), '<a target="_blank" href="https://wordpress.org/">WordPress</a>' ); ?>
			</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

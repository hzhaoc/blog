<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Prime Hosting
 */

get_header();
?>
	<section class="page_404">
		<div class="container">
			<div class="justify-content-center content-center text-center">
				<div class="error-box">
					<h1><?php esc_html_e( '404', 'prime-hosting' ); ?></h1>
					<span><?php esc_html_e( 'OOOPS!', 'prime-hosting' ); ?></span>
					<p class="my-3"><?php esc_html_e( 'Something Went Wrong. Go Back Home', 'prime-hosting' ); ?></p>
					<?php get_search_form(); ?>
					<br>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn  btn-xs btn-primary"><?php esc_html_e( 'Back To Home', 'prime-hosting' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
get_footer();

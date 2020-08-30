<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Prime Hosting
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<section class="section pb-50 mt-80">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 col-md-6 mb-30">
							<?php
							if ( have_posts() ) {

								/* Start the Loop */
								while ( have_posts() ) :
									the_post();

									get_template_part( 'template-parts/content', 'search' );

								endwhile;

								?>
								<div class="pagination-wrap">
									<?php
									the_posts_pagination(
										array(
											'mid_size'  => 3,
											'prev_text' => esc_html__( 'Previous', 'prime-hosting' ) . '&nbsp;',
											'next_text' => '&nbsp;' . esc_html__( 'Next', 'prime-hosting' ) . '</span>',
										)
									);
									?>
								</div>
								<?php
							} else {
								get_template_part( 'template-parts/content', 'none' );
							}
							?>
						</div>
						<div class="col-lg-4 col-md-6  blog_sidebar">
							<?php get_sidebar(); ?>
						</div><!-- end sidebar -->
					</div>
				</div>
			</section>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();

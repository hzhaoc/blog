<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
							while ( have_posts() ) :
								the_post();

								get_template_part( 'template-parts/content', get_post_type() );
								?>

								<div class="news_box bg-primary p-4">
									<div class="row">
										<div class="col-lg-3 news_box_img ">
										<?php echo get_avatar( get_the_author_meta( 'ID' ), '120' ); ?>
										</div>
										<div class="col-lg-9">
											<div class="news_box_cont">
											<?php
											echo '<h3 class="text-white">', the_author_posts_link(), '</h3>';
											echo '<p>' . wp_kses_post( the_author_meta( 'user_description' ) ) . '</p>';
											?>
											</div>
										</div>
									</div>
								</div><!-- end author box news_box -->

								<?php
								the_post_navigation(
									array(
										'prev_text' => esc_html__( 'Previous', 'prime-hosting' ) . '&nbsp;',
										'next_text' => '&nbsp;' . esc_html__( 'Next', 'prime-hosting' ) . '</span>',
									)
								);
								?>
								<div class="blog-reset">
								<?php
								prime_hosting_related_posts( get_the_ID() );

								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
								echo '</div><!-- end blog-reset -->';
							endwhile; // End of the loop.
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

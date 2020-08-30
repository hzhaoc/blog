<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime Hosting
 */

$prime_hosting_has_thumbnail = '';
if ( has_post_thumbnail() && get_theme_mod( 'show_featured_image', true ) === true ) {
	$prime_hosting_has_thumbnail = 'has_thumbnail';
}

if ( is_singular() ) {
	$prime_hosting_post_class = 'blog_post card border-0 ';
} else {
	$prime_hosting_post_class = 'blog_post card border-0 shadow ';
}
?><article id="post-<?php the_ID(); ?>" <?php post_class( $prime_hosting_post_class . $prime_hosting_has_thumbnail ); ?>>

	<?php
	prime_hosting_post_formats();

	if ( is_singular() ) {
		prime_hosting_social_sharing();
	}
	?>
	<div class="card-body positon-relative">
		<?php
		if ( ! is_singular() ) {
			?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn_detail"><i class="fas fa-long-arrow-alt-right"></i></a>
			<?php
		}

		if ( is_single() && ! is_page() && get_theme_mod( 'sharing_posts', true ) === true ) {
			?>
			<a data-toggle="collapse" data-target="#social_share" class="btn_detail"><i class="fas fa-share-alt"></i></a>
			<?php
		}
		?>
		<span class="date"> <?php prime_hosting_posted_on(); ?> </span>
		<?php
		if ( is_singular() ) {
			the_title( '<h2 class="card-title">', '</h2>' );
		} else {
			the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		if ( ! is_single() ) {
			the_excerpt();
		}

		if ( 'post' === get_post_type() ) {
			?>
			<ul class="post_info d-flex flex-wrap">
				<li> <i class="fas fa-user"></i> <?php prime_hosting_posted_by(); ?> </li>
				<li class="comments-popup"> <span> <i class="far fa-comments"> </i> <?php comments_popup_link(); ?> <span> </li>
				<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					echo '<li> <a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="tips">' . esc_html( $categories[0]->name ) . '</a></li>';
				}
				?>
			</ul>
			<?php
		}

		if ( is_single() ) {
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'prime-hosting' ),
					'after'  => '</div>',
				)
			);
			?>
			<hr>
			<div class="blog-post-bottom-panel post_info d-flex align-items-center ml-3">
				<p class="mb-0"> <i class="fa fa-tag text-primary"></i>
				<?php
				if ( 'post' === get_post_type() ) {
					the_tags( __( 'Tags: ', 'prime-hosting' ), ', ' );
				}
				?>
				</p>
			</div><!-- End bottom panel -->
			<?php
		}
		?>
	</div><!-- End card body -->

</article><!-- #post-<?php the_ID(); ?> -->

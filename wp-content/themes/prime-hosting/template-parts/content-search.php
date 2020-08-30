<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime Hosting
 */

$prime_hosting_has_thumbnail = '';
if ( has_post_thumbnail() && get_theme_mod( 'show_featured_image', true ) === true ) {
	$prime_hosting_has_thumbnail = 'has_thumbnail';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog_post card border-0 shadow ' . $prime_hosting_has_thumbnail ); ?>>
	<?php
	prime_hosting_post_formats();
	?>
	<div class="card-body positon-relative">
		<?php
		if ( ! is_singular() ) {
			?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn_detail"><i class="fas fa-long-arrow-alt-right"></i></a>
			<?php
		}
		?>
		<span class="date"> <?php prime_hosting_posted_on(); ?> </span>
		<?php
		the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		the_excerpt();

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

		?>
	</div><!-- End card body -->

</article><!-- #post-<?php the_ID(); ?> -->

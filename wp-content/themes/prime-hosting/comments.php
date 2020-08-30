<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime Hosting
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area p-4 mb-60">
	<section class="comment_area">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-spacing-100 font-weight-normal">
			<?php
			$prime_hosting_comment_count = get_comments_number();
			if ( '1' === $prime_hosting_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One comment', 'prime-hosting' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s comment', '%1$s comments', $prime_hosting_comment_count, 'comments title', 'prime-hosting' ) ),
					number_format_i18n( $prime_hosting_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<div class="comment-list bg-gray mb-60">
			<?php
			wp_list_comments(
				array(
					'style'       => 'div',
					'short_ping'  => true,
					'max_depth'   => 3,
					'avatar_size' => 120,
					'walker'      => new Prime_Hosting_Walker_Comment(),
				)
			);
			?>
		</div><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'prime-hosting' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	comment_form();
	?>
	</section>
</div><!-- #comments -->

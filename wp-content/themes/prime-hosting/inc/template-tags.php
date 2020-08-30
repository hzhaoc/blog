<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Prime Hosting
 */

if ( ! function_exists( 'prime_hosting_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function prime_hosting_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = $time_string;

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'prime_hosting_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function prime_hosting_posted_by() {
		echo '<span class="author vcard">' . 
			'<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' .
			esc_html_x( 'by', 'post author', 'prime-hosting' ) . ' ' . esc_html( get_the_author() ) . '</a></span>';
	}
endif;

if ( ! function_exists( 'prime_hosting_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function prime_hosting_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() || get_theme_mod( 'show_featured_image', true ) === false ) {
			return;
		}
		if ( is_singular() ) {
			?>
			<div class="post_img positon-relative">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->
			<?php
		} else {
			?>
			<div class="post_img">
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</a>
			</div>
			<?php
		} // End is_singular().
	}
}

if ( ! function_exists( 'prime_hosting_social_sharing' ) ) {
	function prime_hosting_social_sharing() {
		if ( is_page() && get_theme_mod( 'sharing_pages', false ) === true || is_single() && ! is_page() && get_theme_mod( 'sharing_posts', true ) === true ) {

			if ( get_theme_mod( 'sharing_facebook', true ) === true ||
				get_theme_mod( 'sharing_twitter', true ) === true ||
				get_theme_mod( 'sharing_linkedin', true ) === true ||
				get_theme_mod( 'sharing_reddit', true ) === true ||
				get_theme_mod( 'sharing_pinterest', true ) === true ||
				get_theme_mod( 'sharing_email', true ) === true
			) {
				global $post;
				?>
				<div id="social_share" class="p_abslute post_share collapse">
				<ul class="team_social block">
				<?php
				if ( get_theme_mod( 'sharing_facebook', true ) === true ) {
					?>
					<li><a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u?' . get_permalink( $post->ID ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'prime-hosting' ); ?></span><i class="fab fa-facebook-f"></i></a></li>
					<?php
				}

				if ( get_theme_mod( 'sharing_twitter', true ) === true ) {
					?>
					<li><a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . get_the_title( $post->ID ) . '&amp;url=' . get_permalink( $post->ID ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'prime-hosting' ); ?></span><i class="fab fa-twitter"></i></a></li>
					<?php
				}

				if ( get_theme_mod( 'sharing_linkedin', true ) === true ) {
					?>
					<li><a href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?url=' . get_permalink( $post->ID ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'prime-hosting' ); ?></span><i class="fab fa-linkedin"></i></a></li>
					<?php
				}

				if ( get_theme_mod( 'sharing_reddit', true ) === true ) {
					?>
					<li><a href="<?php echo esc_url( 'https://reddit.com/submit?url=' . get_permalink( $post->ID ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Reddit', 'prime-hosting' ); ?></span><i class="fab fa-reddit"></i></a></li>
					<?php
				}

				if ( get_theme_mod( 'sharing_pinterest', true ) === true ) {
					?>
					<li><a href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=[' . get_permalink( $post->ID ) ); ?>]"><span class="screen-reader-text"><?php esc_html_e( 'Pinterest', 'prime-hosting' ); ?></span><i class="fab fa-pinterest"></i></a></li>
					<?php
				}
				?>
				</ul>
				</div><!-- End social sharing -->
				<?php
			}
		}
	}
}

if ( ! function_exists( 'prime_hosting_social_links' ) ) {
	function prime_hosting_social_links() {
		if ( get_theme_mod( 'facebook_link', '' ) <> '' ||
			get_theme_mod( 'twitter_link', '' ) <> '' ||
			get_theme_mod( 'instagram_link', '' ) <> '' ||
			get_theme_mod( 'linkedin_link', '' ) <> '' ||
			get_theme_mod( 'youtube_link', '' ) <> '' ||
			get_theme_mod( 'pinterest_link', '' ) <> ''
		) {
			?>
			<ul class="team_social d-flex">
			<?php

			if ( get_theme_mod( 'facebook_link', '' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'facebook_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'prime-hosting' ); ?></span><i class="fab fa-facebook-f"></i></a></li>
				<?php
			}

			if ( get_theme_mod( 'twitter_link', '' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'twitter_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'prime-hosting' ); ?></span><i class="fab fa-twitter"></i></a></li>
				<?php
			}

			if ( get_theme_mod( 'instagram_link', '' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'instagram_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'prime-hosting' ); ?></span><i class="fab fa-instagram"></i></a></li>
				<?php
			}

			if ( get_theme_mod( 'linkedin_link','' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'linkedin_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'prime-hosting' ); ?></span><i class="fab fa-linkedin"></i></a></li>
				<?php
			}

			if ( get_theme_mod( 'youtube_link', '' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'youtube_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'YouTube', 'prime-hosting' ); ?></span><i class="fab fa-youtube"></i></a></li>
				<?php
			}

			if ( get_theme_mod( 'pinterest_link', '' ) <> '' ) {
				?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'pinterest_link' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Pinterest', 'prime-hosting' ); ?></span><i class="fab fa-pinterest"></i></a></li>
				<?php
			}

			?>
			</ul>
			<?php
		}
	}
}

if ( ! function_exists( 'prime_hosting_post_formats' ) ) {
	/**
	 * Post format archive previews.
	 */
	function prime_hosting_post_formats() {
		global $post;
		$blocks = parse_blocks( $post->post_content );

		if ( ! is_singular() && has_post_format( 'quote' ) && has_block( 'quote' )
			|| ! is_singular() && has_post_format( 'quote' ) && has_block( 'pullquote' ) ) {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] == 'core/quote' || $block['blockName'] == 'core/pullquote' ) {
					?>
					<div class="post_img bg-primary p-5">
						<p class="text-white"> <i><?php echo render_block( $block ); ?></i></p>
					</div>
					<?php
					break;
				}
			}
		} elseif ( ! is_singular() && has_post_format( 'video' ) && has_block( 'video' ) || has_post_format( 'video' ) && has_block( 'core-embed/youtube' ) ) {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] == 'core/video' || $block['blockName'] == 'core-embed/youtube' ) {
					?>
					<div class="post_img">
						<?php echo apply_filters( 'the_content', render_block( $block ) ); ?>
					</div>
					<?php
					break;
				}
			}
		} elseif ( ! is_singular() && has_post_format( 'image' ) && has_block( 'image' ) ) {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] == 'core/image' ) {
					?>
					<div class="post_img">
						<?php echo render_block( $block ); ?>
					</div>
					<?php
					break;
				}
			}
		} elseif ( ! is_singular() && has_post_format( 'gallery' ) && has_block( 'gallery' ) ) {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] == 'core/gallery' ) {
					?>
					<div class="post_img">
						<?php echo render_block( $block ); ?>
					</div>
					<?php
					break;
				}
			}
		} else {
			prime_hosting_post_thumbnail();
		}
	}
}


if ( ! function_exists( 'prime_hosting_related_posts' ) ) {
	/**
	 * Related posts
	 */
	function prime_hosting_related_posts( $post_id ) {
		$categories = get_the_category( $post_id );

		if ( $categories ) {
			$category_ids = [];
			$category     = get_category( $category_ids );
			$categories   = get_the_category( $post_id );

			foreach ( $categories as $category ) {
				$category_ids[] = $category->term_id;
			}

			$count = $category->category_count;

			if ( $count > 1 ) {

				$cat_post_args = [
					'category__in'        => $category_ids,
					'post__not_in'        => [ $post_id ],
					'post_type'           => 'post',
					'posts_per_page'      => 3,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'meta_query'          => array(
						array( 'key' => '_thumbnail_id' ),
					),
				];

				$related_posts_query = new WP_Query( $cat_post_args );
				if ( $related_posts_query->have_posts() ) {
					echo '<h3 class="related-posts-heading">' , __( 'Related Posts', 'prime-hosting' ) , '</h3>';
					echo '<div class="blog_2">';
					while ( $related_posts_query->have_posts() ) {
							$related_posts_query->the_post();
						?>
						<div class="div">
							<article class="blog_post m-3 card border-0 has-thumbnail">
								<div class="post_img"><?php the_post_thumbnail( 'prime-hosting-related-posts' ); ?></div>
								<div class="card-body positon-relative">
									<a href="<?php the_permalink(); ?>" class="btn_detail"><i class="fas fa-long-arrow-alt-right"></i></a>
									<span class="date"><?php prime_hosting_posted_on(); ?></span>
									<h4 class="related-posts-title card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<ul class="post_info d-flex flex-wrap  mt-3">
										<li> <i class="fas fa-user"></i> <?php prime_hosting_posted_by(); ?> </li>
										<li class="comments-popup"> <span> <i class="fas fa-comments"> </i> <?php comments_popup_link(); ?> <span> </li>
									</ul>
							</div>
							</article>
						</div>
						<?php
					} //End While.
				}
				wp_reset_postdata();
			}
		}
		?>
		</div><!-- End related posts -->
		<?php
	}
}


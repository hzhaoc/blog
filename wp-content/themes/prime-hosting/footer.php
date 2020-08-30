<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Prime Hosting
 */

if ( get_theme_mod( 'show_footer_widget', true ) === true && is_active_sidebar( 'sidebar-4' ) ) {
	?>
	<section class="subscribe_area pt-80 pb-80 bg_gray mt-80">
		<div class="container">
			<div class="row ">
				<i class="fa fa-3x fa-rocket" aria-hidden="true"></i>
				<div class="col-lg-4 col-md-6">
					<div class="subscribe-left">
						<?php dynamic_sidebar( 'sidebar-4' ); ?>
					</div>
				</div>
				<div class="col-lg-4 ol-md-6">
					<div class="subscribe_img">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/images/footer-globe.png' ); ?>" alt="" aria-hidden="true">
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}
?>
<footer id="colophon" class="site-footer">
	<div class="footer-inner container pt-80">
		<div class="row ">
			<div class="col-md-6 col-lg-4">
			<?php
			if ( get_theme_mod( 'enable_footer_logo', true ) === true ) {
				prime_hosting_logo();
			}

			echo '<p class="pt-3">';
			echo esc_html( get_theme_mod( 'footer_text' ) );
			echo '</p>';

			if ( get_theme_mod( 'social_links_footer', true ) === true ) {
				?>
				<div class="footer_social">
					<?php prime_hosting_social_links(); ?>
				</div><!-- end footer social -->
				<?php
			}
			?>
			</div>
			<div class="col-md-6 col-lg-4">
				<?php
				if ( is_active_sidebar( 'sidebar-2' ) ) {
					dynamic_sidebar( 'sidebar-2' );
				}
				?>
			</div>
			<div class="col-md-6 col-lg-4">
				<?php
				if ( is_active_sidebar( 'sidebar-3' ) ) {
					dynamic_sidebar( 'sidebar-3' );
				}
				?>
			</div>
		</div><!-- end row-->
	<?php
	if ( get_theme_mod( 'show_footer_bar', true ) === true ) {
		?>
		<div class="footer_coppy_right">
			<!-- Rights-->
			<p class="rights">
			<?php
			if ( get_theme_mod( 'copyright_text', '' ) <> '' ) {
				echo wp_kses_post( get_theme_mod( 'copyright_text', '' ) );
			} else {
				?>
				<span>&copy;&nbsp;</span><span class="copyright-year"><?php echo esc_html( date_i18n( _x( 'Y', 'copyright date format', 'prime-hosting' ) ) ); ?></span>
				<span><?php bloginfo( 'name' ); ?></span>
				<?php
			}
			?>
			</p>
			<?php the_privacy_policy_link( '<p class="rights">', '</p>' ); ?>
		</div><!-- end footer copy right -->
		<?php
	}
	?>
	</div><!-- end footer inner-->
</footer>

<div class="go-top">
	<button class=" icon pulse-button "><i class="fas fa-angle-up"></i></button>
</div>

<div class="preloader" id="preloader">
	<div class="preloader_inner">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/images/loader.gif' ); ?>">
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>

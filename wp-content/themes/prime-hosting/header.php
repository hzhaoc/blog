<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Prime Hosting
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
} else {
	do_action( 'wp_body_open' );
}
?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'prime-hosting' ); ?></a>

	<header class="navigation">
		<div class="container">
			<nav class="navbar navbar-light navbar-expand-lg">
				<div class="right d-flex">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'prime-hosting' ); ?>"><span class="navbar-toggler-icon"></span></button>
					<?php
					prime_hosting_logo();

					echo '<div class="site-branding">';
					if ( display_header_text() ) {
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					}

					$prime_hosting_description = get_bloginfo( 'description', 'display' );
					if ( get_theme_mod( 'display_tagline', false ) === true && $prime_hosting_description ) {
						?>
						<div class="site-description">
							<?php echo $prime_hosting_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
						</div>
						<?php
					}
					?>
					</div><!-- site-branding -->
				</div><!-- end d-flex-->

				<!--menu-->
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'menu-1',
						'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
						'container'       => 'nav',
						'container_class' => 'main-navigation collapse navbar-collapse',
						'container_id'    => 'navbarCollapse',
						'menu_class'      => 'navbar-nav m-auto',
						'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
						'walker'          => new WP_Bootstrap_Navwalker(),
					)
				);
				?>
				<!-- end main-navigation -->

				<ul class="nav nvbar_icon" id="extra-menu">
					<?php
					if ( class_exists( 'WooCommerce' ) ) {
						$cart = WC()->cart;

						if ( ! empty( $cart ) ) {
							$count = absint( $cart->get_cart_contents_count() );
							?>
							<li class="nav-item cart-wrap">
							<a class="nav-link crt" data-toggle="collapse" href="#cart1">
							<span class="screen-reader-text"><?php esc_html_e( 'Shopping Cart', 'prime-hosting' ); ?></span>
							<i class="fas fa-shopping-bag"> </i><sup data-count="<?php echo esc_attr( $count ); ?>"> <?php echo $count; ?></sup></a>
								<div class="collapse p_abslute cart1" id="cart1">
									<div class="shopping-cart current_icon">
									<?php woocommerce_mini_cart(); ?>
									</div><!--end shopping-cart -->
								</div>
							</li>
							<?php
						}
					}
					?>

					<li class="nav-item search-wrap">
						<a class="nav-link" data-toggle="collapse" data-target="#search_box" href="#">
							<i class="fas fa-search"> </i>
						</a>
						<div id="search_box" class="collapse p_abslute">
							<form role="search" method="get" class="search-form d-flex justify-content-between current_icon  search-inner" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="search" name="s" class="form-control" id="search_input" placeholder="<?php echo esc_attr_x( 'Search Here', 'placeholder', 'prime-hosting' ); ?>">
							<button class="search-submit" type="submit"> <i class="fas fa-search"> </i><span class="screen-reader-text"><?php esc_html_e( 'Search', 'prime-hosting' ); ?></span></button>
							</form>
						</div>
					</li>

					<?php
					if ( has_nav_menu( 'menu-2' ) ) {
						?>
						<li class="nav-item secondary-menu-wrap">
							<a class="top_info_01" data-toggle="collapse" href="#collapseExample_info_01" 
							role="button" aria-expanded="false" aria-controls="collapseExample">
							<span></span>
							</a>
							<?php
							wp_nav_menu(
								array(
									'theme_location'  => 'menu-2',
									'depth'           => 1, // 1 = no dropdowns, 2 = with dropdowns.
									'container'       => 'div',
									'container_class' => 'collapse secondary-menu',
									'container_id'    => 'collapseExample_info_01',
									'menu_class'      => 'list-group',
								)
							);
							?>
						</li>
						<?php
					}
					?>
				</ul>


				<?php
				if ( get_theme_mod( 'top_button_link', '' ) <> '' && get_theme_mod( 'top_button_text', '' ) <> '' ) {
					?>
					<div class="nav-link">
						<a href="<?php echo esc_url( get_theme_mod( 'top_button_link' ) ); ?>" class="btn free_quote"><?php echo esc_html( get_theme_mod( 'top_button_text' ) ); ?></a>
					</div>
					<?php
				}
				?>
			</nav><!-- end navbar-->
		</div><!-- end container-->
	</header><!-- #masthead -->

<?php
if ( ! is_404() ) {
	?>
	<!-- page title -->
	<section class="page-title position-relative pb-60">
		<?php
		if ( get_theme_mod( 'show_breadcrumbs', true ) === true ) {
			?>
			<div class="nav_breadcrumb mb-5">
				<div class="container positon-relative">
					<div class="row">
						<?php prime_hosting_breadcrumb_trail(); ?>
					</div>
				</div>
			</div>
			<?php
		}
		if ( get_theme_mod( 'show_header_content', true ) === true ) {
			?>
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<div class="text-white">
							<?php
							if ( get_theme_mod( 'header_text_heading', '' ) <> '' ) {
								echo '<h1>' . esc_html( get_theme_mod( 'header_text_heading' ) ) . '</h1>';
							}

							if ( get_theme_mod( 'header_text', '' ) <> '' ) {
								?>
								<p class="pt-5"><?php echo esc_html( get_theme_mod( 'header_text' ) ); ?></p>
								<?php
							}
							?>
						</div>
					</div>
					<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<div class="text-center blog-title_img">
							<img alt="" src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>">
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</section>
	<!-- /page title -->
	<?php
}

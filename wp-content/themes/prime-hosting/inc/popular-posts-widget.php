<?php
/**
 * Widget API: WP_Widget_Recent_Posts class
 *
 * @package Prime Hosting
 */

/**
 * Register our custom widget.
 */
function prime_hosting_post_widget() {
	register_widget( 'Prime_Hosting_Posts_Widget' );
}
add_action( 'widgets_init', 'prime_hosting_post_widget' );


/**
 * Theme specific class used to implement a custom posts widget.
 */
class Prime_Hosting_Posts_Widget extends WP_Widget {

	/**
	 * Sets up a new widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'prime-hosting-posts',
			'description'                 => __( 'Popular posts', 'prime-hosting' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'prime-hosting-posts', __( 'Prime Hosting: Popular posts with featured images', 'prime-hosting' ), $widget_ops );
		$this->alt_option_name = 'prime_hosting_posts';
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Popular posts', 'prime-hosting' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the widget.
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the posts.
		 */
		$r = new WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'orderby'             => 'comment_count',
					'meta_query'          => array(
						array( 'key' => '_thumbnail_id' ),
					),
				)
			)
		);

		if ( $r->have_posts() ) {
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
			<ul>
			<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				?>
				<li class="sidbr_post mb-4">
					<div class="post_img">
						<a href="<?php echo esc_url( get_permalink() ); ?>" class="prime_hosting-widget-post-img"><?php the_post_thumbnail( 'prime-hosting-widget' ); ?></a>
					</div>
					<div class="psot-cnt">
						<?php
						if ( $show_date ) {
							?>
							<span class="post-date"><?php echo get_the_date(); ?></span>
							<?php
						}
						?>
						<h3 class="prime-hosting-widget-post-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>
					</div>
				</li>
			<?php endwhile; ?>
			</ul>
			<?php
			echo $args['after_widget'];
		}
		// Reset the global $the_post as this query will have stomped on it.
		wp_reset_postdata();
	}



	/**
	 * Handles updating the settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['number']    = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the  widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'prime-hosting' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
		value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'prime-hosting' ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" 
		step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" 
		name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'prime-hosting' ); ?></label></p>
		<?php
	}
}

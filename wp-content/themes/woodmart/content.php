<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */
 // woodmart_setup_loop();

$woodmart_loop 		= woodmart_loop_prop( 'woodmart_loop' );
$blog_design   		= woodmart_loop_prop( 'blog_design' );
$desktop_columns    = woodmart_loop_prop( 'blog_columns' );
$tablet_columns     = woodmart_loop_prop( 'blog_columns_tablet' );
$mobile_columns     = woodmart_loop_prop( 'blog_columns_mobile' );
$is_shortcode  		= woodmart_loop_prop( 'blog_type' ) == 'shortcode';
$is_large_image 	= woodmart_get_opt( 'single_post_design' ) == 'large_image';
$classes       		= array();

if ( is_single() && $is_large_image ) $classes[] = 'post-single-large-image';

if( is_single() && !$is_shortcode ) {
	$classes[] = 'post-single-page';
} else {
	$classes[] = 'blog-design-' . $blog_design;
	$classes[] = 'blog-post-loop';
	if( $blog_design == 'chess' ) {
		$classes[] = 'blog-design-small-images';
	}
}

if ( ! is_single() || $is_shortcode ) {
	$blog_style = woodmart_get_opt( 'blog_style', 'shadow' );

	if ( 'shadow' === $blog_style ) {
		$classes[] = 'blog-style-bg';

		if ( woodmart_get_opt( 'blog_with_shadow', true ) ) {
			$classes[] = 'wd-add-shadow';
		}
	} else {
		$classes[] = 'blog-style-' . $blog_style;
	}
}

if( is_single() && !$is_shortcode ) {
	$blog_design = 'default';
}

if( ( $blog_design == 'masonry' || $blog_design == 'mask' ) && ( $is_shortcode || ! is_single() ) ){
	if ( ( 'auto' !== $tablet_columns && ! empty( $tablet_columns ) ) || ( 'auto' !== $mobile_columns && ! empty( $mobile_columns ) ) ) {
		$classes[] = woodmart_get_grid_el_class_new( $woodmart_loop, false, $desktop_columns, $tablet_columns, $mobile_columns );
	} else {
		$classes[] = woodmart_get_grid_el_class( $woodmart_loop, $desktop_columns, false, 12 );
	}
}

if( get_the_title() == '' ){
	$classes[] = 'post-no-title';
}

$gallery_slider = apply_filters( 'woodmart_gallery_slider', true );
$gallery = array();

if( get_post_format() == 'gallery' && $gallery_slider ) {
	$gallery = get_post_gallery( false, false );
}

$random = 'carousel-' . rand(100,999);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="article-inner">
		<?php if ( $blog_design == 'default-alt' || is_single() && ! $is_shortcode && ! $is_large_image ): ?>
			<?php if ( woodmart_loop_prop( 'parts_meta' ) && get_the_category_list( ', ' ) ): ?>
				<div class="meta-post-categories wd-post-cat wd-style-with-bg"><?php echo get_the_category_list( ', ' ); ?></div>
			<?php endif ?>

			<?php if ( is_single() && woodmart_loop_prop( 'parts_title' ) && ! $is_shortcode ) : ?>
				<h1 class="wd-entities-title title post-title"><?php the_title(); ?></h1>
			<?php elseif( woodmart_loop_prop( 'parts_title' ) ) : ?>
				<h3 class="wd-entities-title title post-title">
					<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h3>
			<?php endif; // is_single() ?>

			<?php if ( woodmart_loop_prop( 'parts_meta' ) ): ?>
				<div class="entry-meta wd-entry-meta">
					<?php woodmart_post_meta(array(
						'author' => 1,
						'author_avatar' => 1,
						'date' => 0,
						'comments' => ( ! is_single() ) ? 1 : 0,
						'author_label' => 'long'
					)); ?>
				</div><!-- .entry-meta -->
			<?php endif ?>
		<?php endif ?>
			<header class="entry-header">
				<?php if ( ( has_post_thumbnail() || ! empty( $gallery['src'] ) ) && ! post_password_required() && ! is_attachment() && woodmart_loop_prop( 'parts_media' ) ) : ?>
					<?php
					$owl_attr = woodmart_get_owl_attributes(
						array(
							'carousel_id'             => $random,
							'post_type'               => 'post',
							'slides_per_view'         => 1,
							'hide_pagination_control' => 'yes',
						)
					);

					woodmart_enqueue_js_script( 'owl-carousel' );
					?>

					<figure id="<?php echo esc_attr( $random ); ?>" class="entry-thumbnail" <?php echo $owl_attr; ?>>
						<?php if ( 'gallery' === get_post_format() && $gallery_slider && ! empty( $gallery['src'] ) ) : ?>
							<div class="post-gallery-slider owl-carousel wd-owl <?php echo woodmart_owl_items_per_slide( 1 ); ?>">
								<?php
									foreach ($gallery['src'] as $src) {
										if ( preg_match( "/data:image/is", $src ) ) {
											continue;
										}
										?>
										<div>
											<?php echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $src ) . '" />' ); ?>
										</div>
										<?php
									}
								?>
							</div>
						<?php elseif ( ! is_single() || $is_shortcode ): ?>

							<div class="post-img-wrapp">
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<?php echo woodmart_get_post_thumbnail( 'large' ); ?>
								</a>
							</div>
							<div class="post-image-mask">
								<span></span>
							</div>

						<?php elseif ( is_single() && ! $is_large_image ): ?>
							<?php the_post_thumbnail(); ?>
						<?php endif ?>

					</figure>
				<?php endif; ?>

				<?php if ( is_single() && ! $is_large_image || ! is_single() ): ?>
					<?php
					woodmart_post_date(
						array(
							'style' => 'wd-style-with-bg',
						)
					);
					?>
				<?php endif ?>

			</header><!-- .entry-header -->

		<div class="article-body-container">
			<?php if ( $blog_design != 'default-alt' && ( ! is_single() || $is_shortcode ) ): ?>

				<?php if ( woodmart_loop_prop( 'parts_meta' ) && get_the_category_list( ', ' ) ): ?>
					<div class="meta-categories-wrapp"><div class="meta-post-categories wd-post-cat wd-style-with-bg"><?php echo get_the_category_list( ', ' ); ?></div></div>
				<?php endif ?>

				<?php if ( is_single() && woodmart_loop_prop( 'parts_title' ) && !$is_shortcode ) : ?>
					<h1 class="wd-entities-title post-title"><?php the_title(); ?></h1>
				<?php elseif( woodmart_loop_prop( 'parts_title' ) ) : ?>
					<h3 class="wd-entities-title title post-title">
						<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h3>
				<?php endif; // is_single() ?>

				<?php if ( woodmart_loop_prop( 'parts_meta' ) && ( ! is_single() || $is_shortcode ) ): ?>
					<div class="entry-meta wd-entry-meta">
						<?php woodmart_post_meta(array(
							'author' => 1,
							'author_avatar' => 1,
							'date' => false,
							'comments' => 1,
							'author_label' => ( $blog_design == 'masonry' || $blog_design == 'small-images' || $blog_design == 'chess' || $blog_design == 'mask' ) ? 'short' : 'long'
						)); ?>
					</div><!-- .entry-meta -->
					<?php if ( woodmart_is_social_link_enable( 'share' ) ): ?>
						<div class="hovered-social-icons wd-tltp wd-tltp-top">
							<div class="wd-tooltip-label">
								<?php if( function_exists( 'woodmart_shortcode_social' ) ) echo woodmart_shortcode_social( array('size' => 'small', 'color' => 'light' ) ); ?>
							</div>
						</div>
					<?php endif ?>
				<?php endif ?>
			<?php endif ?>

			<?php if ( is_search() && woodmart_loop_prop( 'parts_text' ) && 'gallery' !== get_post_format() ) : // Only display Excerpts for Search. ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php else : ?>
				<?php
					$parts_btn       = woodmart_loop_prop( 'parts_btn' );
					$is_full_content = 'full' === woodmart_get_opt( 'blog_excerpt' ) || ( is_single() && ! $is_shortcode );
				?>

				<?php if ( woodmart_loop_prop( 'parts_text' ) ) : ?>
                    <div class="entry-content wd-entry-content<?php echo woodmart_get_old_classes( ' woodmart-entry-content' ); //phpcs:ignore. ?>">
						<?php woodmart_get_content( $parts_btn, $is_full_content ); ?>

						<?php
							wp_link_pages(
								array(
									'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'woodmart' ) . '</span>',
									'after'       => '</div>',
									'link_before' => '<span>',
									'link_after'  => '</span>',
								)
							);
						?>
					</div><!-- .entry-content -->
				<?php endif; ?>

				<?php if ( ! $is_full_content && $parts_btn ) : ?>
					<?php woodmart_render_read_more_btn( 'link' ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( woodmart_loop_prop( 'parts_meta' ) && 'default-alt' === $blog_design && ! is_single() ) : ?>
				<div class="share-with-lines">
					<span class="left-line"></span>
					<?php if ( woodmart_is_social_link_enable( 'share' ) ) : ?>
						<?php if( function_exists( 'woodmart_shortcode_social' ) ) echo woodmart_shortcode_social( array( 'style' => 'bordered', 'size' => 'small', 'form' => 'circle' ) ); ?>
					<?php endif ?>
					<span class="right-line"></span>
				</div>
			<?php endif; ?>

			<?php if ( is_single() && get_the_author_meta( 'description' ) && !$is_shortcode ) : ?>
				<footer class="entry-author">
					<?php get_template_part( 'author-bio' ); ?>
				</footer><!-- .entry-author -->
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post -->


<?php
// Increase loop count
woodmart_set_loop_prop( 'woodmart_loop', $woodmart_loop + 1 );
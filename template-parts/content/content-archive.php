<?php

/**
 * Template part for displaying post archives-rcc and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<div class="col-md-3 archive-rcc archive-moara">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('thumbnail_redes_retangular');
		} else {
			echo '<img src="' . get_template_directory_uri() . '/assets/images/futuras_placeholder.png' . '" class="attachment-thumbnail_redes_retangular size-thumbnail_redes_retangular wp-post-image" alt="" decoding="async" width="450" height="250">';
		}
		?>
		<h2>
			<a class="post-title" href="<?php the_permalink(); ?>">
				<?php echo wp_trim_words(get_the_title(), 11) ?>
			</a>
		</h2>
		<p class="post-excerpt"><?php echo wp_trim_words(get_the_content(), 30) ?></p>
		<a class="moretext" href="<?php the_permalink(); ?>">Continue lendo <i class="fas fa-arrow-right"></i></a>
	</article><!-- #post-${ID} -->
</div>
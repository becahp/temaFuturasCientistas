<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$description = get_the_archive_description();
?>

<?php if (have_posts()) : ?>

	<?php
	$rede_slug = "rede-3";
	$rede_name = getNameRede($rede_slug);
	$categoria_rede = "rede_3_categoria";

	gera_breadcrumb_redes($rede_slug, $rede_name, $categoria_rede, 'taxonomy');
	?>

	<div class="mb-5"></div>
	<div class="container-lg d-block">

		<header class="page-header alignwide">
			<?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
			<?php if ($description) : ?>
				<div class="archive-description"><?php echo wp_kses_post(wpautop($description)); ?></div>
			<?php endif; ?>
		</header><!-- .page-header -->
		<div class="row">
			<?php while (have_posts()) : ?>
				<?php the_post(); ?>
				<?php get_template_part('template-parts/redes/redes-archive'); ?>
			<?php endwhile; ?>
		</div>

		<?php twenty_twenty_one_the_posts_navigation(); ?>

	</div>

<?php else : ?>
	<?php get_template_part('template-parts/content/content-none'); ?>
<?php endif; ?>
<div class="mb-5"></div>
<?php get_footer(); ?>
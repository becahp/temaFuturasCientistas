<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */
while (have_posts()) :
	the_post();

	$rede_slug = "rede-3";
	$rede_name = getNameRede($rede_slug);
	$categoria_rede = "rede_3_categoria";

	gera_breadcrumb_redes($rede_slug, $rede_name, $categoria_rede);

	// get_template_part('template-parts/redes/redes-single');
	get_template_part('template-parts/content/content-single');


endwhile; // End of the loop.

get_footer();

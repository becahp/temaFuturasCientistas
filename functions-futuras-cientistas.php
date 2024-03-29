<?php

/**
 * Novo accordion multi nível:
 * https://codyhouse.co/demo/multi-level-accordion-menu/index.html
 */
function gerar_redes_moara($r_type, $r_tax)
{
    /* https://wordpress.stackexchange.com/a/215963 */
    $args = array(
        'taxonomy'    => $r_tax,
        'orderby'    => 'name',
        'parent'    => 0, /* garantir que não trará as filhas */
        'hide_empty' => 0, /* mostrar todas */
    );

?>
    <?php

    $allthecats = get_categories($args);
    $categorias_array = wp_list_pluck($allthecats, 'name', 'term_id');

    echo '<div class="texto-hover" id="texto-hover-' . $r_type . '"></div>';
    echo '<div class="accordion-moara-container">';
    echo '<div class="accordion-moara">';
    echo '<ul class="cd-accordion primeiro-ul">';

    $categoria_count = 0;
    foreach ($categorias_array as $categoria_id => $categoria) {
        $categoria_slug = get_term($categoria_id, $r_tax)->slug;
        $checkbox_name = "group-" . $r_tax . "-" . $categoria_slug . "-" . $categoria_count;
    ?>
        <li class="cd-accordion__item cd-accordion__item--has-children primeiro-li">
            <input class="cd-accordion__input" type="checkbox" name="<?php echo $checkbox_name ?>" id="<?php echo $checkbox_name ?>">
            <label class="cd-accordion__label cd-accordion__label--icon-folder" for="<?php echo $checkbox_name ?>">
                <span class="icon">
                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                </span>
                <span><?php echo $categoria; ?></span> <!-- CATEGORIA PAI -->
            </label>

            <ul class="cd-accordion__sub cd-accordion__sub--l1">
                <?php
                // <!-- single child do PAI-->
                meu_arrr_custom_loop_moara($r_type, -1, $r_tax, $categoria);
                $filhos_ids = retorna_filhos($categoria_id, $r_tax);

                if (sizeof($filhos_ids) > 0) {
                    $filhos_count = 0;
                    foreach ($filhos_ids as $categoria_filho_id) {
                        $categoria_filho = get_term($categoria_filho_id, $r_tax)->name;
                        $categoria_filho_slug = get_term($categoria_filho_id, $r_tax)->slug;
                        $checkbox_name_filho = "sub-group-" . $r_tax . "-" . $categoria_filho_slug . "-" . $filhos_count;
                ?>
                        <!-- PAI tem filhos  -->
                        <li class="cd-accordion__item cd-accordion__item--has-children">
                            <input class="cd-accordion__input" type="checkbox" name="<?php echo $checkbox_name_filho; ?>" id="<?php echo $checkbox_name_filho; ?>">
                            <label class="cd-accordion__label cd-accordion__label--icon-folder" for="<?php echo $checkbox_name_filho; ?>">
                                <span class="icon">
                                    <i class="fas fa-plus" aria-hidden="true"></i>
                                </span>
                                <span><?php echo $categoria_filho; ?></span> <!-- CATEGORIA FILHO -->
                            </label>

                            <ul class="cd-accordion__sub cd-accordion__sub--l2">
                                <?php
                                // <!-- single child  do FILHO -->
                                meu_arrr_custom_loop_moara($r_type, -1, $r_tax, $categoria_filho);
                                $netos_ids = retorna_filhos($categoria_filho_id, $r_tax);

                                if (sizeof($netos_ids) > 0) {
                                    $netos_count = 10;
                                    foreach ($netos_ids as $categoria_neto_id) {
                                        $categoria_neto = get_term($categoria_neto_id, $r_tax)->name;
                                        $categoria_neto_slug = get_term($categoria_neto_id, $r_tax)->slug;
                                        $checkbox_name_neto = "sub-sub-group-" . $r_tax . "-" . $categoria_neto_slug . "-" . $netos_count;
                                ?>
                                        <!-- FILHOS tem filhos (netos) -->
                                        <li class="cd-accordion__item cd-accordion__item--has-children">
                                            <input class="cd-accordion__input" type="checkbox" name="<?php echo $checkbox_name_neto; ?>" id="<?php echo $checkbox_name_neto; ?>">
                                            <label class="cd-accordion__label cd-accordion__label--icon-folder" for="<?php echo $checkbox_name_neto; ?>">
                                                <span class="icon">
                                                    <i class="fas fa-folder" aria-hidden="true"></i>
                                                </span>
                                                <span><?php echo $categoria_neto; ?></span> <!-- CATEGORIA NETO -->
                                            </label>

                                            <ul class="cd-accordion__sub cd-accordion__sub--l3">

                                                <?php meu_arrr_custom_loop_moara($r_type, -1, $r_tax, $categoria_neto); ?>

                                            </ul> <!-- ul netos  -->
                                        </li> <!-- li de cada neto -->
                                <?php
                                        $netos_count += 1;
                                    } //fechamento do foreach $netos_ids
                                } // if $netos_ids
                                ?>
                            </ul> <!-- ul filhos : netos  -->
                        </li> <!-- li de cada filho -->
                <?php
                        $filhos_count += 1;
                    } //fechamento do foreach $filhos_ids
                }  // if $filhos_ids
                ?>
            </ul> <!-- ul pai : filhos  -->
        </li> <!-- li de cada pai -->
        <?php
        $categoria_count += 1;
    } // fim do foreach $categorias_array

    echo '</ul></div></div>';
}


function retorna_filhos($categoria_id, $r_tax)
{
    $filhos_args = array(
        'taxonomy'    => $r_tax,
        'orderby'    => 'name',
        'parent'    => $categoria_id,
        'hide_empty' => 0, /* mostrar todas */
    );

    $filhos_categorias  = get_categories($filhos_args);

    return wp_list_pluck($filhos_categorias, 'term_id');
}


/************************************************************************************************REDES */
/* Função para padronizar a chamada de custom post types por categoria e subcategoria
 * https://wordpress.stackexchange.com/questions/23136/get-custom-post-type-by-category-in-a-page-template */

function meu_arrr_custom_loop_moara($r_type = 'post', $r_post_num, $r_tax = 'category', $r_terms = 'featured')
{
    $args = array(
        'showposts' => $r_post_num,
        'order' => "ASC",
        'orderby' => "title",
        'tax_query' => array(
            array(
                'post_type' => $r_type,
                'taxonomy' => $r_tax,
                'field' => 'slug',
                'terms' => array(
                    $r_terms
                ),
                'include_children' => false,
            )
        )
    );
    $the_query = new WP_Query($args);
    // The Loop
    if ($the_query->have_posts()) {
        // echo '<ol>';
        while ($the_query->have_posts()) {
            echo '<li class="cd-accordion__item item-moara">';
            $the_query->the_post();
            //$cats = get_the_category_instituicoes();

        ?>
            <div>
                <a class="item-moara-a" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>

                <span class='d-none'><?php echo wp_trim_words(get_the_excerpt(), 120) ?></span>
            </div>
<?php
            echo '</li>';
        }
        // echo '</ol>';
    } else {
        //echo '<br>no posts found';
    }
    /* Restore original Post Data */
    wp_reset_postdata();
}


/*  
 * -- Novo formato para as imagens --
*/
/* function thumbnail_redes()
{
    add_image_size('thumbnail_redes', 300, 300, true);
}
add_action('after_setup_theme', 'thumbnail_redes'); */

function thumbnail_redes_retangular()
{
    add_image_size('thumbnail_redes_retangular', 450, 250, true);
}
add_action('after_setup_theme', 'thumbnail_redes_retangular');


/* Função para mostrar os posts das redes em categorias e tags */
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $post_type = get_query_var('post_type');
    if($post_type) {
        $post_type = $post_type;
	}
    else {
        $post_type = array('post','rede-1','rede-2','rede-3'); // replace cpt to your custom post type
	}
    $query->set('post_type',$post_type);
    return $query;
    }
}


/* Função para restringir a busca aos posts das redes */
//https://thomasgriffin.com/how-to-exclude-pages-from-wordpress-search-results/
/*add_action( 'pre_get_posts', 'tg_exclude_pages_from_search_results' );
function tg_exclude_pages_from_search_results( $query ) {
  if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
    //$query->set( 'post_type', array( 'post' ) );
    $post_type = array('rede-de-formacao','rede-de-inovacao','rede-de-pesquisa','rede-de-tecnologia','rede-de-suporte'); // 
    $query->set('post_type',$post_type);
  }    
}
*/
<?php
/**
 * Rendu dynamique de la section Actualité.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('actualites_dynamiques', 'pr_actualites_dynamiques_shortcode');

function pr_get_actualite_field($field_name, $post_id) {
    return function_exists('get_field') ? get_field($field_name, $post_id) : '';
}

function pr_get_actualite_image_id($image) {
    if (is_array($image) && !empty($image['ID'])) {
        return absint($image['ID']);
    }

    if (is_numeric($image)) {
        return absint($image);
    }

    return 0;
}

function pr_render_actualite_image($image, $size, $class_name, $alt) {
    $image_id = pr_get_actualite_image_id($image);

    if (!$image_id) {
        return '';
    }

    return wp_get_attachment_image($image_id, $size, false, array(
        'class' => $class_name,
        'loading' => 'lazy',
        'alt' => $alt,
    ));
}

/**
 * Afficher les actualités publiées dans une section avec dialogue réutilisable.
 *
 * @param array|string $atts Attributs du shortcode.
 * @return string
 */
function pr_actualites_dynamiques_shortcode($atts = array()) {
    $atts = shortcode_atts(array(
        'nombre' => 4,
    ), $atts, 'actualites_dynamiques');

    $posts_per_page = max(1, absint($atts['nombre']));
    $actualites = get_posts(array(
        'post_type' => 'pr_actualite',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    if (empty($actualites)) {
        return '';
    }

    wp_enqueue_script('pr-actualites-dialog');

    $section_id = wp_unique_id('pr-actualites-');
    $html_parts = array();
    $html_parts[] = '<section class="pr-section pr-section-actualites" id="' . esc_attr($section_id) . '" aria-labelledby="' . esc_attr($section_id) . '-title">';
    $html_parts[] = '<div class="pr-container">';
    $html_parts[] = '<h2 class="wp-block-heading" id="' . esc_attr($section_id) . '-title">' . esc_html__('Actualité', 'prochaine-revue') . '</h2>';
    $html_parts[] = '<div class="pr-actualites-grid">';

    foreach ($actualites as $actualite) {
        $post_id = $actualite->ID;
        $title = get_the_title($post_id);
        $title_field = pr_get_actualite_field('actualite_tuile_titre', $post_id);
        $tile_title = $title_field !== '' ? $title_field : $title;
        $title_text = wp_strip_all_tags($tile_title);
        $tile_description = pr_get_actualite_field('actualite_tuile_description', $post_id);
        if ($tile_description === '') {
            $tile_description = get_the_excerpt($post_id);
        }
        $template_id = $section_id . '-content-' . $post_id;
        $type_terms = get_the_terms($post_id, 'pr-type-actualite');
        $tile_image = pr_get_actualite_field('actualite_image_tuile', $post_id);
        if (empty($tile_image)) {
            $tile_image = get_post_thumbnail_id($post_id);
        }
        $modal_content = pr_get_actualite_field('actualite_contenu_modale', $post_id);
        if ($modal_content === '') {
            $modal_content = $actualite->post_content;
        }

        $html_parts[] = '<article class="pr-actualite-card">';

        if (!empty($tile_image)) {
            $html_parts[] = '<div class="pr-actualite-card__image">';
            $html_parts[] = pr_render_actualite_image($tile_image, 'medium', 'pr-actualite-card__image-img', $title_text);
            $html_parts[] = '</div>';
        }

        $html_parts[] = '<div class="pr-actualite-card__body">';

        if (!empty($type_terms) && !is_wp_error($type_terms)) {
            $html_parts[] = '<p class="pr-actualite-card__type">' . esc_html($type_terms[0]->name) . '</p>';
        }

        $html_parts[] = '<h3 class="pr-actualite-card__title">' . esc_html($title_text) . '</h3>';

        if ($tile_description !== '') {
            $html_parts[] = '<div class="pr-actualite-card__excerpt">' . wp_kses_post(wpautop($tile_description)) . '</div>';
        }

        $html_parts[] = '<time class="pr-actualite-card__date" datetime="' . esc_attr(get_the_date('c', $post_id)) . '">' . esc_html(get_the_date('', $post_id)) . '</time>';
        $html_parts[] = '<button type="button" class="pr-actualite-card__trigger" data-pr-actualite-trigger data-dialog-template="' . esc_attr($template_id) . '" aria-label="' . esc_attr(sprintf(__('Lire l’actualité : %s', 'prochaine-revue'), $title_text)) . '">';
        $html_parts[] = '<span class="screen-reader-text">' . esc_html(sprintf(__('Lire l’actualité : %s', 'prochaine-revue'), $title_text)) . '</span>';
        $html_parts[] = '<span class="pr-actualite-card__trigger-icon" aria-hidden="true">';
        $html_parts[] = '<svg clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="m12.012 1.995c-5.518 0-9.998 4.48-9.998 9.998s4.48 9.998 9.998 9.998 9.997-4.48 9.997-9.998-4.479-9.998-9.997-9.998zm0 1.5c4.69 0 8.497 3.808 8.497 8.498s-3.807 8.498-8.497 8.498-8.498-3.808-8.498-8.498 3.808-8.498 8.498-8.498zm1.528 4.715s1.502 1.505 3.255 3.259c.146.147.219.339.219.531s-.073.383-.219.53c-1.753 1.754-3.254 3.258-3.254 3.258-.145.145-.336.217-.527.217-.191-.001-.383-.074-.53-.221-.293-.293-.295-.766-.004-1.057l1.978-1.977h-6.694c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h6.694l-1.979-1.979c-.289-.289-.286-.762.006-1.054.147-.147.339-.221.531-.222.19 0 .38.071.524.215z" fill-rule="nonzero"/></svg>';
        $html_parts[] = '</span>';
        $html_parts[] = '</button>';
        $html_parts[] = '</div>';
        $html_parts[] = '</article>';

        $html_parts[] = '<template id="' . esc_attr($template_id) . '">';
        $html_parts[] = '<header class="pr-actualite-dialog__header">';
        $html_parts[] = '<p class="pr-actualite-dialog__date">' . esc_html(get_the_date('', $post_id)) . '</p>';
        $html_parts[] = '<h2 class="pr-actualite-dialog__title">' . esc_html($title_text) . '</h2>';

        if (!empty($type_terms) && !is_wp_error($type_terms)) {
            $html_parts[] = '<p class="pr-actualite-dialog__type">' . esc_html($type_terms[0]->name) . '</p>';
        }

        $html_parts[] = '</header>';
        if (!empty($tile_image)) {
            $html_parts[] = '<div class="pr-actualite-dialog__image">' . pr_render_actualite_image($tile_image, 'large', 'pr-actualite-dialog__image-img', $title_text) . '</div>';
        }
        $html_parts[] = '<div class="pr-actualite-dialog__body">' . apply_filters('the_content', $modal_content) . '</div>';
        $html_parts[] = '</template>';
    }

    $html_parts[] = '</div>';
    $html_parts[] = '</div>';
    $html_parts[] = '<dialog class="pr-actualite-dialog" data-pr-actualite-dialog aria-labelledby="' . esc_attr($section_id) . '-dialog-title">';
    $html_parts[] = '<div class="pr-actualite-dialog__surface">';
    $html_parts[] = '<button type="button" class="pr-actualite-dialog__close" data-pr-actualite-close aria-label="' . esc_attr__('Fermer l’actualité', 'prochaine-revue') . '">×</button>';
    $html_parts[] = '<div class="pr-actualite-dialog__content" data-pr-actualite-dialog-content></div>';
    $html_parts[] = '</div>';
    $html_parts[] = '</dialog>';
    $html_parts[] = '</section>';

    return implode('', $html_parts);
}

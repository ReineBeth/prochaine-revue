<?php
/**
 * Rendu dynamique de la section Actualité.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('actualites_dynamiques', 'pr_actualites_dynamiques_shortcode');

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
        $title_text = wp_strip_all_tags($title);
        $template_id = $section_id . '-content-' . $post_id;
        $type_terms = get_the_terms($post_id, 'pr-type-actualite');
        $thumbnail_id = get_post_thumbnail_id($post_id);

        $html_parts[] = '<article class="pr-actualite-card">';

        if ($thumbnail_id) {
            $html_parts[] = '<div class="pr-actualite-card__image">';
            $html_parts[] = get_the_post_thumbnail($post_id, 'medium', array(
                'loading' => 'lazy',
                'alt' => $title_text,
            ));
            $html_parts[] = '</div>';
        }

        $html_parts[] = '<div class="pr-actualite-card__body">';

        if (!empty($type_terms) && !is_wp_error($type_terms)) {
            $html_parts[] = '<p class="pr-actualite-card__type">' . esc_html($type_terms[0]->name) . '</p>';
        }

        $html_parts[] = '<h3 class="pr-actualite-card__title">' . esc_html($title_text) . '</h3>';

        $excerpt = get_the_excerpt($post_id);
        if ($excerpt !== '') {
            $html_parts[] = '<div class="pr-actualite-card__excerpt">' . wp_kses_post(wpautop($excerpt)) . '</div>';
        }

        $html_parts[] = '<time class="pr-actualite-card__date" datetime="' . esc_attr(get_the_date('c', $post_id)) . '">' . esc_html(get_the_date('', $post_id)) . '</time>';
        $html_parts[] = '<button type="button" class="pr-actualite-card__trigger" data-pr-actualite-trigger data-dialog-template="' . esc_attr($template_id) . '">';
        $html_parts[] = '<span>' . esc_html(sprintf(__('Lire l’actualité : %s', 'prochaine-revue'), $title_text)) . '</span>';
        $html_parts[] = '<span class="pr-actualite-card__trigger-icon" aria-hidden="true">→</span>';
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
        $html_parts[] = '<div class="pr-actualite-dialog__body">' . apply_filters('the_content', $actualite->post_content) . '</div>';
        $html_parts[] = '</template>';
    }

    $html_parts[] = '</div>';
    $html_parts[] = '</div>';
    $html_parts[] = '<dialog class="pr-actualite-dialog" data-pr-actualite-dialog aria-labelledby="' . esc_attr($section_id) . '-dialog-title">';
    $html_parts[] = '<div class="pr-actualite-dialog__surface">';
    $html_parts[] = '<button type="button" class="pr-actualite-dialog__close" data-pr-actualite-close aria-label="' . esc_attr__('Fermer l’actualité', 'prochaine-revue') . '">×</button>';
    $html_parts[] = '<div class="pr-actualite-dialog__content" data-pr-actualite-dialog-content>';
    $html_parts[] = '<h2 id="' . esc_attr($section_id) . '-dialog-title" class="screen-reader-text">' . esc_html__('Actualité', 'prochaine-revue') . '</h2>';
    $html_parts[] = '</div>';
    $html_parts[] = '</div>';
    $html_parts[] = '</dialog>';
    $html_parts[] = '</section>';

    return implode('', $html_parts);
}

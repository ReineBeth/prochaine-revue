<?php
/**
 * Plugin Name:       Pr Accordeon
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pr-accordeon
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Inclure le fichier de fonctions
require_once plugin_dir_path(__FILE__) . 'includes/auteurs-functions.php';

// Fonction de rendu pour le bloc accordéon
function render_accordeon_block($attributes, $content) {
    // Si mode statique, retourner le contenu tel quel
    if ($attributes['mode'] === 'static') {
        return $content;
    }

    // Pour le mode dynamique
    $output = '<div class="pr-accordeon">';

    // Récupérer les auteurs
    $terms = get_terms([
        'taxonomy' => 'pr-auteurs',
        'hide_empty' => false,
        'number' => $attributes['showAllAuthors'] ? 0 : $attributes['authorCount']
    ]);

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $accordionId = 'author-' . $term->term_id;

            $output .= '<div class="pr-accordeon-container">';

            // En-tête de l'accordéon
            $output .= sprintf(
                '<%1$s><button type="button" aria-expanded="false" class="pr-accordeon-trigger js-trigger" aria-controls="content-%2$s" id="trigger-%2$s">%3$s</button></%1$s>',
                'h' . ($attributes['headingLevel'] ?? '3'),
                esc_attr($accordionId),
                esc_html($term->name)
            );

            // Contenu de l'accordéon
            $output .= sprintf(
                '<div id="content-%s" role="region" aria-labelledby="trigger-%s" class="pr-accordeon-content js-content" hidden>',
                esc_attr($accordionId),
                esc_attr($accordionId)
            );

            $output .= '<div class="pr-accordeon-content-inner">';

            // Articles de l'auteur
            $args = [
                'post_type' => 'articles',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'pr-auteurs',
                        'field' => 'term_id',
                        'terms' => $term->term_id
                    ]
                ]
            ];

            $articles_query = new WP_Query($args);

            if ($articles_query->have_posts()) {
                $output .= '<ul class="author-articles">';
                while ($articles_query->have_posts()) {
                    $articles_query->the_post();
                    $output .= '<li>';
                    $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';

                    $pdf_url = get_field('article_pdf');
                    if ($pdf_url) {
                        $output .= ' - <a href="' . esc_url($pdf_url) . '">PDF</a>';
                    }

                    $output .= '</li>';
                }
                $output .= '</ul>';
            } else {
                $output .= '<p>Aucun article pour cet auteur</p>';
            }

            wp_reset_postdata();

            $output .= '</div></div></div>';
        }
    } else {
        $output .= '<p>Aucun auteur trouvé</p>';
    }

    $output .= '</div>';

    return $output;
}

// Enregistrer les assets
function enqueue_accordeon_assets() {
    wp_enqueue_style(
        'pr-accordeon-style',
        plugins_url('assets/css/style.css', __FILE__)
    );

    wp_enqueue_script(
        'pr-accordeon-script',
        plugins_url('assets/js/script.js', __FILE__),
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_accordeon_assets');

// Initialisation du bloc
function create_block_pr_accordeon_block_init() {
    register_block_type(__DIR__ . '/build', array(
        'render_callback' => 'render_accordeon_block'
    ));
}
add_action('init', 'create_block_pr_accordeon_block_init');

// Ajouter le shortcode
add_shortcode('liste_auteurs_articles', function() {
    return display_authors_with_articles();
});

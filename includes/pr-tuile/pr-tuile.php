<?php
/**
 * Plugin Name:       Pr Tuile
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pr-tuile
 *
 * @package CreateBlock
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_pr_tuile_block_init() {
    register_block_type( __DIR__ . '/build', array(
        'render_callback' => 'render_pr_tuile_block'
    ) );
}
add_action( 'init', 'create_block_pr_tuile_block_init' );

/**
 * Fonction de rendu pour le bloc pr-tuile
 */
function render_pr_tuile_block($attributes, $content) {
    $mode = isset($attributes['mode']) ? $attributes['mode'] : 'static';

    if ($mode === 'dynamic') {
        // Mode dynamique : générer les tuiles d'articles
        $articles_count = isset($attributes['articlesCount']) ? $attributes['articlesCount'] : 3;
        $show_all = isset($attributes['showAllArticles']) ? $attributes['showAllArticles'] : false;

        $posts_per_page = $show_all ? -1 : intval($articles_count);

        $articles = get_posts(array(
            'post_type' => 'pr_article',
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (empty($articles)) {
            return '<p>Aucun article disponible.</p>';
        }

        $output = '<div class="wp-block-pr-tuile pr-tuile-container">';

        foreach ($articles as $article) {
            $title = get_the_title($article->ID);

            // Récupérer les auteurs via la taxonomie
            $auteurs_terms = get_the_terms($article->ID, 'pr-auteurs');

            // Récupérer le type d'article via ACF
            $type_article_raw = get_field('article_type', $article->ID);
            $type_article = '';
            if ($type_article_raw) {
                $type_choices = array(
                    'recherche' => 'Note de recherche',
                    'synthese' => 'Texte réflexif',
                    'rendu' => 'Compte rendu',
                );
                $type_article = isset($type_choices[$type_article_raw]) ? $type_choices[$type_article_raw] : $type_article_raw;
            }

            $thumbnail = get_the_post_thumbnail_url($article->ID, 'medium');
            $slug = $article->post_name;
            $articles_page_url = home_url('/articles/' . $slug . '/');

            $output .= '<a class="pr-tuile-lien" href="' . esc_url($articles_page_url) . '" rel="noopener noreferrer">';

            if ($thumbnail) {
                $output .= '<div class="pr-tuile-lien-image">';
                $output .= '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($title) . '" loading="lazy" />';
                $output .= '</div>';
            }

            $output .= '<div class="pr-tuile-lien-text">';
            $output .= '<h3>' . esc_html($title) . '</h3>';

            // Affichage des auteurs
            if ($auteurs_terms && !is_wp_error($auteurs_terms)) {
                $output .= '<div class="pr-tuile-auteurs">';
                foreach ($auteurs_terms as $auteur) {
                    $output .= '<div class="pr-tuile-auteur">' . esc_html($auteur->name) . '</div>';
                }
                $output .= '</div>';
            }

            // Affichage du type d'article
            if ($type_article) {
                $output .= '<div class="pr-tuile-type"><strong>' . esc_html($type_article) . '</strong></div>';
            }

            $output .= '</div>';
            $output .= '</a>';
        }

        $output .= '</div>';

        return $output;

    } else {
        // Mode statique : utiliser le contenu sauvegardé par save.js
        return $content;
    }
}

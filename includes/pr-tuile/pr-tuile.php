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

        // Définir les balises autorisées pour wp_kses
        $allowed_tags = array(
            'em' => array(),
            'i' => array(),
            'strong' => array(),
            'b' => array(),
        );

        // Utiliser un array pour construire le HTML proprement
        $html_parts = array();
        $html_parts[] = '<div class="wp-block-pr-tuile pr-tuile-container">';

        foreach ($articles as $article) {
            $title = get_the_title($article->ID);
            $auteurs_terms = get_the_terms($article->ID, 'pr-auteurs');

            // NOUVEAU : Récupérer le type depuis la taxonomie
            $type_article = '';
            $type_terms = get_the_terms($article->ID, 'pr-type-article');
            if ($type_terms && !is_wp_error($type_terms)) {
                $type_article = $type_terms[0]->name;
            }

            $thumbnail = get_the_post_thumbnail_url($article->ID, 'medium');
            $permalink = get_permalink($article->ID);

            $html_parts[] = '<a class="pr-tuile-lien" href="' . esc_url($permalink) . '" rel="noopener noreferrer">';

            if ($thumbnail) {
                $html_parts[] = '<div class="pr-tuile-lien-image">';
                $html_parts[] = '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($title) . '" loading="lazy" />';
                $html_parts[] = '</div>';
            }

            $html_parts[] = '<div class="pr-tuile-lien-text">';

            // Titre avec support em/strong
            $html_parts[] = '<h3>' . wp_kses($title, $allowed_tags) . '</h3>';

            // Affichage des auteurs avec support em/strong
            if ($auteurs_terms && !is_wp_error($auteurs_terms)) {
                $html_parts[] = '<div class="pr-tuile-auteurs">';
                foreach ($auteurs_terms as $auteur) {
                    $html_parts[] = '<div class="pr-tuile-auteur">' . wp_kses($auteur->name, $allowed_tags) . '</div>';
                }
                $html_parts[] = '</div>';
            }

            // Type d'article
            if ($type_article) {
                $html_parts[] = '<div class="pr-tuile-type">' . esc_html($type_article) . '</div>';
            }

            $html_parts[] = '</div>'; // Ferme pr-tuile-lien-text
            $html_parts[] = '</a>'; // Ferme pr-tuile-lien
        }

        $html_parts[] = '</div>'; // Ferme pr-tuile-container

        return implode('', $html_parts);

    } else {
        // Mode statique : utiliser le contenu sauvegardé par save.js
        return $content;
    }
}

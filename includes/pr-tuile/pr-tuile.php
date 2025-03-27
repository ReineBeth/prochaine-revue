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
    register_block_type(__DIR__ . '/build', [
        'render_callback' => 'render_pr_tuile_block',
    ]);
}
add_action('init', 'create_block_pr_tuile_block_init');

// TEST

function render_pr_tuile_block($attributes) {
    if ($attributes['mode'] === 'static') {
        // Rendu des tuiles statiques
        if (empty($attributes['tiles'])) {
            return '<p>Aucune tuile disponible.</p>';
        }

        ob_start();
        echo '<div class="pr-tuile-container">';
        foreach ($attributes['tiles'] as $tile) {
            echo '<a class="pr-tuile-lien" href="' . esc_url($tile['linkUrl']) . '">';

            // Vérifier si une image est disponible avant de l'afficher
            if (!empty($tile['showImage']) && !empty($tile['imageUrl'])) {
                echo '<div class="pr-tuile-lien-image"><img src="' . esc_url($tile['imageUrl']) . '" alt="' . esc_attr($tile['imageAlt']) . '"></div>';
            }

            echo '<div class="pr-tuile-lien-text">';
            echo '<h3>' . esc_html($tile['titleField']) . '</h3>';
            echo '<p>' . esc_html($tile['textField']) . '</p>';
            echo '</div></a>';
        }
        echo '</div>';
        return ob_get_clean();
    }

    // Rendu des articles dynamiques
    $args = [
        'post_type'      => 'pr_article',
        'posts_per_page' => !empty($attributes['showAllArticles']) ? -1 : (int) $attributes['articlesCount'],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="pr-tuile-container">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id   = get_the_ID();
            $title     = get_the_title();
            $excerpt   = get_field('article_description') ?: 'Aucune description disponible.';
            $permalink = get_permalink();
            $image_url = get_the_post_thumbnail_url($post_id, 'medium');

            echo '<a class="pr-tuile-lien" href="' . esc_url($permalink) . '">';

            // ✅ Afficher l'image seulement si elle existe
            if ($image_url) {
                echo '<div class="pr-tuile-lien-image"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($title) . '"></div>';
            }

            echo '<div class="pr-tuile-lien-text">';
            echo '<h3>' . esc_html($title) . '</h3>';
            echo '<p>' . esc_html($excerpt) . '</p>';
            echo '</div></a>';
        }
        echo '</div>';
    } else {
        echo '<p>Aucun article disponible.</p>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}

// Associer la fonction au bloc Gutenberg
register_block_type('pr/tuile', [
    'render_callback' => 'render_pr_tuile_block',
]);

// TEST

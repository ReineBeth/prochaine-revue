<?php
/**
 * Shortcode pour afficher les tuiles d'articles dynamiques
 */

if (!defined('ABSPATH')) exit;

add_shortcode('tuiles_articles_dynamiques', 'pr_tuiles_articles_dynamiques_shortcode');

function pr_tuiles_articles_dynamiques_shortcode($atts) {
    // Attributs du shortcode
    $atts = shortcode_atts(array(
        'nombre' => 3,
        'tout_afficher' => 'non'
    ), $atts);
    
    wp_enqueue_style('pr-tuile-block-style');
    
    $posts_per_page = ($atts['tout_afficher'] === 'oui') ? -1 : intval($atts['nombre']);
    
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
    
    $output = '<div class="pr-tuile-container wp-block-pr-tuile">';
    
    foreach ($articles as $article) {
        $title = get_the_title($article->ID);
        
        // Récupérer les auteurs
        $auteurs_terms = get_the_terms($article->ID, 'pr-auteurs');
        
        // NOUVEAU : Récupérer le type depuis la taxonomie
        $type_article = '';
        $type_terms = get_the_terms($article->ID, 'pr-type-article');
        if ($type_terms && !is_wp_error($type_terms)) {
            $type_article = $type_terms[0]->name;
        }
        
        $thumbnail = get_the_post_thumbnail_url($article->ID, 'medium');
        $permalink = get_permalink($article->ID);
        
        $output .= '<a class="pr-tuile-lien" href="' . esc_url($permalink) . '" rel="noopener noreferrer">';
        
        if ($thumbnail) {
            $output .= '<div class="pr-tuile-lien-image">';
            $output .= '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($title) . '" loading="lazy" />';
            $output .= '</div>';
        }
        
        $output .= '<div class="pr-tuile-lien-text">';
        $output .= '<h3>' . wp_kses_post($title) . '</h3>';
        
        if ($auteurs_terms && !is_wp_error($auteurs_terms)) {
            $output .= '<div class="pr-tuile-auteurs">';
            foreach ($auteurs_terms as $auteur) {
                $institution = get_field('auteur_institution', 'pr-auteurs_' . $auteur->term_id);
                $auteur_display = wp_kses_post($auteur->name);
                if ($institution) {
                    $auteur_display .= ' (' . wp_kses_post($institution) . ')';
                }
                $output .= '<div class="pr-tuile-auteur">' . $auteur_display . '</div>';
            }
            $output .= '</div>';
        }
        
        if ($type_article) {
            $output .= '<div class="pr-tuile-type"><strong>' . esc_html($type_article) . '</strong></div>';
        }
        
        $output .= '</div>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
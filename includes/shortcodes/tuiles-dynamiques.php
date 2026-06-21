<?php
/**
 * Shortcode pour afficher les tuiles d'articles dynamiques
 */

if (!defined('ABSPATH')) exit;

add_shortcode('tuiles_articles_dynamiques', 'pr_tuiles_articles_dynamiques_shortcode');

function pr_tuiles_articles_dynamiques_shortcode() {
    wp_enqueue_style('pr-tuile-block-style');
    
    $articles = get_posts(array(
        'post_type' => 'pr_article',
        'posts_per_page' => 3,
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
        
        $auteurs_terms = get_the_terms($article->ID, 'pr-auteurs');
        $auteurs_list = '';
        if ($auteurs_terms && !is_wp_error($auteurs_terms)) {
            $auteurs_names = wp_list_pluck($auteurs_terms, 'name');
            $auteurs_list = implode(', ', $auteurs_names);
        }
        
        $type_article_raw = get_field('article_type', $article->ID);
        $type_article = '';
        if ($type_article_raw) {
            $type_choices = array(
                'recherche' => 'Note de recherche',
                'synthese' => 'Compte Rendu',
                'opinion' => 'Texte réflexif',
                'article' => 'Article',
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
            $output .= '<div class="pr-tuile-type"><strong>' . wp_kses_post($type_article) . '</strong></div>';
        }
        
        $output .= '</div>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
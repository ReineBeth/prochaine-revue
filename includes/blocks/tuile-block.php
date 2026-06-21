<?php
/**
 * Bloc PR Tuile - Rendu dynamique
 */

if (!defined('ABSPATH')) exit;

register_block_type('pr/tuile', array(
    'render_callback' => 'pr_render_tuile_block'
));

function pr_render_tuile_block($attributes, $content) {
    $mode = isset($attributes['mode']) ? $attributes['mode'] : 'static';
    
    if ($mode === 'dynamic') {
        $articlesCount = isset($attributes['articlesCount']) ? $attributes['articlesCount'] : 3;
        $showAllArticles = isset($attributes['showAllArticles']) ? $attributes['showAllArticles'] : false;
        
        $posts_per_page = $showAllArticles ? -1 : $articlesCount;
        
        // Récupérer les articles
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
            $auteurs_terms = get_the_terms($article->ID, 'pr-auteurs');
            
            // Récupérer le type d'article
            $type_article_raw = get_field('article_type', $article->ID);
            $type_article = '';
            if ($type_article_raw) {
                $type_choices = array(
                    'recherche' => 'Note de recherche',
                    'synthese' => 'Compte Rendu',
                    'opinion' => 'Texte Réflexif',
                    'article' => 'Article',
                );
                $type_article = isset($type_choices[$type_article_raw]) ? $type_choices[$type_article_raw] : $type_article_raw;
            }
            
            $slug = $article->post_name;
            $articles_page_url = home_url('/articles/' . $slug . '/');
            
            $output .= '<a class="pr-tuile-lien" href="' . esc_url($articles_page_url) . '" rel="noopener noreferrer">';
            $output .= '<div class="pr-tuile-lien-text">';
            $output .= '<h3>' . wp_kses_post($title) . '</h3>';
            
            // Auteurs
            if ($auteurs_terms && !is_wp_error($auteurs_terms)) {
                $output .= '<div class="pr-tuile-auteurs">';
                foreach ($auteurs_terms as $auteur) {
                    $output .= '<div class="pr-tuile-auteur">' . wp_kses_post($auteur->name) . '</div>';
                }
                $output .= '</div>';
            }
            
            // Type d'article
            if ($type_article) {
                $output .= '<div class="pr-tuile-type">' . wp_kses_post($type_article) . '</div>';
            }
            
            $output .= '</div>';
            $output .= '</a>';
        }
        
        $output .= '</div>';
        return $output;
    }
    
    // Mode statique
    return $content;
}
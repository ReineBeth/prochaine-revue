<?php
/**
 * Custom Post Type: PR Article
 */

if (!defined('ABSPATH')) exit;

add_action('init', 'pr_create_articles_post_type');
function pr_create_articles_post_type() {
    register_post_type('pr_article',
        array(
            'labels' => array(
                'name' => __('Articles'),
                'singular_name' => __('Article'),
                'menu_name' => __('PR Articles'),
                'add_new' => __('Ajouter un Article'),
                'add_new_item' => __('Ajouter un nouvel Article')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-media-document',
            'show_in_rest' => true,
        )
    );
}

// Exposition des champs ACF dans l'API REST
add_action('rest_api_init', 'pr_expose_acf_fields_to_rest');
function pr_expose_acf_fields_to_rest() {
    $acf_fields = array(
        'article_description', 'article_type', 'article_pdf',
        'titre_revue', 'volume', 'pages', 'annee_publication',
        'numero_volume', 'disciplines', 'mots_cles',
        'droits_auteur', 'mois_publication'
    );
    
    foreach($acf_fields as $field) {
        register_rest_field('pr_article', $field, array(
            'get_callback' => function($post) use ($field) {
                return get_field($field, $post['id']);
            },
            'update_callback' => function($value, $post) use ($field) {
                return update_field($field, $value, $post->ID);
            },
            'schema' => null,
        ));
    }
}

add_filter('rest_prepare_pr_article', 'pr_add_acf_to_rest', 10, 3);
function pr_add_acf_to_rest($response, $post, $request) {
    $acf_fields = get_fields($post->ID);
    if ($acf_fields) {
        $response->data['acf'] = $acf_fields;
    }
    return $response;
}
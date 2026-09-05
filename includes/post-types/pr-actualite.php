<?php
/**
 * Type de contenu et taxonomie pour les actualités PR-01.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'pr_register_actualite_content_type');

/**
 * Enregistrer le type de contenu et sa taxonomie.
 */
function pr_register_actualite_content_type() {
    pr_register_actualite_post_type();
    pr_register_actualite_taxonomy();
}

/**
 * Enregistrer le CPT des actualités.
 */
function pr_register_actualite_post_type() {
    register_post_type('pr_actualite', array(
        'labels' => array(
            'name' => __('Actualités', 'prochaine-revue'),
            'singular_name' => __('Actualité', 'prochaine-revue'),
            'menu_name' => __('Actualités', 'prochaine-revue'),
            'add_new' => __('Ajouter', 'prochaine-revue'),
            'add_new_item' => __('Ajouter une actualité', 'prochaine-revue'),
            'edit_item' => __('Modifier l’actualité', 'prochaine-revue'),
            'new_item' => __('Nouvelle actualité', 'prochaine-revue'),
            'view_item' => __('Voir l’actualité', 'prochaine-revue'),
            'search_items' => __('Rechercher des actualités', 'prochaine-revue'),
            'not_found' => __('Aucune actualité trouvée.', 'prochaine-revue'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-megaphone',
        'rewrite' => array('slug' => 'actualites'),
        'supports' => array('title', 'editor', 'thumbnail'),
    ));
}

/**
 * Enregistrer les types d’actualité.
 */
function pr_register_actualite_taxonomy() {
    register_taxonomy('pr-type-actualite', array('pr_actualite'), array(
        'labels' => array(
            'name' => __('Types d’actualité', 'prochaine-revue'),
            'singular_name' => __('Type d’actualité', 'prochaine-revue'),
            'menu_name' => __('Types d’actualité', 'prochaine-revue'),
            'all_items' => __('Tous les types', 'prochaine-revue'),
            'edit_item' => __('Modifier le type', 'prochaine-revue'),
            'add_new_item' => __('Ajouter un type', 'prochaine-revue'),
            'search_items' => __('Rechercher des types', 'prochaine-revue'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'type-actualite'),
    ));
}

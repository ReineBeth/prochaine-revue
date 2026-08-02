<?php
/**
 * Taxonomie: Types d'articles
 */

if (!defined('ABSPATH')) exit;

/**
 * Enregistrer la taxonomie Types d'articles
 */
add_action('init', 'pr_register_type_article_taxonomy');
function pr_register_type_article_taxonomy() {
    $labels = array(
        'name' => 'Types d\'articles',
        'singular_name' => 'Type d\'article',
        'menu_name' => 'Types d\'articles',
        'all_items' => 'Tous les types',
        'edit_item' => 'Modifier le type',
        'view_item' => 'Voir le type',
        'update_item' => 'Mettre à jour le type',
        'add_new_item' => 'Ajouter un nouveau type',
        'new_item_name' => 'Nom du nouveau type',
        'search_items' => 'Rechercher des types',
        'popular_items' => 'Types populaires',
        'add_or_remove_items' => 'Ajouter ou supprimer des types',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'show_tagcloud' => false,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'rewrite' => array(
            'slug' => 'type-article',
            'with_front' => true,
            'hierarchical' => false,
        ),
    );

    register_taxonomy('pr-type-article', array('pr_article'), $args);
}

/**
 * Ajouter les types par défaut lors de l'activation du thème
 */
/**
 * Ajouter les types par défaut lors de l'activation du thème
 */
add_action('after_switch_theme', 'pr_add_default_article_types');
add_action('admin_init', 'pr_add_default_article_types_check', 999);

function pr_add_default_article_types() {
    pr_insert_default_types();
}

function pr_add_default_article_types_check() {
    // Ne s'exécute qu'une fois pour ne pas ralentir l'admin
    if (get_option('pr_default_types_created')) {
        return;
    }
    
    // Vérifier si les types existent déjà
    $existing_types = get_terms(array(
        'taxonomy' => 'pr-type-article',
        'hide_empty' => false,
    ));
    
    // Si aucun type n'existe, créer les types par défaut
    if (empty($existing_types) || is_wp_error($existing_types)) {
        pr_insert_default_types();
        update_option('pr_default_types_created', true);
    }
}

function pr_insert_default_types() {
    $default_types = array(
        array(
            'name' => 'Note de recherche',
            'slug' => 'note-recherche',
            'description' => 'Documents de recherche académique'
        ),
        array(
            'name' => 'Compte Rendu',
            'slug' => 'compte-rendu',
            'description' => 'Comptes rendus et synthèses'
        ),
        array(
            'name' => 'Texte réflexif',
            'slug' => 'texte-reflexif',
            'description' => 'Textes d\'opinion et de réflexion'
        ),
        array(
            'name' => 'Article',
            'slug' => 'article',
            'description' => 'Articles généraux'
        ),
    );

    foreach ($default_types as $type) {
        // Vérifier si le terme existe déjà
        $term_exists = term_exists($type['slug'], 'pr-type-article');
        
        if (!$term_exists) {
            wp_insert_term(
                $type['name'],
                'pr-type-article',
                array(
                    'slug' => $type['slug'],
                    'description' => $type['description']
                )
            );
        }
    }
}

/**
 * Ajouter des colonnes personnalisées dans l'admin pour la taxonomie pr-type-article
 */
add_filter('manage_edit-pr-type-article_columns', 'pr_type_article_columns');
function pr_type_article_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['name'] = $columns['name'];
    $new_columns['posts'] = $columns['posts'];
    $new_columns['slug'] = $columns['slug'];
    
    return $new_columns;
}

/**
 * Fonction helper pour récupérer le type d'un article
 * 
 * @param int|null $post_id ID de l'article (null = article courant)
 * @return array|null Données du type d'article ou null
 */
function pr_get_article_type($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $types = get_the_terms($post_id, 'pr-type-article');
    
    if (!$types || is_wp_error($types)) {
        return null;
    }
    
    $type = $types[0]; // On prend le premier type (un article ne devrait avoir qu'un type)
    
    return array(
        'id' => $type->term_id,
        'name' => $type->name,
        'slug' => $type->slug,
        'link' => get_term_link($type),
        'description' => $type->description,
        'count' => $type->count
    );
}

/**
 * Shortcode pour afficher le type d'article
 * 
 * Usage: 
 * [afficher_type_article] - Affiche le type de l'article courant
 * [afficher_type_article post_id="123"] - Affiche le type d'un article spécifique
 * [afficher_type_article link="yes"] - Avec lien vers l'archive du type
 */
add_shortcode('afficher_type_article', 'pr_afficher_type_article_shortcode');
function pr_afficher_type_article_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
        'link' => 'no',
        'class' => 'type-article'
    ), $atts);
    
    $type = pr_get_article_type($atts['post_id']);
    
    if (!$type) {
        return '';
    }
    
    $output = wp_kses_post($type['name']);
    
    // Ajouter le lien si demandé
    if ($atts['link'] === 'yes') {
        $output = '<a href="' . esc_url($type['link']) . '" class="type-article-link">' . $output . '</a>';
    }
    
    return '<span class="' . esc_attr($atts['class']) . '">' . $output . '</span>';
}

/**
 * Fonction pour obtenir le nombre total d'articles par type
 * 
 * @param int $term_id ID du terme type d'article
 * @return int Nombre d'articles
 */
function pr_get_type_article_count($term_id) {
    $term = get_term($term_id, 'pr-type-article');
    return $term && !is_wp_error($term) ? $term->count : 0;
}

/**
 * Fonction pour obtenir tous les types d'articles avec leurs compteurs
 * 
 * @return array Tableau de types d'articles
 */
function pr_get_all_types_articles() {
    $types = get_terms(array(
        'taxonomy' => 'pr-type-article',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false
    ));
    
    if (is_wp_error($types) || empty($types)) {
        return array();
    }
    
    $all_types = array();
    
    foreach ($types as $type) {
        $all_types[] = array(
            'id' => $type->term_id,
            'name' => $type->name,
            'slug' => $type->slug,
            'count' => $type->count,
            'link' => get_term_link($type),
            'description' => $type->description
        );
    }
    
    return $all_types;
}

/**
 * Shortcode pour afficher tous les types d'articles
 * Usage: [liste_types_articles show_count="yes"]
 */
add_shortcode('liste_types_articles', 'pr_liste_types_articles_shortcode');
function pr_liste_types_articles_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_count' => 'yes',
        'title' => 'Types d\'articles',
        'show_empty' => 'no'
    ), $atts);
    
    $args = array(
        'taxonomy' => 'pr-type-article',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => ($atts['show_empty'] === 'no')
    );
    
    $types = get_terms($args);
    
    if (empty($types) || is_wp_error($types)) {
        return '<p>Aucun type d\'article trouvé.</p>';
    }
    
    $output = '<div class="pr-types-articles">';
    
    if (!empty($atts['title'])) {
        $output .= '<h3>' . wp_kses_post($atts['title']) . '</h3>';
    }
    
    $output .= '<ul class="types-list">';
    
    foreach ($types as $type) {
        $output .= '<li class="type-item">';
        $output .= '<a href="' . esc_url(get_term_link($type)) . '" class="type-link">';
        $output .= '<span class="type-name">' . wp_kses_post($type->name) . '</span>';
        
        if ($atts['show_count'] === 'yes') {
            $output .= ' <span class="type-count">(' . $type->count . ')</span>';
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Ajouter les types d'articles à l'API REST
 */
add_action('rest_api_init', 'pr_register_type_article_rest_fields');
function pr_register_type_article_rest_fields() {
    register_rest_field('pr_article', 'type_article_details', array(
        'get_callback' => function($post) {
            return pr_get_article_type($post['id']);
        },
        'schema' => array(
            'description' => 'Détails du type d\'article',
            'type' => 'object'
        )
    ));
}

/**
 * Filtrer les articles par type dans l'admin
 */
add_action('restrict_manage_posts', 'pr_filter_articles_by_type');
function pr_filter_articles_by_type() {
    global $typenow;
    
    if ($typenow == 'pr_article') {
        $selected = isset($_GET['pr_type_article']) ? $_GET['pr_type_article'] : '';
        
        $types = get_terms(array(
            'taxonomy' => 'pr-type-article',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        if (!empty($types) && !is_wp_error($types)) {
            echo '<select name="pr_type_article" id="pr_type_article">';
            echo '<option value="">Tous les types</option>';
            
            foreach ($types as $type) {
                printf(
                    '<option value="%s"%s>%s (%d)</option>',
                    $type->slug,
                    $selected == $type->slug ? ' selected="selected"' : '',
                    wp_kses_post($type->name),
                    $type->count
                );
            }
            
            echo '</select>';
        }
    }
}

/**
 * Appliquer le filtre par type
 */
add_filter('parse_query', 'pr_apply_type_article_filter');
function pr_apply_type_article_filter($query) {
    global $pagenow;
    
    if ($pagenow == 'edit.php' && 
        isset($_GET['post_type']) && 
        $_GET['post_type'] == 'pr_article' && 
        isset($_GET['pr_type_article']) && 
        $_GET['pr_type_article'] != '') {
        
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'pr-type-article',
                'field' => 'slug',
                'terms' => $_GET['pr_type_article']
            )
        );
    }
}
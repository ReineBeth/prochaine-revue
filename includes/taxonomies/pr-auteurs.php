<?php
/**
 * Taxonomie: Auteurs
 * Note: La taxonomie pr-auteurs est créée via ACF
 * Ce fichier contient des fonctions supplémentaires liées aux auteurs
 */

if (!defined('ABSPATH')) exit;

/**
 * Ajouter des colonnes personnalisées dans l'admin pour la taxonomie pr-auteurs
 */
add_filter('manage_edit-pr-auteurs_columns', 'pr_auteurs_columns');
function pr_auteurs_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['name'] = $columns['name'];
    $new_columns['institution'] = __('Institution', 'prochaine-revue');
    $new_columns['posts'] = $columns['posts'];
    $new_columns['slug'] = $columns['slug'];
    
    return $new_columns;
}

/**
 * Afficher le contenu des colonnes personnalisées
 */
add_filter('manage_pr-auteurs_custom_column', 'pr_auteurs_column_content', 10, 3);
function pr_auteurs_column_content($content, $column_name, $term_id) {
    if ($column_name === 'institution') {
        $institution = get_field('auteur_institution', 'pr-auteurs_' . $term_id);
        $content = $institution ? wp_kses_post($institution) : '—';
    }
    return $content;
}

/**
 * Rendre la colonne institution triable
 */
add_filter('manage_edit-pr-auteurs_sortable_columns', 'pr_auteurs_sortable_columns');
function pr_auteurs_sortable_columns($columns) {
    $columns['institution'] = 'institution';
    return $columns;
}

/**
 * Fonction helper pour récupérer les auteurs d'un article avec leurs institutions
 * 
 * @param int|null $post_id ID de l'article (null = article courant)
 * @return array Tableau d'auteurs avec leurs données
 */
function pr_get_article_auteurs($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $auteurs = get_the_terms($post_id, 'pr-auteurs');
    
    if (!$auteurs || is_wp_error($auteurs)) {
        return array();
    }
    
    $auteurs_data = array();
    
    foreach ($auteurs as $auteur) {
        $institution = get_field('auteur_institution', 'pr-auteurs_' . $auteur->term_id);
        
        $auteurs_data[] = array(
            'id' => $auteur->term_id,
            'name' => $auteur->name,
            'slug' => $auteur->slug,
            'institution' => $institution,
            'link' => get_term_link($auteur),
            'description' => $auteur->description,
            'count' => $auteur->count
        );
    }
    
    return $auteurs_data;
}

/**
 * Shortcode pour afficher les auteurs avec leurs institutions
 * 
 * Usage: 
 * [afficher_auteurs] - Affiche les auteurs de l'article courant
 * [afficher_auteurs post_id="123"] - Affiche les auteurs d'un article spécifique
 * [afficher_auteurs show_institution="no"] - Sans les institutions
 * [afficher_auteurs link="yes"] - Avec liens vers les archives auteurs
 * [afficher_auteurs separator=" | "] - Personnaliser le séparateur
 */
add_shortcode('afficher_auteurs', 'pr_afficher_auteurs_shortcode');
function pr_afficher_auteurs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
        'separator' => ', ',
        'show_institution' => 'yes',
        'link' => 'no',
        'class' => 'auteurs-list'
    ), $atts);
    
    $auteurs = pr_get_article_auteurs($atts['post_id']);
    
    if (empty($auteurs)) {
        return '';
    }
    
    $output_items = array();
    
    foreach ($auteurs as $auteur) {
        $auteur_html = wp_kses_post($auteur['name']);
        
        // Ajouter l'institution si demandé
        if ($atts['show_institution'] === 'yes' && !empty($auteur['institution'])) {
            $auteur_html .= ' <span class="auteur-institution">(' . wp_kses_post($auteur['institution']) . ')</span>';
        }
        
        // Ajouter le lien si demandé
        if ($atts['link'] === 'yes') {
            $auteur_html = '<a href="' . esc_url($auteur['link']) . '" class="auteur-link">' . $auteur_html . '</a>';
        }
        
        $output_items[] = $auteur_html;
    }
    
    return '<div class="' . esc_attr($atts['class']) . '">' . implode($atts['separator'], $output_items) . '</div>';
}

/**
 * Fonction pour obtenir le nombre total d'articles par auteur
 * 
 * @param int $term_id ID du terme auteur
 * @return int Nombre d'articles
 */
function pr_get_auteur_article_count($term_id) {
    $term = get_term($term_id, 'pr-auteurs');
    return $term && !is_wp_error($term) ? $term->count : 0;
}

/**
 * Fonction pour obtenir les auteurs les plus actifs
 * 
 * @param int $number Nombre d'auteurs à récupérer
 * @return array Tableau d'auteurs triés par nombre d'articles
 */
function pr_get_top_auteurs($number = 5) {
    $auteurs = get_terms(array(
        'taxonomy' => 'pr-auteurs',
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => $number,
        'hide_empty' => true
    ));
    
    if (is_wp_error($auteurs) || empty($auteurs)) {
        return array();
    }
    
    $top_auteurs = array();
    
    foreach ($auteurs as $auteur) {
        $institution = get_field('auteur_institution', 'pr-auteurs_' . $auteur->term_id);
        
        $top_auteurs[] = array(
            'id' => $auteur->term_id,
            'name' => $auteur->name,
            'slug' => $auteur->slug,
            'institution' => $institution,
            'count' => $auteur->count,
            'link' => get_term_link($auteur)
        );
    }
    
    return $top_auteurs;
}

/**
 * Shortcode pour afficher les auteurs populaires
 * Usage: [auteurs_populaires number="5"]
 */
add_shortcode('auteurs_populaires', 'pr_auteurs_populaires_shortcode');
function pr_auteurs_populaires_shortcode($atts) {
    $atts = shortcode_atts(array(
        'number' => 5,
        'show_count' => 'yes',
        'show_institution' => 'yes',
        'title' => 'Auteurs populaires'
    ), $atts);
    
    $auteurs = pr_get_top_auteurs($atts['number']);
    
    if (empty($auteurs)) {
        return '<p>Aucun auteur trouvé.</p>';
    }
    
    $output = '<div class="pr-auteurs-populaires">';
    
    if (!empty($atts['title'])) {
        $output .= '<h3>' . wp_kses_post($atts['title']) . '</h3>';
    }
    
    $output .= '<ul class="auteurs-list">';
    
    foreach ($auteurs as $auteur) {
        $output .= '<li class="auteur-item">';
        $output .= '<a href="' . esc_url($auteur['link']) . '" class="auteur-link">';
        $output .= '<span class="auteur-name">' . wp_kses_post($auteur['name']) . '</span>';
        
        if ($atts['show_institution'] === 'yes' && !empty($auteur['institution'])) {
            $output .= ' <span class="auteur-institution">(' . wp_kses_post($auteur['institution']) . ')</span>';
        }
        
        if ($atts['show_count'] === 'yes') {
            $output .= ' <span class="auteur-count">(' . $auteur['count'] . ')</span>';
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Ajouter les auteurs à l'API REST
 */
add_action('rest_api_init', 'pr_register_auteurs_rest_fields');
function pr_register_auteurs_rest_fields() {
    register_rest_field('pr_article', 'auteurs_details', array(
        'get_callback' => function($post) {
            return pr_get_article_auteurs($post['id']);
        },
        'schema' => array(
            'description' => 'Détails des auteurs avec institutions',
            'type' => 'array'
        )
    ));
}

/**
 * Filtrer les articles par auteur dans l'admin
 */
add_action('restrict_manage_posts', 'pr_filter_articles_by_auteur');
function pr_filter_articles_by_auteur() {
    global $typenow;
    
    if ($typenow == 'pr_article') {
        $selected = isset($_GET['pr_auteur']) ? $_GET['pr_auteur'] : '';
        
        $auteurs = get_terms(array(
            'taxonomy' => 'pr-auteurs',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        if (!empty($auteurs) && !is_wp_error($auteurs)) {
            echo '<select name="pr_auteur" id="pr_auteur">';
            echo '<option value="">Tous les auteurs</option>';
            
            foreach ($auteurs as $auteur) {
                $institution = get_field('auteur_institution', 'pr-auteurs_' . $auteur->term_id);
                $label = $auteur->name;
                if ($institution) {
                    $label .= ' (' . $institution . ')';
                }
                printf(
                    '<option value="%s"%s>%s (%d)</option>',
                    $auteur->slug,
                    $selected == $auteur->slug ? ' selected="selected"' : '',
                    wp_kses_post($label),
                    $auteur->count
                );
            }
            
            echo '</select>';
        }
    }
}

/**
 * Appliquer le filtre par auteur
 */
add_filter('parse_query', 'pr_apply_auteur_filter');
function pr_apply_auteur_filter($query) {
    global $pagenow;
    
    if ($pagenow == 'edit.php' && 
        isset($_GET['post_type']) && 
        $_GET['post_type'] == 'pr_article' && 
        isset($_GET['pr_auteur']) && 
        $_GET['pr_auteur'] != '') {
        
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'pr-auteurs',
                'field' => 'slug',
                'terms' => $_GET['pr_auteur']
            )
        );
    }
}

/**
 * Ajouter un message dans l'archive auteur si vide
 */
add_action('template_redirect', 'pr_auteur_archive_redirect');
function pr_auteur_archive_redirect() {
    if (is_tax('pr-auteurs')) {
        $term = get_queried_object();
        if ($term && $term->count == 0) {
            // Optionnel: rediriger vers la page des auteurs
            // wp_redirect(home_url('/auteurs/'));
            // exit;
        }
    }
}
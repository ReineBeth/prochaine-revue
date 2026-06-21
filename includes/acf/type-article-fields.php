<?php
/**
 * Champs ACF pour la taxonomie Types d'articles
 */

if (!defined('ABSPATH')) exit;

/**
 * Champs ACF pour la taxonomie (optionnel - si vous voulez ajouter des champs supplémentaires)
 */
add_action('acf/init', 'pr_create_type_article_taxonomy_acf_fields');
function pr_create_type_article_taxonomy_acf_fields() {
    if(!function_exists('acf_add_local_field_group')) return;
    
    // Pour l'instant, pas de champs supplémentaires
    // Mais vous pouvez en ajouter ici si nécessaire
    // Par exemple: une description étendue, une couleur, une icône, etc.
    
    /*
    acf_add_local_field_group(array(
        'key' => 'group_pr_type_article_taxonomy',
        'title' => 'Informations Type d\'article',
        'fields' => array(
            array(
                'key' => 'field_type_article_couleur',
                'label' => 'Couleur',
                'name' => 'type_article_couleur',
                'type' => 'color_picker',
                'required' => 0,
                'show_in_rest' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'pr-type-article',
                ),
            ),
        ),
    ));
    */
}
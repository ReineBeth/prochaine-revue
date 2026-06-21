<?php
/**
 * Champs ACF pour la taxonomie Auteurs
 */

if (!defined('ABSPATH')) exit;

add_action('acf/init', 'pr_create_auteurs_taxonomy_acf_fields');
function pr_create_auteurs_taxonomy_acf_fields() {
    if(!function_exists('acf_add_local_field_group')) return;
    
    acf_add_local_field_group(array(
        'key' => 'group_pr_auteurs_taxonomy',
        'title' => 'Informations Auteur',
        'fields' => array(
            array(
                'key' => 'field_auteur_institution',
                'label' => 'Institution',
                'name' => 'auteur_institution',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Institution d\'affiliation de l\'auteur (ex: Université de Montréal, UQAM, etc.)'
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'pr-auteurs',
                ),
            ),
        ),
    ));
}
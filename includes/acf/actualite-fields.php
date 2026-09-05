<?php
/**
 * Champs ACF pour les actualités PR-01.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'pr_create_actualite_acf_fields');

function pr_create_actualite_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_pr_actualite',
        'title' => 'Contenu de l’actualité',
        'fields' => array(
            array(
                'key' => 'field_actualite_tuile_titre',
                'label' => 'Titre de la tuile',
                'name' => 'actualite_tuile_titre',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Titre affiché sur la carte. Le titre WordPress est utilisé s’il est vide.',
            ),
            array(
                'key' => 'field_actualite_tuile_description',
                'label' => 'Description de la tuile',
                'name' => 'actualite_tuile_description',
                'type' => 'textarea',
                'required' => 0,
                'show_in_rest' => 1,
                'rows' => 4,
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_actualite_image_tuile',
                'label' => 'Image de la tuile',
                'name' => 'actualite_image_tuile',
                'type' => 'image',
                'required' => 0,
                'show_in_rest' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'instructions' => 'Facultative. L’image mise en avant est utilisée en repli si ce champ est vide.',
            ),
            array(
                'key' => 'field_actualite_contenu_modale',
                'label' => 'Contenu de la modale',
                'name' => 'actualite_contenu_modale',
                'type' => 'wysiwyg',
                'required' => 0,
                'show_in_rest' => 1,
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'instructions' => 'Contenu détaillé affiché à l’ouverture. Le contenu WordPress est utilisé en repli si ce champ est vide.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'pr_actualite',
                ),
            ),
        ),
    ));
}

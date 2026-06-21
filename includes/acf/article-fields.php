<?php
/**
 * Champs ACF pour les articles
 */

if (!defined('ABSPATH')) exit;

add_action('acf/init', 'pr_create_articles_acf_fields');
function pr_create_articles_acf_fields() {
    if(!function_exists('acf_add_local_field_group')) return;
    
    acf_add_local_field_group(array(
        'key' => 'group_pr_article',
        'title' => 'Détails de l\'Article',
        'fields' => array(
            array(
                'key' => 'field_article_description',
                'label' => 'Description',
                'name' => 'article_description',
                'type' => 'textarea',
                'required' => 1,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_article_type',
                'label' => 'Type d\'article',
                'name' => 'article_type',
                'type' => 'select',
                'choices' => array(
                    'recherche' => 'Note de recherche',
                    'synthese' => 'Compte Rendu',
                    'opinion' => 'Texte réflexif',
                    'article' => 'Article',
                ),
                'default_value' => 'recherche',
                'required' => 1,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_article_pdf',
                'label' => 'Fichier PDF',
                'name' => 'article_pdf',
                'type' => 'file',
                'return_format' => 'array',
                'mime_types' => 'pdf',
                'required' => 1,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_titre_revue',
                'label' => 'Titre de la revue',
                'name' => 'titre_revue',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_volume',
                'label' => 'Volume',
                'name' => 'volume',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_pages',
                'label' => 'Pages',
                'name' => 'pages',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_annee_publication',
                'label' => 'Année de publication',
                'name' => 'annee_publication',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_numero_volume',
                'label' => 'Numéro de volume',
                'name' => 'numero_volume',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1
            ),
            array(
                'key' => 'field_disciplines',
                'label' => 'Discipline(s) concernée(s)',
                'name' => 'disciplines',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Ex: Éducation, Psychologie, Sociologie'
            ),
            array(
                'key' => 'field_mots_cles',
                'label' => 'Mots clés',
                'name' => 'mots_cles',
                'type' => 'textarea',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Séparez les mots clés par des virgules'
            ),
            array(
                'key' => 'field_droits_auteur',
                'label' => 'Droits d\'auteur',
                'name' => 'droits_auteur',
                'type' => 'text',
                'required' => 0,
                'show_in_rest' => 1,
                'default_value' => 'Tous droits réservés © Les Prochaines Éditions, 2025'
            ),
            array(
                'key' => 'field_mois_publication',
                'label' => 'Mois de publication',
                'name' => 'mois_publication',
                'type' => 'select',
                'choices' => array(
                    'janvier' => 'Janvier',
                    'février' => 'Février',
                    'mars' => 'Mars',
                    'avril' => 'Avril',
                    'mai' => 'Mai',
                    'juin' => 'Juin',
                    'juillet' => 'Juillet',
                    'août' => 'Août',
                    'septembre' => 'Septembre',
                    'octobre' => 'Octobre',
                    'novembre' => 'Novembre',
                    'décembre' => 'Décembre',
                ),
                'required' => 0,
                'show_in_rest' => 1
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'pr_article',
                ),
            ),
        ),
    ));
}
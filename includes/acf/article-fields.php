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
                'key' => 'field_article_mentors',
                'label' => 'Mentor·es',
                'name' => 'article_mentors',
                'type' => 'taxonomy',
                'taxonomy' => 'pr-auteurs',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'id',
                'multiple' => 1,
                'show_in_rest' => 1,
                'instructions' => 'Sélectionner les personnes qui ont collaboré comme mentor·es à cet article. Une personne peut aussi être autrice du même article.'
            ),
            array(
                'key' => 'field_article_type',
                'label' => 'Type d\'article',
                'name' => 'article_type',
                'type' => 'taxonomy',
                'taxonomy' => 'pr-type-article',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 1,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
                'required' => 1,
                'show_in_rest' => 1,
                'multiple' => 0,
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
                'key' => 'field_citation_apa',
                'label' => 'Citation APA',
                'name' => 'citation_apa',
                'type' => 'textarea',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Texte affiché dans la section « Pour citer cet article ».',
                'rows' => 3,
                'new_lines' => ''
            ),
            array(
                'key' => 'field_citation_protocole',
                'label' => 'Protocole La Prochaine Revue',
                'name' => 'citation_protocole',
                'type' => 'textarea',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Texte affiché dans la section « Pour citer cet article ».',
                'rows' => 3,
                'new_lines' => ''
            ),
            array(
                'key' => 'field_citation_ris',
                'label' => 'Fichier RIS',
                'name' => 'citation_ris',
                'type' => 'file',
                'return_format' => 'array',
                'mime_types' => 'ris',
                'required' => 0,
                'show_in_rest' => 1,
                'instructions' => 'Fichier .RIS pour Zotero, Mendeley et autres gestionnaires bibliographiques.'
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

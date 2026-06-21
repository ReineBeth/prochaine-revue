<?php
/**
 * Blocs Gutenberg pour les articles
 */

if (!defined('ABSPATH')) exit;

// Rendu des auteurs
register_block_type('custom-article/auteurs', array(
    'render_callback' => 'pr_render_auteurs_block'
));

function pr_render_auteurs_block($attributes, $content) {
    $post_id = get_the_ID();
    $terms = get_the_terms($post_id, 'pr-auteurs');
    
    if ($terms && !is_wp_error($terms)) {
        $output = '<div class="article-auteurs pr-mt-8">';
        foreach ($terms as $auteur) {
            $institution = get_field('auteur_institution', 'pr-auteurs_' . $auteur->term_id);
            $auteur_display = wp_kses_post($auteur->name);
            if ($institution) {
                $auteur_display .= ' (' . wp_kses_post($institution) . ')';
            }
            $output .= '<p class="auteur-nom">' . $auteur_display . '</p>';
        }
        $output .= '</div>';
        return $output;
    }
    return '';
}

// Rendu de la description
register_block_type('custom-article/description', array(
    'render_callback' => 'pr_render_description_block'
));

function pr_render_description_block($attributes, $content) {
    $post_id = get_the_ID();
    $description = get_field('article_description', $post_id);
    if ($description) {
        return '<p class="article-description pr-mt-8">' . wp_kses_post($description) . '</p>';
    }
    return '';
}

// Rendu du PDF
register_block_type('custom-article/pdf', array(
    'render_callback' => 'pr_render_pdf_block'
));

function pr_render_pdf_block($attributes, $content) {
    $post_id = get_the_ID();
    $pdf = get_field('article_pdf', $post_id);
    if ($pdf) {
        $output = sprintf(
            '<div class="article-pdf"><a href="%s" target="_blank">Télécharger le PDF</a></div>',
            esc_url($pdf['url'])
        );
        $output .= do_shortcode('[social_sharing]');
        return $output;
    }
    return '';
}

// Rendu du type d'article
register_block_type('custom-article/type', array(
    'render_callback' => 'pr_render_type_block'
));

function pr_render_type_block($attributes, $content) {
    $post_id = get_the_ID();
    $type_raw = get_field('article_type', $post_id);
    
    if ($type_raw) {
        $type_choices = array(
            'recherche' => 'Note de recherche',
            'synthese' => 'Compte Rendu',
            'opinion' => 'Texte réflexif',
            'article' => 'Article',
        );
        $type_label = isset($type_choices[$type_raw]) ? $type_choices[$type_raw] : $type_raw;
        return '<p class="article-type pr-mt-8">' . wp_kses_post($type_label) . '</p>';
    }
    return '';
}

// Rendu des disciplines
register_block_type('custom-article/disciplines', array(
    'render_callback' => 'pr_render_disciplines_block'
));

function pr_render_disciplines_block($attributes, $content) {
    $post_id = get_the_ID();
    $disciplines = get_field('disciplines', $post_id);
    if ($disciplines) {
        return '<p class="article-disciplines pr-mt-8">Discipline(s) concernée(s) : ' . wp_kses_post($disciplines) . '</p>';
    }
    return '';
}

// Rendu des mots clés
register_block_type('custom-article/mots-cles', array(
    'render_callback' => 'pr_render_mots_cles_block'
));

function pr_render_mots_cles_block($attributes, $content) {
    $post_id = get_the_ID();
    $mots_cles = get_field('mots_cles', $post_id);
    if ($mots_cles) {
        return '<p class="article-mots-cles pr-mt-8">Mots clés : ' . wp_kses_post($mots_cles) . '</p>';
    }
    return '';
}

// Rendu des droits d'auteur
register_block_type('custom-article/droits-auteur', array(
    'render_callback' => 'pr_render_droits_auteur_block'
));

function pr_render_droits_auteur_block($attributes, $content) {
    $post_id = get_the_ID();
    $droits = get_field('droits_auteur', $post_id);
    if ($droits) {
        return '<p class="article-droits-auteur pr-mt-8">' . wp_kses_post($droits) . '</p>';
    }
    return '';
}

// Rendu des informations de publication
register_block_type('custom-article/infos-publication', array(
    'render_callback' => 'pr_render_infos_publication_block'
));

function pr_render_infos_publication_block($attributes, $content) {
    $post_id = get_the_ID();
    $volume = get_field('volume', $post_id);
    $numero_volume = get_field('numero_volume', $post_id);
    $mois = get_field('mois_publication', $post_id);
    $annee = get_field('annee_publication', $post_id);
    $pages = get_field('pages', $post_id);
    
    $infos = array();
    
    if ($volume && $numero_volume) {
        $infos[] = 'Volume ' . $volume . ', numéro ' . $numero_volume;
    } elseif ($volume) {
        $infos[] = 'Volume ' . $volume;
    }
    
    if ($mois && $annee) {
        $infos[] = $mois . ' ' . $annee;
    } elseif ($annee) {
        $infos[] = $annee;
    }
    
    if ($pages) {
        $infos[] = 'p. ' . $pages;
    }
    
    if (!empty($infos)) {
        $publication_info = implode(', ', $infos) . '.';
        return '<p class="article-infos-publication pr-mt-8">' . wp_kses_post($publication_info) . '</p>';
    }
    
    return '';
}

// Bloc de partage social
register_block_type('custom-article/social-sharing', array(
    'render_callback' => 'pr_render_social_sharing_block'
));

function pr_render_social_sharing_block() {
    return do_shortcode('[social_sharing]');
}
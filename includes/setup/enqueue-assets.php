<?php
/**
 * Gestion des styles et scripts
 */

if (!defined('ABSPATH')) exit;

/**
 * Retourne la date de modification d'un asset pour invalider son cache.
 *
 * @param string $relative_path Chemin relatif à la racine du thème.
 * @return string
 */
function pr_asset_version($relative_path) {
    $asset_path = get_theme_file_path($relative_path);

    if (file_exists($asset_path)) {
        return (string) filemtime($asset_path);
    }

    return (string) wp_get_theme()->get('Version');
}

// Style principal
add_action('wp_enqueue_scripts', 'pr_enqueue_styles');
function pr_enqueue_styles() {
    wp_enqueue_style(
        'prochaine-revue-style', 
        get_stylesheet_directory_uri() . '/style.css', 
        array(),
        pr_asset_version('style.css')
    );
}

// Scripts éditeur de blocs
add_action('enqueue_block_editor_assets', 'pr_block_editor_assets');
function pr_block_editor_assets() {
    // Block Editor Script
    wp_register_script(
        'themeslug-block-editor',
        get_theme_file_uri('assets/js/block-editor.js'), 
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        filemtime(get_theme_file_path('assets/js/block-editor.js')),
        true
    );

    if (wp_script_is('themeslug-block-editor', 'registered')) {
        wp_enqueue_script('themeslug-block-editor');
    }
    
    // Custom Article Blocks
    wp_enqueue_script(
        'custom-article-blocks',
        get_template_directory_uri() . '/blocks/custom-article-blocks.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data'),
        filemtime(get_template_directory() . '/blocks/custom-article-blocks.js'),
        true
    );
    
    // Styles admin
    wp_enqueue_style(
        'prochaine-revue-admin-style',
        get_template_directory_uri() . '/assets/style-admin/style.css',
        array(),
        pr_asset_version('assets/style-admin/style.css')
    );
}

// Styles du bloc tuile
add_action('wp_enqueue_scripts', 'pr_enqueue_tuile_styles');
function pr_enqueue_tuile_styles() {
    $tuile_css_path = get_template_directory() . '/includes/pr-tuile/build/style-index.css';
    $tuile_css_url = get_template_directory_uri() . '/includes/pr-tuile/build/style-index.css';
    
    if (file_exists($tuile_css_path)) {
        wp_enqueue_style(
            'pr-tuile-block-style',
            $tuile_css_url,
            array(),
            filemtime($tuile_css_path)
        );
    }
}

// Citation Tool Script
add_action('wp_enqueue_scripts', 'pr_enqueue_citation_tool');
function pr_enqueue_citation_tool() {
    if (is_singular('pr_article') || is_singular('post')) {
        wp_enqueue_script(
            'citation-tool',
            get_template_directory_uri() . '/assets/js/citation-tool.js',
            array(),
            pr_asset_version('assets/js/citation-tool.js'),
            true
        );
    }
}

// Script du dialogue Actualité : enregistré sur le frontal, chargé par le shortcode uniquement.
add_action('wp_enqueue_scripts', 'pr_register_actualites_dialog_script');
function pr_register_actualites_dialog_script() {
    $script_path = get_template_directory() . '/assets/js/actualites-dialog.js';

    if (file_exists($script_path)) {
        wp_register_script(
            'pr-actualites-dialog',
            get_template_directory_uri() . '/assets/js/actualites-dialog.js',
            array(),
            filemtime($script_path),
            true
        );
    }
}

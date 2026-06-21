<?php
/**
 * Gestion des styles et scripts
 */

if (!defined('ABSPATH')) exit;

// Style principal
add_action('wp_enqueue_scripts', 'pr_enqueue_styles');
function pr_enqueue_styles() {
    wp_enqueue_style(
        'prochaine-revue-style', 
        get_stylesheet_directory_uri() . '/style.css', 
        array(),
        wp_get_theme()->get('Version') 
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
        null
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
            '1.0',
            true
        );
    }
}
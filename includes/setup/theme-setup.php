<?php
/**
 * Configuration générale du thème
 */

if (!defined('ABSPATH')) exit;

// Support des fonctionnalités du thème
add_action('after_setup_theme', 'pr_theme_support');
function pr_theme_support() {
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
}

// Favicon
add_action('wp_head', 'pr_add_favicon');
function pr_add_favicon() {
    $base = get_template_directory_uri() . '/assets/images/favicon';
    ?>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base; ?>/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $base; ?>/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo $base; ?>/android-chrome-512x512.png">
    <link rel="apple-touch-icon" href="<?php echo $base; ?>/apple-touch-icon.png">
    <?php
}

// Initialisation template
add_action('after_switch_theme', 'pr_initialize_template_check');
function pr_initialize_template_check() {
    $template_path = get_template_directory() . '/templates/lecture-article.html';
    add_option('lecture_article_template_modified', filemtime($template_path));
}
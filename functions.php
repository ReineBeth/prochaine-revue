

<?php
// Connecter le style au thème
function prochaine_revue_enqueue_styles() {
    wp_enqueue_style(
        'prochaine-revue-style', 
        get_stylesheet_directory_uri() . '/style.css', 
        wp_get_theme()->get('Version') 
    );
}
add_action('wp_enqueue_scripts', 'prochaine_revue_enqueue_styles');
// FIN Connecter le style au thème

// Ajouter script au thème
add_action('enqueue_block_editor_assets', 'themeslug_block_editor_assets');

function themeslug_block_editor_assets() {
    // Vérifier le chemin et enregistrer le script.
    wp_register_script(
        'themeslug-block-editor',
        get_theme_file_uri('assets/js/block-editor.js'), 
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        filemtime(get_theme_file_path('assets/js/block-editor.js')),
        true
    );

    // Enqueuer le script si l'enregistrement réussit.
    if (wp_script_is('themeslug-block-editor', 'registered')) {
        wp_enqueue_script('themeslug-block-editor');
    } else {
        error_log('Échec du chargement de block-editor.js');
    }
}
// FIN Ajouter script au thème

add_theme_support('editor-styles');
add_theme_support('wp-block-styles');
add_theme_support('align-wide');
?>


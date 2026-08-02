<?php
/**
 * Prochaine Revue - Functions principale
 * 
 * @package ProchaineRevue
 */

// Sécurité - empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir le chemin des includes
define('PR_INCLUDES_PATH', get_template_directory() . '/includes/');

/**
 * Fonction helper pour inclure un fichier s'il existe
 */
function pr_require_file($file_path) {
    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Fichier manquant : ' . $file_path);
        }
    }
}

// === PLUGINS PERSONNALISÉS ===
pr_require_file(get_template_directory() . '/includes/pr-accordeon/pr-accordeon.php');
pr_require_file(get_template_directory() . '/includes/pr-carte/pr-carte.php');
pr_require_file(get_template_directory() . '/includes/pr-tuile/pr-tuile.php');
pr_require_file(get_template_directory() . '/includes/pr-bloc-recherche/pr-bloc-recherche.php');

// === CONFIGURATION DU THÈME ===
pr_require_file(PR_INCLUDES_PATH . 'setup/theme-setup.php');
pr_require_file(PR_INCLUDES_PATH . 'setup/enqueue-assets.php');

// === POST TYPES & TAXONOMIES ===
pr_require_file(PR_INCLUDES_PATH . 'post-types/pr-article.php');
pr_require_file(PR_INCLUDES_PATH . 'taxonomies/pr-auteurs.php');
pr_require_file(PR_INCLUDES_PATH . 'taxonomies/pr-type-article.php'); 

// === CHAMPS ACF ===
pr_require_file(PR_INCLUDES_PATH . 'acf/article-fields.php');
pr_require_file(PR_INCLUDES_PATH . 'acf/auteur-fields.php');
pr_require_file(PR_INCLUDES_PATH . 'acf/type-article-fields.php');

// === BLOCS GUTENBERG ===
pr_require_file(PR_INCLUDES_PATH . 'blocks/article-blocks.php');
// pr_require_file(PR_INCLUDES_PATH . 'blocks/tuile-block.php'); //doublon

// === SHORTCODES ===
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/acf-shortcode.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/citation-tool.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/social-sharing.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/tuiles-dynamiques.php');

// === SCRIPTS & PERSONNALISATIONS ===
pr_require_file(PR_INCLUDES_PATH . 'scripts/search-auteurs.php');
pr_require_file(PR_INCLUDES_PATH . 'scripts/custom-classes.php');

// === MIGRATION DES TYPES VERS TAXONOMIE - À SUPPRIMER APRÈS ===
add_action('admin_init', 'pr_migrate_article_types_once');
function pr_migrate_article_types_once() {
    // Ne s'exécute qu'une seule fois
    if (get_option('pr_types_migrated')) {
        return;
    }
    
    // Mapping ancien slug => nouveau terme
    $type_mapping = array(
        'recherche' => 'note-recherche',
        'synthese' => 'compte-rendu',
        'opinion' => 'texte-reflexif',
        'article' => 'article',
    );
    
    $articles = get_posts(array(
        'post_type' => 'pr_article',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($articles as $article) {
        $old_type = get_field('article_type', $article->ID);
        
        if ($old_type && isset($type_mapping[$old_type])) {
            // Assigner le nouveau terme de taxonomie
            $term = get_term_by('slug', $type_mapping[$old_type], 'pr-type-article');
            
            if ($term) {
                wp_set_object_terms($article->ID, $term->term_id, 'pr-type-article', false);
            }
        }
    }
    
    update_option('pr_types_migrated', true);
    
    // Message de confirmation
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Migration des types d\'articles terminée!</strong></p></div>';
    });
}
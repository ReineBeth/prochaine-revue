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

// === CHAMPS ACF ===
pr_require_file(PR_INCLUDES_PATH . 'acf/article-fields.php');
pr_require_file(PR_INCLUDES_PATH . 'acf/auteur-fields.php');

// === BLOCS GUTENBERG ===
pr_require_file(PR_INCLUDES_PATH . 'blocks/article-blocks.php');
pr_require_file(PR_INCLUDES_PATH . 'blocks/tuile-block.php');

// === SHORTCODES ===
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/acf-shortcode.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/citation-tool.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/social-sharing.php');
pr_require_file(PR_INCLUDES_PATH . 'shortcodes/tuiles-dynamiques.php');

// === SCRIPTS & PERSONNALISATIONS ===
pr_require_file(PR_INCLUDES_PATH . 'scripts/search-auteurs.php');
pr_require_file(PR_INCLUDES_PATH . 'scripts/custom-classes.php');
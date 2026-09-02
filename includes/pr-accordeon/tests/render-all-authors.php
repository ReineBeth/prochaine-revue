<?php

/**
 * Test de regression autonome pour le rendu dynamique des personnes contributrices.
 *
 * Execution : php includes/pr-accordeon/tests/render-all-authors.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ABSPATH', __DIR__);

$captured_get_terms_args = null;

function plugin_dir_path($file) {
    return dirname($file) . DIRECTORY_SEPARATOR;
}

function add_action() {}
function add_shortcode() {}
function plugins_url() {}
function register_block_type() {}

function get_terms($args) {
    global $captured_get_terms_args;
    $captured_get_terms_args = $args;

    return [];
}

function is_wp_error() {
    return false;
}

require dirname(__DIR__) . '/pr-accordeon.php';

render_accordeon_block([
    'mode' => 'dynamic',
    'showAllAuthors' => false,
    'authorCount' => 12,
    'headingLevel' => 3,
], '');

if (($captured_get_terms_args['number'] ?? null) !== 0) {
    fwrite(STDERR, "Echec : le rendu dynamique limite encore la requete des auteurs.\n");
    exit(1);
}

fwrite(STDOUT, "Succes : le rendu dynamique demande tous les auteurs.\n");

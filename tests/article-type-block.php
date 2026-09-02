<?php

/**
 * Test de regression autonome pour le type affiche dans les pages article.
 *
 * Execution : php tests/article-type-block.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ABSPATH', __DIR__);

$article_type_details = [
    'id' => 19,
    'name' => 'Compte Rendu',
    'slug' => 'compte-rendu',
];

function register_block_type() {}

function get_the_ID() {
    return 548;
}

function get_field($field_name) {
    return $field_name === 'article_type' ? 19 : null;
}

function pr_get_article_type() {
    global $article_type_details;

    return $article_type_details;
}

function wp_kses_post($value) {
    return $value;
}

require dirname(__DIR__) . '/includes/blocks/article-blocks.php';

$markup = pr_render_type_block([], '');
$expected = '<p class="article-type pr-mt-8">Compte Rendu</p>';

if ($markup !== $expected) {
    fwrite(STDERR, "Echec : le bloc affiche '$markup' au lieu du nom du type.\n");
    exit(1);
}

$article_type_details = null;
$markup_without_term = pr_render_type_block([], '');

if ($markup_without_term !== '') {
    fwrite(STDERR, "Echec : le bloc affiche un identifiant sans terme valide.\n");
    exit(1);
}

fwrite(STDOUT, "Succes : le bloc affiche le nom du type d'article.\n");

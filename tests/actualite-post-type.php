<?php

/**
 * Test autonome du modèle éditorial PR-01.
 *
 * Execution : php tests/actualite-post-type.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('ABSPATH', __DIR__);

$registered_post_types = array();
$registered_taxonomies = array();

function add_action($hook, $callback) {}

function __($text, $domain = null) {
    return $text;
}

function register_post_type($name, $args) {
    global $registered_post_types;
    $registered_post_types[$name] = $args;
}

function register_taxonomy($name, $object_type, $args) {
    global $registered_taxonomies;
    $registered_taxonomies[$name] = array(
        'object_type' => $object_type,
        'args' => $args,
    );
}

require dirname(__DIR__) . '/includes/post-types/pr-actualite.php';

pr_register_actualite_post_type();
pr_register_actualite_taxonomy();

if (!isset($registered_post_types['pr_actualite'])) {
    fwrite(STDERR, "Echec : le CPT pr_actualite n'est pas enregistré.\n");
    exit(1);
}

$post_type = $registered_post_types['pr_actualite'];

if ($post_type['show_in_rest'] !== true || $post_type['supports'] !== array('title', 'editor', 'excerpt', 'thumbnail')) {
    fwrite(STDERR, "Echec : les supports ou l'exposition REST du CPT sont incorrects.\n");
    exit(1);
}

if (!isset($registered_taxonomies['pr-type-actualite'])) {
    fwrite(STDERR, "Echec : la taxonomie pr-type-actualite n'est pas enregistrée.\n");
    exit(1);
}

$taxonomy = $registered_taxonomies['pr-type-actualite'];

if ($taxonomy['object_type'] !== array('pr_actualite') || $taxonomy['args']['show_in_rest'] !== true) {
    fwrite(STDERR, "Echec : la taxonomie n'est pas liée au CPT ou exposée dans REST.\n");
    exit(1);
}

fwrite(STDOUT, "Succes : le modèle éditorial PR-01 est correctement enregistré.\n");

<?php
/**
 * Ajout de classes personnalisées et modifications diverses
 */

if (!defined('ABSPATH')) exit;

// Ajouter classe parent articles
add_filter('body_class', 'pr_add_articles_parent_class');
function pr_add_articles_parent_class($classes) {
    if (is_page() && ($parent_id = wp_get_post_parent_id(get_the_ID()))) {
        $parent = get_post($parent_id);
        if ($parent->post_name === 'articles') {
            $classes[] = 'page-parent-articles';
        }
    }
    return $classes;
}

// Cacher le titre sur les pages articles
add_filter('render_block', 'pr_add_custom_class_to_post_title', 10, 2);
function pr_add_custom_class_to_post_title($block_content, $block) {
    if (!is_admin() && isset($block['blockName']) && $block['blockName'] === 'core/post-title') {
        global $post;
        
        if (strpos($_SERVER['REQUEST_URI'], '/prochaine-revue/articles/') !== false) {
            $block_content = str_replace('<h1', '<h1 class="pr-display-none"', $block_content);
        }
    }
    return $block_content;
}

// Mise à jour des pages articles (template)
function pr_update_article_pages_template() {
    $template_path = get_template_directory() . '/templates/lecture-article.html';
    $template_content = file_get_contents($template_path);

    $articles_page = get_page_by_path('articles');
    if (!$articles_page) return;

    $pages = get_pages(array(
        'child_of' => $articles_page->ID
    ));

    foreach ($pages as $page) {
        $article = get_posts(array(
            'post_type' => 'pr_article',
            'title' => $page->post_title,
            'posts_per_page' => 1
        ));

        if (!empty($article)) {
            $article_id = $article[0]->ID;
            $updated_content = str_replace(
                '[my_acf field="',
                '[my_acf post_id="' . $article_id . '" field="',
                $template_content
            );

            wp_update_post(array(
                'ID' => $page->ID,
                'post_content' => $updated_content
            ));
        }
    }
}
<?php
/**
 * Bloc pour afficher les tuiles d'articles dynamiquement
 */
function render_tuiles_articles_block($attributes, $content) {
    $articles = get_posts(array(
        'post_type' => 'pr_article',
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if (empty($articles)) {
        return '<p>Aucun article disponible.</p>';
    }
    
    ob_start();
    ?>
    <div class="pr-tuile-container">
        <?php foreach ($articles as $article) : 
            $title = get_the_title($article->ID);
            $description = get_field('article_description', $article->ID);
            $thumbnail = get_the_post_thumbnail_url($article->ID, 'medium');
            $slug = $article->post_name;
            $articles_page_url = home_url('/articles/' . $slug . '/');
        ?>
            <a class="pr-tuile-lien" href="<?php echo esc_url($articles_page_url); ?>" rel="noopener noreferrer">
                <?php if ($thumbnail) : ?>
                    <div class="pr-tuile-lien-image">
                        <img src="<?php echo esc_url($thumbnail); ?>" 
                             alt="<?php echo esc_attr($title); ?>" 
                             loading="lazy" />
                    </div>
                <?php endif; ?>
                <div class="pr-tuile-lien-text">
                    <h3><?php echo esc_html($title); ?></h3>
                    <?php if ($description) : ?>
                        <p><?php echo esc_html(wp_trim_words($description, 20)); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Enregistrer le bloc
function register_tuiles_articles_block() {
    register_block_type('custom/tuiles-articles', array(
        'render_callback' => 'render_tuiles_articles_block',
        'attributes' => array(
            'nombre' => array(
                'type' => 'number',
                'default' => 3
            )
        )
    ));
}
add_action('init', 'register_tuiles_articles_block');
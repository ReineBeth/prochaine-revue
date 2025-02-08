

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

//Cree un shortcode pour les fiels de la page lecture-article
function my_custom_acf_shortcode($atts) {
    $atts = shortcode_atts(array(
        'field' => '',
    ), $atts);

    $value = get_field($atts['field']);
    
    if($atts['field'] == 'article_pdf' && !empty($value)) {
        return '<object data="' . esc_url($value) . '" type="application/pdf" width="100%" height="800px">
            <p>Votre navigateur ne peut pas afficher ce PDF. 
            <a href="' . esc_url($value) . '">Téléchargez-le ici</a></p>
        </object>';
    }

    if($atts['field'] == 'img-a-propos') {
        return '<div class="wp-block-image">' . '<img src="' . $value['url'] . '" alt="' . $value['alt'] . '">' . '</div>';
    }

    return $value;
}
add_shortcode('my_acf', 'my_custom_acf_shortcode');

// TEST
function create_articles_post_type() {
    register_post_type('pr_article',
        array(
            'labels' => array(
                'name' => __('Articles'),
                'singular_name' => __('Article'),
                'menu_name' => __('Articles'),
                'add_new' => __('Ajouter un Article'),
                'add_new_item' => __('Ajouter un nouvel Article')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-media-document',
            'show_in_rest' => true // Important pour Gutenberg
        )
    );
}
add_action('init', 'create_articles_post_type');

function create_articles_acf_fields() {
    if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_pr_article',
        'title' => 'Détails de l\'Article',
        'fields' => array(
            array(
                'key' => 'field_article_description',
                'label' => 'Description',
                'name' => 'article_description',
                'type' => 'textarea',
                'required' => 1,
                'show_in_rest' => true
            ),
            array(
                'key' => 'field_article_auteur',
                'label' => 'Auteurs',
                'name' => 'article_auteurs',
                'type' => 'text',
                'required' => 1,
                'show_in_rest' => true
            ),
            array(
                'key' => 'field_article_pdf',
                'label' => 'Fichier PDF',
                'name' => 'article_pdf',
                'type' => 'file',
                'return_format' => 'array',
                'mime_types' => 'pdf',
                'required' => 1,
                'show_in_rest' => true
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'pr_article',
                ),
            ),
        ),
    ));
    endif;
}
add_action('acf/init', 'create_articles_acf_fields');

function enqueue_custom_article_blocks() {
    // Chemin vers ton fichier JavaScript
    $script_url = get_template_directory_uri() . '/blocks/custom-article-blocks.js';
    
    // Enregistre et charge le fichier JavaScript
    wp_enqueue_script(
        'custom-article-blocks', // Handle du script
        $script_url, // URL du script
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data'), // Dépendances (cela inclut les bibliothèques nécessaires pour Gutenberg)
        filemtime(get_template_directory() . '/blocks/custom-article-blocks.js'), // Version basée sur le timestamp du fichier
        true // Charger dans le footer
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_custom_article_blocks');

// Rendu des auteurs
register_block_type('custom-article/auteurs', array(
    'render_callback' => function($attributes, $content) {
        $post_id = get_the_ID();
        $auteurs = get_field('article_auteurs', $post_id);
        if ($auteurs) {
            return '<div class="article-auteurs">Auteurs: ' . esc_html($auteurs) . '</div>';
        }
        return '';
    }
));

// Rendu de la description
register_block_type('custom-article/description', array(
    'render_callback' => function($attributes, $content) {
        $post_id = get_the_ID();
        $description = get_field('article_description', $post_id);
        if ($description) {
            return '<div class="article-description">' . esc_html($description) . '</div>';
        }
        return '';
    }
));

// Rendu du PDF
register_block_type('custom-article/pdf', array(
    'render_callback' => function($attributes, $content) {
        $post_id = get_the_ID();
        $pdf = get_field('article_pdf', $post_id);
        if ($pdf) {
            return sprintf(
                '<div class="article-pdf"><a href="%s" target="_blank">Télécharger le PDF</a></div>',
                esc_url($pdf['url'])
            );
        }
        return '';
    }
));


// TEST

// TEST 2
add_filter('rest_prepare_pr_article', function ($response, $post, $request) {
    $acf_fields = get_fields($post->ID);
    if ($acf_fields) {
        $response->data['acf'] = $acf_fields;
    }
    return $response;
}, 10, 3);
// TEST 2


// Créer une taxonomie pour les auteurs
function create_custom_taxonomy() {
    register_taxonomy(
        'auteurs', // Slug de la taxonomie
        'post',  // Type de post (post, page ou CPT)
        array(
            'label' => 'Auteurs',
            'hierarchical' => false, // true pour type catégorie, false pour type tag
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true
        )
    );
}
add_action('init', 'create_custom_taxonomy');

// FIN Ajouter script au thème

add_theme_support('editor-styles');
add_theme_support('wp-block-styles');
add_theme_support('align-wide');
?>


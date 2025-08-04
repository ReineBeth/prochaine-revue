

<?php
// Inclure les fonctionnalités des plugins
require_once get_template_directory() . './includes/pr-accordeon/pr-accordeon.php';
require_once get_template_directory() . './includes/pr-carte/pr-carte.php';
require_once get_template_directory() . './includes/pr-tuile/pr-tuile.php';
require_once get_template_directory() . './includes/pr-bloc-recherche/pr-bloc-recherche.php';

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
        'post_id' => get_the_ID(), // Par défaut, utilise l'ID de la page/post courante
    ), $atts);
    
    // Utiliser le post_id fourni ou l'ID courant
    $post_id = $atts['post_id'] ? $atts['post_id'] : get_the_ID();
    $value = get_field($atts['field'], $post_id);
    
    if($atts['field'] == 'article_pdf' && !empty($value)) {
        // Gérer le cas où $value est un tableau (format array) ou une URL
        $pdf_url = is_array($value) ? $value['url'] : $value;
        return '<object data="' . esc_url($pdf_url) . '" type="application/pdf" width="100%" height="800px">
            <p>Votre navigateur ne peut pas afficher ce PDF. 
            <a href="' . esc_url($pdf_url) . '">Téléchargez-le ici</a></p>
        </object>';
    }
    if($atts['field'] == 'img-a-propos' && !empty($value)) {
        $img_url = is_array($value) ? $value['url'] : $value;
        $img_alt = is_array($value) ? $value['alt'] : '';
        return '<div class="wp-block-image">' . '<img src="' . $img_url . '" alt="' . $img_alt . '">' . '</div>';
    }
    return $value ? $value : '';
}
add_shortcode('my_acf', 'my_custom_acf_shortcode');

function create_articles_post_type() {
    register_post_type('pr_article',
        array(
            'labels' => array(
                'name' => __('Articles'),
                'singular_name' => __('Article'),
                'menu_name' => __('PR Articles'),
                'add_new' => __('Ajouter un Article'),
                'add_new_item' => __('Ajouter un nouvel Article')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-media-document',
            'show_in_rest' => true, // Important pour Gutenberg
            'taxonomies' => ['pr-auteurs']
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

            $output .= do_shortcode('[social_sharing]');
        }
        return '';
    }
));


add_filter('rest_prepare_pr_article', function ($response, $post, $request) {
    $acf_fields = get_fields($post->ID);
    if ($acf_fields) {
        $response->data['acf'] = $acf_fields;
    }
    return $response;
}, 10, 3);

//Bouton de partages pour les réseaux sociaux

function floating_share_button_shortcode() {
    global $post;
    
    // Récupérer l'URL et le titre de l'article
    $url = urlencode(get_permalink());
    $title = urlencode(get_the_title());
    
    // Récupérer l'image mise en avant (si disponible)
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    }
    
    // Construire les URLs de partage
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url;
    $whatsapp_url = 'https://wa.me/?text=' . $title . ' ' . $url;
    $messenger_url = 'https://www.facebook.com/dialog/send?link=' . $url . '&app_id=YOUR_FACEBOOK_APP_ID&redirect_uri=' . $url;
    $email_url = 'mailto:?subject=' . $title . '&body=Découvrez cet article: ' . $url;
    
    // Générer le HTML pour le bouton flottant
    $output = '<div class="floating-share-button">';

    $output .= '<div class="share-buttons">';
    
    // Facebook
    $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="share-button facebook">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    $output .= '</a>';
    
    // Twitter/X
    $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" class="share-button twitter">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19.9 7.24c.013.194.013.388.013.582 0 5.963-4.582 12.834-12.834 12.834-2.551 0-4.921-.746-6.917-2.02.363.044.713.063 1.088.063 2.107 0 4.043-.713 5.587-1.924-1.975-.038-3.638-1.326-4.205-3.106.275.05.55.75.838.075.4 0 .8-.05 1.175-.15-2.05-.413-3.6-2.226-3.6-4.401v-.05c.6.338 1.3.538 2.038.563-1.2-.8-2-2.176-2-3.726 0-.825.225-1.587.6-2.25 2.176 2.688 5.45 4.45 9.113 4.638-.075-.337-.1-.675-.1-1.013 0-2.45 2-4.45 4.476-4.45 1.275 0 2.425.538 3.237 1.4.988-.188 1.95-.55 2.787-1.038-.325 1.013-1.013 1.875-1.925 2.413.888-.1 1.75-.325 2.538-.65-.613.863-1.375 1.638-2.251 2.263z"/></svg>';
    $output .= '</a>';
    
    // LinkedIn
    $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" class="share-button linkedin">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
    $output .= '</a>';
    
    // WhatsApp
    $output .= '<a href="' . esc_url($whatsapp_url) . '" target="_blank" rel="noopener noreferrer" class="share-button whatsapp">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>';
    $output .= '</a>';

    // Messenger
    $output .= '<a href="' . esc_url($messenger_url) . '" target="_blank" rel="noopener noreferrer" class="share-button messenger">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 4.975-12 11.111 0 3.497 1.745 6.616 4.472 8.652v4.237l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111 0-6.136-5.373-11.111-12-11.111zm1.193 14.963l-3.056-3.259-5.963 3.259 6.559-6.963 3.13 3.259 5.889-3.259-6.559 6.963z"/></svg>';
    $output .= '</a>';
    
    // Email
    $output .= '<a href="' . esc_url($email_url) . '" class="share-button email">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm21.518 2l-9.518 7.713-9.518-7.713h19.036zm-19.518 14v-11.817l10 8.104 10-8.104v11.817h-20z"/></svg>';
    $output .= '</a>';
    
    $output .= '</div>'; // Fin des boutons de partage
    $output .= '<div class="share-toggle">';
    $output .= '<svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 362.621 362.621" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#ffffff;" d="M288.753,121.491c33.495,0,60.746-27.251,60.746-60.746S322.248,0,288.753,0 s-60.745,27.25-60.745,60.746c0,6.307,0.968,12.393,2.76,18.117l-126.099,76.937c-9.707-8.322-22.301-13.366-36.059-13.366 c-30.596,0-55.487,24.891-55.487,55.487s24.892,55.487,55.487,55.487c10.889,0,21.047-3.165,29.626-8.606l101.722,58.194 c-0.584,3.058-0.902,6.209-0.902,9.435c0,27.676,22.516,50.192,50.191,50.192s50.191-22.516,50.191-50.192 s-22.516-50.191-50.191-50.191c-13.637,0-26.014,5.474-35.069,14.331l-95.542-54.658c3.498-7.265,5.46-15.403,5.46-23.991 c0-5.99-0.966-11.757-2.73-17.166l125.184-76.379C257.488,114.959,272.368,121.491,288.753,121.491z"></path> </g></svg>';
    $output .= '</div>';
    $output .= '</div>'; // Fin du bouton flottant

    return $output;
}
add_shortcode('floating_share', 'floating_share_button_shortcode');

function register_social_sharing_block() {
    register_block_type('custom-article/social-sharing', array(
        'render_callback' => 'render_social_sharing_block'
    ));
}
add_action('init', 'register_social_sharing_block');

function render_social_sharing_block() {
    return do_shortcode('[social_sharing]');
}

// TEST CRÉER PAGE ARTICLES AUTOMATIQUMENT 
// Fonction pour mettre à jour toutes les pages d'articles existantes
function update_article_pages_template() {
    // Récupère le template
    $template_path = get_template_directory() . '/templates/lecture-article.html';
    $template_content = file_get_contents($template_path);

    // Récupère toutes les pages enfants de la page "articles"
    $articles_page = get_page_by_path('articles');
    if (!$articles_page) return;

    $pages = get_pages(array(
        'child_of' => $articles_page->ID
    ));

    foreach ($pages as $page) {
        // Pour chaque page, trouve l'article PR correspondant
        $article = get_posts(array(
            'post_type' => 'pr_article',
            'title' => $page->post_title,
            'posts_per_page' => 1
        ));

        if (!empty($article)) {
            $article_id = $article[0]->ID;
            // Met à jour le contenu avec le nouveau template
            $updated_content = str_replace(
                '[my_acf field="',
                '[my_acf post_id="' . $article_id . '" field="',
                $template_content
            );

            // Met à jour la page
            wp_update_post(array(
                'ID' => $page->ID,
                'post_content' => $updated_content
            ));
        }
    }
}

// Ajoute un hook pour détecter les modifications du fichier template
function check_template_modification() {
    $template_path = get_template_directory() . '/templates/lecture-article.html';
    $last_modified = get_option('lecture_article_template_modified');
    $current_modified = filemtime($template_path);

    if ($last_modified != $current_modified) {
        update_article_pages_template();
        update_option('lecture_article_template_modified', $current_modified);
    }
}
add_action('init', 'check_template_modification');

function initialize_template_modification_check() {
    $template_path = get_template_directory() . '/templates/lecture-article.html';
    add_option('lecture_article_template_modified', filemtime($template_path));
}
add_action('after_switch_theme', 'initialize_template_modification_check');

function add_articles_parent_class($classes) {
    if (is_page() && ($parent_id = wp_get_post_parent_id(get_the_ID()))) {
        $parent = get_post($parent_id);
        if ($parent->post_name === 'articles') {
            $classes[] = 'page-parent-articles';
        }
    }
    return $classes;
}
add_filter('body_class', 'add_articles_parent_class');

function add_custom_class_to_post_title($block_content, $block) {
    if (!is_admin() && isset($block['blockName']) && $block['blockName'] === 'core/post-title') {
        global $post;

        // Vérifie si l'URL est sous /prochaine-revue/articles/
        if (strpos($_SERVER['REQUEST_URI'], '/prochaine-revue/articles/') !== false) {
            // Ajoute la classe "wp-default-title"
            $block_content = str_replace('<h1', '<h1 class="pr-display-none"', $block_content);
        }
    }
    return $block_content;
}
add_filter('render_block', 'add_custom_class_to_post_title', 10, 2);

// TEST CRÉER PAGE ARTICLES AUTOMATIQUMENT 

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


// Enregistrement du shortcode des auteurs
// add_action('init', function() {
//     add_shortcode('liste_auteurs_articles', function() {
//         return display_authors_with_articles();
//     });
// });

function ajouter_script_recherche_auteurs() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('recherche-auteurs');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchText = e.target.value.toLowerCase();
                // On cible les conteneurs principaux des accordéons
                const auteursContainers = document.querySelectorAll('.pr-accordeon-container');
                
                auteursContainers.forEach(container => {
                    const button = container.querySelector('.pr-accordeon-trigger');
                    if (button) {
                        const auteurText = button.textContent.toLowerCase();
                        if (auteurText.includes(searchText)) {
                            container.style.display = 'block';  // ou '' selon votre style par défaut
                            container.style.visibility = 'visible';
                            container.style.margin = '';        // Réinitialise la marge
                            container.style.height = '';        // Réinitialise la hauteur
                            container.style.opacity = '1';
                        } else {
                            container.style.display = 'none';
                            container.style.visibility = 'hidden';
                            container.style.margin = '0';
                            container.style.height = '0';
                            container.style.opacity = '0';
                        }
                    }
                });
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'ajouter_script_recherche_auteurs');


// Outil de citation

function citation_tool_shortcode() {
    // Récupérer les métadonnées de l'article
    global $post;
    
    // Récupérer les informations de l'article
    $author = get_field('auteurs_article', $post->ID) ? get_field('auteurs_article', $post->ID) : get_the_author();
    $title = get_the_title();
    $revue = get_field('titre_revue', $post->ID) ? get_field('titre_revue', $post->ID) : '';
    $volume = get_field('volume', $post->ID) ? get_field('volume', $post->ID) : '';
    $numero_volume = get_field('numero_volume', $post->ID) ? get_field('numero_volume', $post->ID) : '';
    $year =  get_field('annee_publication', $post->ID) ? get_field('annee_publication', $post->ID) : '';
    $pages = get_field('pages', $post->ID) ? get_field('pages', $post->ID) : '';
    $url = get_permalink();

    // Construire les différents formats de citation
    // Format MLA
    $mla_citation = $author . '. « ' . $title . '. » <em>' . $revue . '</em>';
    if ($volume) $mla_citation .= ', volume ' . $volume;
    if ($numero_volume) $mla_citation .= ', numéro ' . $numero_volume;
    $mla_citation .= ', ' . $year;
    if ($pages) $mla_citation .= ', p. ' . $pages;
    $mla_citation .= '. ' . $url;
    
    // Format APA
    $apa_citation = $author . ' (' . $year . '). ' . $title . '. <em>' . $revue . '</em>';
    if ($volume) $apa_citation .= ', ' . $volume;
    if ($numero_volume) $apa_citation .= '(' . $numero_volume . ')';
    if ($pages) $apa_citation .= ', ' . $pages;
    $apa_citation .= '. ' . $url;
    
    // Format Chicago
    $chicago_citation = $author . ' « ' . $title . ' ». <em>' . $revue . '</em>';
    if ($volume) $chicago_citation .= ' ' . $volume;
    if ($numero_volume) $chicago_citation .= ', n° ' . $numero_volume;
    $chicago_citation .= ' (' . $year . ')';
    if ($pages) $chicago_citation .= ' : ' . $pages;
    $chicago_citation .= '. ' . $url;
    
    // Échapper les variables pour JavaScript
    $js_author = esc_js($author);
    $js_title = esc_js($title);
    // $js_journal = esc_js($journal);
    $js_volume = esc_js($revue);
    $js_issue = esc_js($numero_volume);
    $js_year = esc_js($year);
    $js_pages = esc_js($pages);
    $js_url = esc_js($url);
    
    // Générer le HTML pour l'outil de citation
    $output = '<div class="citation-tool-container">';
    $output .= '<button id="citation-button" class="citation-button"><svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g id="right_x5F_quote"> <g> <path style="fill:#ffffff;" d="M0,4v12h8c0,4.41-3.586,8-8,8v4c6.617,0,12-5.383,12-12V4H0z"></path> <path style="fill:#ffffff;" d="M20,4v12h8c0,4.41-3.586,8-8,8v4c6.617,0,12-5.383,12-12V4H20z"></path> </g> </g> </g> </g></svg></button>';
    
    // Modale pour la citation
    $output .= '<div id="citation-modal" class="citation-modal">';
    $output .= '<div class="citation-modal-content">';
    $output .= '<div class="citation-modal-header">';
    $output .= '<h2>Outils de citation</h2>';
    $output .= '<span class="citation-close"><svg fill="#ffffff" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55 c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55 c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505 c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55 l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719 c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path> </g></svg></span>';
    $output .= '</div>'; // Fin de l'en-tête
    
    $output .= '<div class="citation-modal-body">';
    $output .= '<h3>Citer cet article</h3>';
    
    // Format MLA
    $output .= '<div class="citation-format">';
    $output .= '<h4>MLA</h4>';
    $output .= '<p class="citation-text">' . $mla_citation . '</p>';
    $output .= '</div>';
    
    // Format APA
    $output .= '<div class="citation-format">';
    $output .= '<h4>APA</h4>';
    $output .= '<p class="citation-text">' . $apa_citation . '</p>';
    $output .= '</div>';
    
    // Format Chicago
    $output .= '<div class="citation-format">';
    $output .= '<h4>Chicago</h4>';
    $output .= '<p class="citation-text">' . $chicago_citation . '</p>';
    $output .= '</div>';
    
    $output .= '</div>'; // Fin du corps
    $output .= '</div>'; // Fin du contenu
    $output .= '</div>'; // Fin de la modale
    
    $output .= '</div>'; // Fin du conteneur principal

    wp_enqueue_script('citation-tool', get_template_directory_uri() . '/assets/js/citation-tool.js', array('jquery'), '1.0', true);
    
    return $output;
}

add_shortcode('citation_tool', 'citation_tool_shortcode');
function enqueue_citation_tool_script() {
    if (is_singular('pr_article') || is_singular('post')) {
        wp_enqueue_script('citation-tool', get_template_directory_uri() . '/assets/js/citation-tool.js', array(), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_citation_tool_script');

function add_citation_inline_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const citationButton = document.getElementById('citation-button');
        const citationModal = document.getElementById('citation-modal');
        

        // Fermer la modale quand on clique sur le X
        const closeButton = document.querySelector('.citation-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                citationModal.style.display = 'none';
            });
        }
        
        // Fermer la modale quand on clique en dehors du contenu
        window.addEventListener('click', function(event) {
            if (event.target === citationModal) {
                citationModal.style.display = 'none';
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'add_citation_inline_script');

// FIN Ajouter script au thème

add_theme_support('editor-styles');
add_theme_support('wp-block-styles');
add_theme_support('align-wide');
?>

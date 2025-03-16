<?php
/**
 * Plugin Name:       Pr Carte
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pr-carte
 *
 * @package CreateBlock
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enregistre le bloc avec ses attributs et son callback de rendu
 */
function create_block_pr_carte_block_init() {
    register_block_type(__DIR__ . '/build', array(
        'attributes' => array(
            'cards' => array(
                'type' => 'array',
                'default' => [],
                'items' => array(
                    'type' => 'object',
                    'properties' => array(
                        'titleField' => array(
                            'type' => 'string',
                            'default' => ''
                        ),
                        'subtitleField' => array(
                            'type' => 'string',
                            'default' => ''
                        ),
                        'textField' => array(
                            'type' => 'string',
                            'default' => ''
                        ),
                        'imageUrl' => array(
                            'type' => 'string',
                            'default' => ''
                        ),
                        'showImage' => array(
                            'type' => 'boolean',
                            'default' => false
                        ),
                        'imageAlt' => array(
                            'type' => 'string',
                            'default' => ''
                        )
                    )
                )
            )
        ),
        'render_callback' => 'render_block_pr_carte'
    ));
}
add_action('init', 'create_block_pr_carte_block_init');

/**
 * Enregistre le JavaScript pour l'animation des cartes
 */
function enqueue_pr_carte_assets() {
    wp_enqueue_script(
        'pr-carte-flip',
        plugins_url('assets/js/flip.js', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'assets/js/flip.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_pr_carte_assets');

/**
 * Rendu du bloc
 * @param array    $attributes Les attributs du bloc
 * @param string   $content    Le contenu du bloc
 * @return string  Le HTML du bloc
 */
function render_block_pr_carte($attributes) {
    $cards = $attributes['cards'] ?? array();

    $flip_icon = '<svg width="48" height="48" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m21.897 13.404.008-.057v.002c.024-.178.044-.357.058-.537.024-.302-.189-.811-.749-.811-.391 0-.715.3-.747.69-.018.221-.044.44-.078.656-.645 4.051-4.158 7.153-8.391 7.153-3.037 0-5.704-1.597-7.206-3.995l1.991-.005c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-4.033c-.414 0-.75.336-.75.75v4.049c0 .414.336.75.75.75s.75-.335.75-.75l.003-2.525c1.765 2.836 4.911 4.726 8.495 4.726 5.042 0 9.217-3.741 9.899-8.596zm-19.774-2.974-.009.056v-.002c-.035.233-.063.469-.082.708-.024.302.189.811.749.811.391 0 .715-.3.747-.69.022-.28.058-.556.107-.827.716-3.968 4.189-6.982 8.362-6.982 3.037 0 5.704 1.597 7.206 3.995l-1.991.005c-.414 0-.75.336-.75.75s.336.75.75.75h4.033c.414 0 .75-.336.75-.75v-4.049c0-.414-.336-.75-.75-.75s-.75.335-.75.75l-.003 2.525c-1.765-2.836-4.911-4.726-8.495-4.726-4.984 0-9.12 3.654-9.874 8.426z" fill-rule="nonzero"/></svg>';

    ob_start(); ?>
    <div class="wp-block-create-block-pr-carte">
        <div class="pr-carte-container">
            <?php foreach ($cards as$card) :
                $title = $card['titleField'] ?? '';
                $subtitle = $card['subtitleField'] ?? '';
                $text = $card['textField'] ?? '';
                $image_url = $card['imageUrl'] ?? '';
                $show_image = $card['showImage'] ?? false;
                $image_alt = $card['imageAlt'] ?? $title;
            ?>
                <div
                    role="button"
                    class="pr-carte"
                    data-card-flip
                    aria-label="<?php echo esc_attr("Apprendre plus sur {$title}, {$subtitle}"); ?>"
                    aria-live="assertive"
                >
                    <div class="pr-carte-contenu">
                        <div class="pr-carte-front">
                            <div>
                                <?php if ($show_image) : ?>
                                    <div class="pr-carte-bloc-image">
                                        <img
                                            class="pr-carte-image"
											src="<?php echo esc_url($image_url ?: 'https://placecats.com/520/300'); ?>"
											alt="<?php echo esc_attr($image_alt ?: "Image"); ?>"
                                        />
                                    </div>
                                <?php endif; ?>
                                <h3 class="pr-carte-titre"><?php echo esc_html($title); ?></h3>
                                <h4 class="pr-carte-soustitre"><?php echo esc_html($subtitle); ?></h4>
                            </div>
                            <div class="pr-carte-icone"><?php echo $flip_icon; ?></div>
                        </div>
                        <div class="pr-carte-back">
                            <p><?php echo esc_html($text); ?></p>
                            <div class="pr-carte-icone"><?php echo $flip_icon; ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

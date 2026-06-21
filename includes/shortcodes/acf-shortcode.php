<?php
/**
 * Shortcode pour afficher les champs ACF
 */

if (!defined('ABSPATH')) exit;

add_shortcode('my_acf', 'pr_custom_acf_shortcode');

function pr_custom_acf_shortcode($atts) {
    $atts = shortcode_atts(array(
        'field' => '',
        'post_id' => get_the_ID(),
    ), $atts);
    
    $post_id = $atts['post_id'] ? $atts['post_id'] : get_the_ID();
    $value = get_field($atts['field'], $post_id);
    
    // Gestion du PDF
    if($atts['field'] == 'article_pdf' && !empty($value)) {
        $pdf_url = is_array($value) ? $value['url'] : $value;
        return '<object data="' . esc_url($pdf_url) . '" type="application/pdf" width="100%" height="800px">
            <p>Votre navigateur ne peut pas afficher ce PDF. 
            <a href="' . esc_url($pdf_url) . '">Téléchargez-le ici</a></p>
        </object>';
    }
    
    // Gestion des images
    if(($atts['field'] == 'img-a-propos' || $atts['field'] == 'img-repondre-aux-defis') && !empty($value)) {
        $img_url = is_array($value) ? $value['url'] : $value;
        $img_alt = is_array($value) ? $value['alt'] : '';
        return '<div class="wp-block-image"><img src="' . esc_url($img_url) . '" alt="' . esc_attr($img_alt) . '"></div>';
    }
    
    return $value ? $value : '';
}
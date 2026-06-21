<?php
/**
 * Boutons de partage social
 */

if (!defined('ABSPATH')) exit;

add_shortcode('floating_share', 'pr_floating_share_button_shortcode');

function pr_floating_share_button_shortcode() {
    global $post;
    
    $url = urlencode(get_permalink());
    $title = urlencode(get_the_title());
    
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    }
    
    // URLs de partage
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $x_url = 'https://x.com/' . $url . '&text=' . $title;
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url;
    $whatsapp_url = 'https://wa.me/?text=' . $title . ' ' . $url;
    $messenger_url = 'https://www.facebook.com/dialog/send?link=' . $url . '&app_id=YOUR_FACEBOOK_APP_ID&redirect_uri=' . $url;
    $email_url = 'mailto:?subject=' . $title . '&body=Découvrez cet article: ' . $url;
    
    $output = '<div class="floating-share-button">';
    $output .= '<div class="share-buttons">';
    
    // Facebook
    $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="share-button facebook">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    $output .= '</a>';
    
    // X
    $output .= '<a href="' . esc_url($x_url) . '" target="_blank" rel="noopener noreferrer" class="share-button x">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M18.42,14.009L27.891,3h-2.244l-8.224,9.559L10.855,3H3.28l9.932,14.455L3.28,29h2.244l8.684-10.095,6.936,10.095h7.576l-10.301-14.991h0Zm-3.074,3.573l-1.006-1.439L6.333,4.69h3.447l6.462,9.243,1.006,1.439,8.4,12.015h-3.447l-6.854-9.804h0Z"></path></svg>';
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
    
    $output .= '</div>';
    
    $output .= '<div class="share-toggle">';
    $output .= '<svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 362.621 362.621" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path style="fill:#ffffff;" d="M288.753,121.491c33.495,0,60.746-27.251,60.746-60.746S322.248,0,288.753,0 s-60.745,27.25-60.745,60.746c0,6.307,0.968,12.393,2.76,18.117l-126.099,76.937c-9.707-8.322-22.301-13.366-36.059-13.366 c-30.596,0-55.487,24.891-55.487,55.487s24.892,55.487,55.487,55.487c10.889,0,21.047-3.165,29.626-8.606l101.722,58.194 c-0.584,3.058-0.902,6.209-0.902,9.435c0,27.676,22.516,50.192,50.191,50.192s50.191-22.516,50.191-50.192 s-22.516-50.191-50.191-50.191c-13.637,0-26.014,5.474-35.069,14.331l-95.542-54.658c3.498-7.265,5.46-15.403,5.46-23.991 c0-5.99-0.966-11.757-2.73-17.166l125.184-76.379C257.488,114.959,272.368,121.491,288.753,121.491z"></path></g></svg>';
    $output .= '</div>';
    
    $output .= '</div>';

    return $output;
}
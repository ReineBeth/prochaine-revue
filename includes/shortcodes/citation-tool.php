<?php
/**
 * Outil de citation
 */

if (!defined('ABSPATH')) exit;

add_shortcode('citation_tool', 'pr_citation_tool_shortcode');

function pr_citation_tool_shortcode() {
    global $post;
    
    // Récupérer les informations de l'article
    $author = get_field('auteurs_article', $post->ID) ? get_field('auteurs_article', $post->ID) : get_the_author();
    $title = get_the_title();
    $revue = get_field('titre_revue', $post->ID) ? get_field('titre_revue', $post->ID) : '';
    $volume = get_field('volume', $post->ID) ? get_field('volume', $post->ID) : '';
    $numero_volume = get_field('numero_volume', $post->ID) ? get_field('numero_volume', $post->ID) : '';
    $year = get_field('annee_publication', $post->ID) ? get_field('annee_publication', $post->ID) : '';
    $pages = get_field('pages', $post->ID) ? get_field('pages', $post->ID) : '';
    $url = get_permalink();

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
    
    // HTML
    $output = '<div class="citation-tool-container">';
    $output .= '<button id="citation-button" class="citation-button">';
    $output .= '<svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g><g id="right_x5F_quote"><g><path style="fill:#ffffff;" d="M0,4v12h8c0,4.41-3.586,8-8,8v4c6.617,0,12-5.383,12-12V4H0z"></path><path style="fill:#ffffff;" d="M20,4v12h8c0,4.41-3.586,8-8,8v4c6.617,0,12-5.383,12-12V4H20z"></path></g></g></g></g></svg>';
    $output .= '</button>';
    
    $output .= '<div id="citation-modal" class="citation-modal">';
    $output .= '<div class="citation-modal-content">';
    $output .= '<div class="citation-modal-header">';
    $output .= '<h2>Outils de citation</h2>';
    $output .= '<span class="citation-close"><svg fill="#ffffff" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55 c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55 c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505 c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55 l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719 c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path></g></svg></span>';
    $output .= '</div>';
    
    $output .= '<div class="citation-modal-body">';
    $output .= '<h3>Citer cet article</h3>';
    
    $output .= '<div class="citation-format">';
    $output .= '<h4>MLA</h4>';
    $output .= '<p class="citation-text">' . $mla_citation . '</p>';
    $output .= '</div>';
    
    $output .= '<div class="citation-format">';
    $output .= '<h4>APA</h4>';
    $output .= '<p class="citation-text">' . $apa_citation . '</p>';
    $output .= '</div>';
    
    $output .= '<div class="citation-format">';
    $output .= '<h4>Chicago</h4>';
    $output .= '<p class="citation-text">' . $chicago_citation . '</p>';
    $output .= '</div>';
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

// Script inline pour la modale
add_action('wp_footer', 'pr_add_citation_inline_script');
function pr_add_citation_inline_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const citationButton = document.getElementById('citation-button');
        const citationModal = document.getElementById('citation-modal');
        
        if (citationButton) {
            citationButton.addEventListener('click', function() {
                citationModal.style.display = 'block';
            });
        }
        
        const closeButton = document.querySelector('.citation-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                citationModal.style.display = 'none';
            });
        }
        
        window.addEventListener('click', function(event) {
            if (event.target === citationModal) {
                citationModal.style.display = 'none';
            }
        });
    });
    </script>
    <?php
}
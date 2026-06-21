<?php
/**
 * Script de recherche pour les auteurs
 */

if (!defined('ABSPATH')) exit;

add_action('wp_footer', 'pr_ajouter_script_recherche_auteurs');

function pr_ajouter_script_recherche_auteurs() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('recherche-auteurs');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchText = e.target.value.toLowerCase();
                const auteursContainers = document.querySelectorAll('.pr-accordeon-container');
                
                auteursContainers.forEach(container => {
                    const button = container.querySelector('.pr-accordeon-trigger');
                    if (button) {
                        const auteurText = button.textContent.toLowerCase();
                        if (auteurText.includes(searchText)) {
                            container.style.display = 'block';
                            container.style.visibility = 'visible';
                            container.style.margin = '';
                            container.style.height = '';
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
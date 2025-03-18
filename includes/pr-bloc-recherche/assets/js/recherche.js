document.addEventListener('DOMContentLoaded', function() {
    // Trouvons l'input par sa classe
    const searchInput = document.querySelector('.pr-recherche-input');


    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();

            // On cible les conteneurs principaux des accordéons
            const elementContainers = document.querySelectorAll('.pr-accordeon-container');

            elementContainers.forEach(container => {
                const button = container.querySelector('.pr-accordeon-trigger');
                if (button) {
                    const elementText = button.textContent.toLowerCase();

                    if (elementText.includes(searchText)) {
                        // Montrer l'élément
                        container.style.display = 'block';
                        container.style.visibility = 'visible';
                        container.style.margin = '';
                        container.style.height = '';
                        container.style.opacity = '1';
                    } else {
                        // Cacher l'élément
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

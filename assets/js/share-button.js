document.addEventListener('DOMContentLoaded', function() {
    const shareToggle = document.querySelector('.share-toggle');
    const floatingButton = document.querySelector('.floating-share-button');
    
    if (shareToggle) {
        shareToggle.addEventListener('click', function() {
            floatingButton.classList.toggle('active');
        });
        
        // Fermer le menu de partage quand on clique ailleurs sur la page
        document.addEventListener('click', function(event) {
            if (!floatingButton.contains(event.target)) {
                floatingButton.classList.remove('active');
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const citationButton = document.getElementById('citation-button');
    const citationModal = document.getElementById('citation-modal');
    const closeButton = document.querySelector('.citation-close');
    const exportButtons = document.querySelectorAll('.citation-export-button');
    const formatDescs = document.querySelectorAll('.format-desc');
    const citationContainer = document.querySelector('.citation-tool-container');
    
    // Afficher la modale lorsque le bouton est cliqué
    if (citationButton) {
        citationButton.addEventListener('click', function() {
            citationModal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Empêcher le défilement du corps
        });
    }
    
    // Fermer la modale lorsque le bouton de fermeture est cliqué
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            citationModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Réactiver le défilement du corps
        });
    }
    
    // Fermer la modale lorsque l'utilisateur clique en dehors du contenu
    window.addEventListener('click', function(event) {
        if (event.target === citationModal) {
            citationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Afficher la description du format lorsque l'utilisateur survole un bouton d'export
    exportButtons.forEach(function(button) {
        button.addEventListener('mouseenter', function() {
            const format = this.getAttribute('data-format');
            
            // Masquer toutes les descriptions
            formatDescs.forEach(function(desc) {
                desc.classList.remove('active');
            });
            
            // Afficher la description correspondante
            const activeDesc = document.querySelector('.format-desc[data-format="' + format + '"]');
            if (activeDesc) {
                activeDesc.classList.add('active');
            }
        });
        
        // Gérer le clic sur les boutons d'export
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const format = this.getAttribute('data-format');
            exportCitation(format);
        });
    });
    
    // Fonction pour exporter la citation
    function exportCitation(format) {
        // Récupérer les métadonnées de l'article à partir des attributs de données
        const author = citationContainer.getAttribute('data-author');
        const title = citationContainer.getAttribute('data-title');
        const journal = citationContainer.getAttribute('data-journal');
        const volume = citationContainer.getAttribute('data-volume');
        const issue = citationContainer.getAttribute('data-issue');
        const year = citationContainer.getAttribute('data-year');
        const pages = citationContainer.getAttribute('data-pages');
        const url = citationContainer.getAttribute('data-url');
        
        let exportContent = '';
        
        // Générer le contenu selon le format
        if (format === 'ris') {
            exportContent = 'TY  - JOUR\n';
            exportContent += 'AU  - ' + author + '\n';
            exportContent += 'TI  - ' + title + '\n';
            exportContent += 'JO  - ' + journal + '\n';
            if (volume) exportContent += 'VL  - ' + volume + '\n';
            if (issue) exportContent += 'IS  - ' + issue + '\n';
            exportContent += 'PY  - ' + year + '\n';
            if (pages) exportContent += 'SP  - ' + pages + '\n';
            exportContent += 'UR  - ' + url + '\n';
            exportContent += 'ER  - \n';
        } else if (format === 'enw') {
            exportContent = '%0 Journal Article\n';
            exportContent += '%A ' + author + '\n';
            exportContent += '%T ' + title + '\n';
            exportContent += '%J ' + journal + '\n';
            if (volume) exportContent += '%V ' + volume + '\n';
            if (issue) exportContent += '%N ' + issue + '\n';
            exportContent += '%D ' + year + '\n';
            if (pages) exportContent += '%P ' + pages + '\n';
            exportContent += '%U ' + url + '\n';
        } else if (format === 'bib') {
            // Générer une clé pour la citation BibTeX
            const authorKey = author.split(' ')[0].toLowerCase();
            
            exportContent = '@article{' + authorKey + year + ',\n';
            exportContent += '  author = {' + author + '},\n';
            exportContent += '  title = {' + title + '},\n';
            exportContent += '  journal = {' + journal + '},\n';
            if (volume) exportContent += '  volume = {' + volume + '},\n';
            if (issue) exportContent += '  number = {' + issue + '},\n';
            exportContent += '  year = {' + year + '},\n';
            if (pages) exportContent += '  pages = {' + pages + '},\n';
            exportContent += '  url = {' + url + '}\n';
            exportContent += '}';
        }
        
        // Créer un blob avec le contenu
        const blob = new Blob([exportContent], { type: 'text/plain' });
        
        // Créer un lien de téléchargement
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'citation.' + format;
        
        // Simuler un clic sur le lien pour déclencher le téléchargement
        document.body.appendChild(a);
        a.click();
        
        // Nettoyer
        document.body.removeChild(a);
        URL.revokeObjectURL(a.href);
    }
});
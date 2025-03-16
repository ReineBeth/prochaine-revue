document.addEventListener('DOMContentLoaded', function() {
    const accordeons = document.querySelectorAll('.pr-accordeon-trigger');

    accordeons.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const isOpen = this.getAttribute('aria-expanded') === 'true';

            // Toggle l'état
            this.setAttribute('aria-expanded', !isOpen);
            this.classList.toggle('is-open');

            // Toggle le contenu
            const content = document.getElementById(this.getAttribute('aria-controls'));
            content.classList.toggle('is-open');
            content.hidden = isOpen;
        });
    });
});

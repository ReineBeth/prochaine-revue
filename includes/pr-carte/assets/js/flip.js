document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.pr-carte');

    cards.forEach(card => {
        let isFlipped = false;
        const content = card.querySelector('.pr-carte-contenu');
        const front = card.querySelector('.pr-carte-front');
        const back = card.querySelector('.pr-carte-back');

        // État initial
        front.style.opacity = '1';
        front.style.visibility = 'visible';
        back.style.opacity = '0';
        back.style.visibility = 'hidden';

        card.addEventListener('click', function() {
            isFlipped = !isFlipped;

            // Faire apparaître le bon côté avec transition
            if (isFlipped) {
                // Face avant disparaît
                front.style.opacity = '0';
                front.style.visibility = 'hidden';

                // Face arrière apparaît
                back.style.opacity = '1';
                back.style.visibility = 'visible';
            } else {
                // Face arrière disparaît
                back.style.opacity = '0';
                back.style.visibility = 'hidden';

                // Face avant apparaît
                front.style.opacity = '1';
                front.style.visibility = 'visible';
            }

            // Style pour la carte
            this.style.backgroundColor = isFlipped ? '#3A5A40' : '#ffffff';
            this.style.borderColor = isFlipped ? '#92d16e' : '#478245';
            card.style.transform = isFlipped ? 'rotateY(180deg)' : 'rotateY(0deg)';
            content.style.transform = isFlipped ? 'rotateY(180deg)' : 'rotateY(0deg)';

            // Mise à jour de l'aria-label
            const title = card.querySelector('.pr-carte-titre').textContent;
            const subtitle = card.querySelector('.pr-carte-soustitre').textContent;
            const text = card.querySelector('.pr-carte-back p').textContent;

            this.setAttribute('aria-label',
                isFlipped ? text : `Apprendre plus sur ${title}, ${subtitle}`
            );
        });
    });
});

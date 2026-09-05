(function () {
    const triggers = document.querySelectorAll('[data-pr-actualite-trigger]');
    const dialog = document.querySelector('[data-pr-actualite-dialog]');

    if (!triggers.length || !dialog || typeof dialog.showModal !== 'function') {
        return;
    }

    const content = dialog.querySelector('[data-pr-actualite-dialog-content]');
    const dialogTitleId = dialog.getAttribute('aria-labelledby');
    const closeButton = dialog.querySelector('[data-pr-actualite-close]');
    let activeTrigger = null;
    let previousBodyOverflow = '';

    function closeDialog() {
        if (dialog.open) {
            dialog.close();
        }
    }

    function openDialog(trigger) {
        const templateId = trigger.getAttribute('data-dialog-template');
        const template = templateId ? document.getElementById(templateId) : null;

        if (!template || !content) {
            return;
        }

        activeTrigger = trigger;
        content.replaceChildren(template.content.cloneNode(true));

        const selectedTitle = content.querySelector('.pr-actualite-dialog__title');
        if (dialogTitleId && selectedTitle) {
            selectedTitle.id = dialogTitleId;
        }

        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialog.showModal();

        if (closeButton) {
            closeButton.focus();
        }
    }

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            openDialog(trigger);
        });
    });

    if (closeButton) {
        closeButton.addEventListener('click', closeDialog);
    }

    dialog.addEventListener('click', function (event) {
        if (event.target === dialog) {
            closeDialog();
        }
    });

    dialog.addEventListener('close', function () {
        document.body.style.overflow = previousBodyOverflow;

        if (activeTrigger) {
            activeTrigger.focus();
        }

        activeTrigger = null;
    });
}());

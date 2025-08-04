/******/ (() => { // webpackBootstrap
/*!*********************!*\
  !*** ./src/view.js ***!
  \*********************/
/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

document.addEventListener("DOMContentLoaded", function () {
  // Sélectionner tous les triggers d'accordéon
  const triggers = document.querySelectorAll(".pr-accordeon-trigger.js-trigger");
  triggers.forEach(trigger => {
    trigger.addEventListener("click", function () {
      // Récupérer l'ID du contenu associé
      const contentId = this.getAttribute("aria-controls");
      const content = document.getElementById(contentId);
      if (content) {
        // Vérifier l'état actuel
        const isExpanded = this.getAttribute("aria-expanded") === "true";

        // Toggle l'état
        this.setAttribute("aria-expanded", !isExpanded);
        content.hidden = isExpanded;

        // Toggle les classes CSS si nécessaire
        this.classList.toggle("is-open", !isExpanded);
        content.classList.toggle("is-open", !isExpanded);
      }
    });
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map
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

const triggerAccordeon = document.querySelectorAll(".js-trigger");
console.log("trigger", triggerAccordeon);
triggerAccordeon.forEach(element => {
  element.addEventListener("click", function () {
    const ariaControls = element.getAttribute("aria-controls");
    const content = document.getElementById(ariaControls);
    content.toggleAttribute("hidden");
    element.classList.toggle("open");
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map
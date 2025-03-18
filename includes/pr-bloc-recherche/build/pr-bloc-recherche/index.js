/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/pr-bloc-recherche/edit.js":
/*!***************************************!*\
  !*** ./src/pr-bloc-recherche/edit.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);
// /**
//  * Retrieves the translation of text.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
//  */
// import { __ } from '@wordpress/i18n';

// /**
//  * React hook that is used to mark the block wrapper element.
//  * It provides all the necessary props like the class name.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
//  */
// import { useBlockProps } from '@wordpress/block-editor';

// /**
//  * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
//  * Those files can contain any CSS code that gets applied to the editor.
//  *
//  * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
//  */
// import './editor.scss';

// /**
//  * The edit function describes the structure of your block in the context of the
//  * editor. This represents what the editor will render when the block is used.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
//  *
//  * @return {Element} Element to render.
//  */
// export default function Edit() {
// 	return (
// 		<p { ...useBlockProps() }>
// 			{ __(
// 				'Pr Bloc Recherche – hello from the editor!',
// 				'pr-bloc-recherche'
// 			) }
// 		</p>
// 	);
// }

// pr-bloc-recherche/editor.js




// Composant d'édition du bloc

const PRBlocRechercheEdit = props => {
  const {
    attributes,
    setAttributes
  } = props;
  const {
    target,
    searchIn,
    placeholder,
    isLive,
    customClass,
    context
  } = attributes;

  // Options prédéfinies pour les contextes communs
  const handleContextChange = newContext => {
    setAttributes({
      context: newContext
    });

    // Appliquer les paramètres prédéfinis selon le contexte
    switch (newContext) {
      case 'auteurs':
        setAttributes({
          target: '.pr-accordeon-container',
          searchIn: '.pr-accordeon-trigger',
          placeholder: 'Rechercher un auteur'
        });
        break;
      case 'articles':
        setAttributes({
          target: '.wp-block-post',
          searchIn: '.wp-block-post-title',
          placeholder: 'Rechercher un article'
        });
        break;
      case 'custom':
        // Ne rien faire, garder les valeurs personnalisées
        break;
    }
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Paramètres de recherche', 'mon-theme'),
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Contexte prédéfini', 'mon-theme'),
          value: context,
          options: [{
            label: 'Personnalisé',
            value: 'custom'
          }, {
            label: 'Page Auteurs',
            value: 'auteurs'
          }, {
            label: 'Page Articles',
            value: 'articles'
          }],
          onChange: handleContextChange
        }), context === 'custom' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Éléments à filtrer (sélecteur CSS)', 'mon-theme'),
            value: target,
            onChange: target => setAttributes({
              target
            }),
            help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Ex: .pr-accordeon-container pour les auteurs', 'mon-theme')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Élément où chercher le texte (sélecteur CSS)', 'mon-theme'),
            value: searchIn,
            onChange: searchIn => setAttributes({
              searchIn
            }),
            help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Ex: .pr-accordeon-trigger pour les auteurs. Laisser vide pour chercher dans tout l\'élément', 'mon-theme')
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Texte du placeholder', 'mon-theme'),
          value: placeholder,
          onChange: placeholder => setAttributes({
            placeholder
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Mode de filtrage', 'mon-theme'),
          value: isLive ? 'true' : 'false',
          options: [{
            label: 'En temps réel',
            value: 'true'
          }, {
            label: 'Au clic sur un bouton',
            value: 'false'
          }],
          onChange: value => setAttributes({
            isLive: value === 'true'
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Classes CSS additionnelles', 'mon-theme'),
          value: customClass,
          onChange: customClass => setAttributes({
            customClass
          })
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
      className: `wp-block-mon-theme-pr-bloc-recherche ${customClass}`,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "pr-recherche-preview",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
          placeholder: placeholder,
          onChange: () => {},
          className: "pr-recherche-input"
        }), !isLive && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("button", {
          className: "pr-recherche-btn",
          disabled: true,
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Filtrer', 'mon-theme')
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("p", {
        className: "pr-recherche-help",
        children: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Ce bloc ajoutera un champ de recherche pour filtrer: ', 'mon-theme'), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("code", {
          children: target
        })]
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PRBlocRechercheEdit);

/***/ }),

/***/ "./src/pr-bloc-recherche/editor.scss":
/*!*******************************************!*\
  !*** ./src/pr-bloc-recherche/editor.scss ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/pr-bloc-recherche/index.js":
/*!****************************************!*\
  !*** ./src/pr-bloc-recherche/index.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/pr-bloc-recherche/edit.js");
/* harmony import */ var _save__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./save */ "./src/pr-bloc-recherche/save.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./src/pr-bloc-recherche/editor.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./style.scss */ "./src/pr-bloc-recherche/style.scss");
// /**
//  * Registers a new block provided a unique name and an object defining its behavior.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
//  */
// import { registerBlockType } from '@wordpress/blocks';

// /**
//  * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
//  * All files containing `style` keyword are bundled together. The code used
//  * gets applied both to the front of your site and to the editor.
//  *
//  * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
//  */
// import './style.scss';

// /**
//  * Internal dependencies
//  */
// import Edit from './edit';
// import save from './save';
// import metadata from './block.json';

// /**
//  * Every block starts by registering a new block type definition.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
//  */
// registerBlockType( metadata.name, {
// 	/**
// 	 * @see ./edit.js
// 	 */
// 	edit: Edit,

// 	/**
// 	 * @see ./save.js
// 	 */
// 	save,
// } );

// pr-bloc-recherche/index.js







// Enregistrement du bloc
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('mon-theme/pr-bloc-recherche', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('PR Bloc Recherche', 'mon-theme'),
  icon: 'search',
  category: 'widgets',
  attributes: {
    target: {
      type: 'string',
      default: '.item'
    },
    searchIn: {
      type: 'string',
      default: ''
    },
    placeholder: {
      type: 'string',
      default: 'Rechercher'
    },
    isLive: {
      type: 'boolean',
      default: true
    },
    customClass: {
      type: 'string',
      default: ''
    },
    context: {
      type: 'string',
      default: 'custom'
    }
  },
  // Référence aux composants d'édition et de sauvegarde
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"],
  save: _save__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ }),

/***/ "./src/pr-bloc-recherche/save.js":
/*!***************************************!*\
  !*** ./src/pr-bloc-recherche/save.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);
// /**
//  * React hook that is used to mark the block wrapper element.
//  * It provides all the necessary props like the class name.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
//  */
// import { useBlockProps } from '@wordpress/block-editor';

// /**
//  * The save function defines the way in which the different attributes should
//  * be combined into the final markup, which is then serialized by the block
//  * editor into `post_content`.
//  *
//  * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
//  *
//  * @return {Element} Element to render.
//  */
// export default function save() {
// 	return (
// 		<p { ...useBlockProps.save() }>
// 			{ 'Pr Bloc Recherche – hello from the saved content!' }
// 		</p>
// 	);
// }

// pr-bloc-recherche/save.js


// Composant de sauvegarde du bloc

const PRBlocRechercheSave = props => {
  const {
    attributes
  } = props;
  const {
    target,
    searchIn,
    placeholder,
    isLive,
    customClass
  } = attributes;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("div", {
    className: `pr-bloc-recherche-container ${customClass}`,
    "data-target": target,
    "data-search-in": searchIn,
    "data-live": isLive,
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("input", {
      type: "text",
      className: "pr-recherche-input",
      placeholder: placeholder
    }), !isLive && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("button", {
      type: "button",
      className: "pr-recherche-btn",
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Filtrer', 'mon-theme')
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PRBlocRechercheSave);

/***/ }),

/***/ "./src/pr-bloc-recherche/style.scss":
/*!******************************************!*\
  !*** ./src/pr-bloc-recherche/style.scss ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"pr-bloc-recherche/index": 0,
/******/ 			"pr-bloc-recherche/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkpr_bloc_recherche"] = globalThis["webpackChunkpr_bloc_recherche"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["pr-bloc-recherche/style-index"], () => (__webpack_require__("./src/pr-bloc-recherche/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map
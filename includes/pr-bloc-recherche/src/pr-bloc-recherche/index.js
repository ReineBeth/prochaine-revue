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
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import edit from './edit';
import save from './save';

import './editor.scss';
import './style.scss';

// Enregistrement du bloc
registerBlockType('mon-theme/pr-bloc-recherche', {
    title: __('PR Bloc Recherche', 'mon-theme'),
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
    edit,
    save
});

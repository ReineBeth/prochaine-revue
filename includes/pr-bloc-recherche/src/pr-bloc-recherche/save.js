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
import { __ } from '@wordpress/i18n';

// Composant de sauvegarde du bloc
const PRBlocRechercheSave = (props) => {
    const { attributes } = props;
    const { target, searchIn, placeholder, isLive, customClass } = attributes;

    return (
        <div
            className={`pr-bloc-recherche-container ${customClass}`}
            data-target={target}
            data-search-in={searchIn}
            data-live={isLive}
        >
            <input
                type="text"
                className="pr-recherche-input"
                placeholder={placeholder}
            />

            {!isLive && (
                <button type="button" className="pr-recherche-btn">
                    {__('Filtrer', 'mon-theme')}
                </button>
            )}
        </div>
    );
};

export default PRBlocRechercheSave;

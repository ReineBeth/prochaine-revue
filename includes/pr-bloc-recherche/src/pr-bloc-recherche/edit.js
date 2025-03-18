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
import { __ } from '@wordpress/i18n';
import {
    TextControl,
    PanelBody,
    SelectControl
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

// Composant d'édition du bloc
const PRBlocRechercheEdit = (props) => {
    const { attributes, setAttributes } = props;
    const { target, searchIn, placeholder, isLive, customClass, context } = attributes;

    // Options prédéfinies pour les contextes communs
    const handleContextChange = (newContext) => {
        setAttributes({ context: newContext });

        // Appliquer les paramètres prédéfinis selon le contexte
        switch(newContext) {
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

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Paramètres de recherche', 'mon-theme')}>
                    <SelectControl
                        label={__('Contexte prédéfini', 'mon-theme')}
                        value={context}
                        options={[
                            { label: 'Personnalisé', value: 'custom' },
                            { label: 'Page Auteurs', value: 'auteurs' },
                            { label: 'Page Articles', value: 'articles' }
                        ]}
                        onChange={handleContextChange}
                    />

                    {context === 'custom' && (
                        <>
                            <TextControl
                                label={__('Éléments à filtrer (sélecteur CSS)', 'mon-theme')}
                                value={target}
                                onChange={(target) => setAttributes({ target })}
                                help={__('Ex: .pr-accordeon-container pour les auteurs', 'mon-theme')}
                            />

                            <TextControl
                                label={__('Élément où chercher le texte (sélecteur CSS)', 'mon-theme')}
                                value={searchIn}
                                onChange={(searchIn) => setAttributes({ searchIn })}
                                help={__('Ex: .pr-accordeon-trigger pour les auteurs. Laisser vide pour chercher dans tout l\'élément', 'mon-theme')}
                            />
                        </>
                    )}

                    <TextControl
                        label={__('Texte du placeholder', 'mon-theme')}
                        value={placeholder}
                        onChange={(placeholder) => setAttributes({ placeholder })}
                    />

                    <SelectControl
                        label={__('Mode de filtrage', 'mon-theme')}
                        value={isLive ? 'true' : 'false'}
                        options={[
                            { label: 'En temps réel', value: 'true' },
                            { label: 'Au clic sur un bouton', value: 'false' }
                        ]}
                        onChange={(value) => setAttributes({ isLive: value === 'true' })}
                    />

                    <TextControl
                        label={__('Classes CSS additionnelles', 'mon-theme')}
                        value={customClass}
                        onChange={(customClass) => setAttributes({ customClass })}
                    />
                </PanelBody>
            </InspectorControls>

            <div className={`wp-block-mon-theme-pr-bloc-recherche ${customClass}`}>
                <div className="pr-recherche-preview">
                    <TextControl
                        placeholder={placeholder}
                        onChange={() => {}}
                        className="pr-recherche-input"
                    />
                    {!isLive && (
                        <button className="pr-recherche-btn" disabled>
                            {__('Filtrer', 'mon-theme')}
                        </button>
                    )}
                </div>
                <p className="pr-recherche-help">
                    {__('Ce bloc ajoutera un champ de recherche pour filtrer: ', 'mon-theme')}
                    <code>{target}</code>
                </p>
            </div>
        </>
    );
};

export default PRBlocRechercheEdit;

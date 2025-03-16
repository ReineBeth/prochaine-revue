/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the className name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, SETTINGS_DEFAULTS, useBlockProps } from '@wordpress/block-editor';

/**
 * Imports the necessary components that will be used to create
 * the user interface for the block's settings.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/panel/#panelbody
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/text-control/
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/toggle-control/
 */
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 * @return {Element} Element to render.
 */

import { MediaUpload } from "@wordpress/block-editor";
import { Button } from "@wordpress/components";
import { useMemo } from "react";

export default function Edit( { attributes, setAttributes}) {
	const { cards = [] } = attributes; //Nécessaire même si card à une valeur de array vide par défaut dans block.json, bug ?

	// Défini les styles une seule fois avec useMemo au lieu de les recaculer à chaque rendu
    const styles = useMemo(() => ({
        carte: {
            base: {
                transition: 'transform 1s, background-color 0.75s',
            },
            flipped: {
                transform: 'rotateY(-180deg)',
                backgroundColor: '#3A5A40',
                borderColor: '#92d16e'
            },
            notFlipped: {
                transform: 'rotateY(0deg)',
                backgroundColor: '#ffffff',
                borderColor: '#478245'
            }
        },
        face: {
            base: {
                transition: 'opacity 0.3s ease, visibility 0.3s ease'
            },
            visible: {
                opacity: '1',
                visibility: 'visible'
            },
            hidden: {
                opacity: '0',
                visibility: 'hidden'
            }
        }
    }), []); // Tableau vide car les styles sont constants

    function toggleCardFlip(index) {
        // Créer une nouvelle copie seulement de la carte modifiée
        const newCards = [...cards];
        newCards[index] = {
            ...newCards[index],
            isFlipped: !newCards[index].isFlipped
        };
        setAttributes({ cards: newCards });
    }

	    // Rendu des cartes séparé
		const renderCard = (card, index) => {

			const cardStyle = {
				...styles.carte.base,
				...(card.isFlipped ? styles.carte.flipped : styles.carte.notFlipped)
			};

			const frontStyle = {
				...styles.face.base,
				...(card.isFlipped ? styles.face.hidden : styles.face.visible)
			};

			const backStyle = {
				...styles.face.base,
				...(card.isFlipped ? styles.face.visible : styles.face.hidden)
			};

			return (
				<div
					key={index}
					role="button"
					className="pr-carte"
					style={cardStyle}
					aria-label={card.isFlipped ? `${card.textField}` : `Apprendre plus sur ${card.titleField}, ${card.subtitleField}`}
					aria-live='assertive'
					onClick={() => toggleCardFlip(index)}
				>
					<div className="pr-carte-contenu" style={cardStyle}>
						<div className="pr-carte-front" style={frontStyle}>
						<div>
								{card.showImage && (
									<div className="pr-carte-bloc-image">
										<img
												className="pr-carte-image"
												src={card.imageUrl || "https://placecats.com/520/300"}
												alt={card.titleField || `Image ${index + 1}`}
										/>
										</div>
									)}
								<h3 className="pr-carte-titre">{card.titleField}</h3>
								<h4 className="pr-carte-soustitre">{card.subtitleField}</h4>
							</div>
							<div className="pr-carte-icone">
								{flipIcon}
							</div>
						</div>
						<div className="pr-carte-back" style={backStyle}>
							<p>{card.textField}</p>
							<div className="pr-carte-icone">
								{flipIcon}
							</div>
						</div>
					</div>
				</div>
			);
		};


	const flipIcon = (
        <svg width="48" height="48" clipRule="evenodd" fillRule="evenodd" strokeLinejoin="round" strokeMiterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="m21.897 13.404.008-.057v.002c.024-.178.044-.357.058-.537.024-.302-.189-.811-.749-.811-.391 0-.715.3-.747.69-.018.221-.044.44-.078.656-.645 4.051-4.158 7.153-8.391 7.153-3.037 0-5.704-1.597-7.206-3.995l1.991-.005c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-4.033c-.414 0-.75.336-.75.75v4.049c0 .414.336.75.75.75s.75-.335.75-.75l.003-2.525c1.765 2.836 4.911 4.726 8.495 4.726 5.042 0 9.217-3.741 9.899-8.596zm-19.774-2.974-.009.056v-.002c-.035.233-.063.469-.082.708-.024.302.189.811.749.811.391 0 .715-.3.747-.69.022-.28.058-.556.107-.827.716-3.968 4.189-6.982 8.362-6.982 3.037 0 5.704 1.597 7.206 3.995l-1.991.005c-.414 0-.75.336-.75.75s.336.75.75.75h4.033c.414 0 .75-.336.75-.75v-4.049c0-.414-.336-.75-.75-.75s-.75.335-.75.75l-.003 2.525c-1.765-2.836-4.911-4.726-8.495-4.726-4.984 0-9.12 3.654-9.874 8.426z" fillRule="nonzero"/>
        </svg>
    );

	//Ajouter une carte
	function addCard() {
		const existingCards = cards || [];

		setAttributes({
			cards: [
				...existingCards,
				{ titleField: "", subtitleField: "", textField: "", imageUrl: "", isFlipped: false },
			],
		});
	}

	// Mettre à jour une carte
	function updateCard(index, field, value) {
		const newCards = [...cards];
		newCards[index][field] = value;
		setAttributes({ cards: newCards });
	}

	// Supprimer une carte
	function removeCard(index) {
		const newCards = [...cards];
		newCards.splice(index, 1);
		setAttributes({ cards: newCards });
	}

	return (
        <>
            <InspectorControls>
                <PanelBody title={__("Settings", "block-development-examples")}>
				{(cards).map((card, index) => (
					<div key={index} style={{ marginBottom: "20px" }}>
                        <TextControl
                            label={`Titre ${index + 1}`}
                            help="Phrase d'un maximum de 25 caractères"
                            maxLength={25}
                            value={card.titleField}
                            onChange={(value) => updateCard(index, 'titleField', value)}
                        />
                        <TextControl
                            label={`Sous titre ${index + 1}`}
                            help="Phrase d'un maximum de 25 caractères"
                            maxLength={25}
                            value={card.subtitleField}
                            onChange={(value) => updateCard(index, 'subtitleField', value)}
                        />
                        <TextControl
                            label={`Texte ${index + 1}`}
                            value={card.textField}
                            onChange={(value) => updateCard(index, 'textField', value)}
                            help="Texte d'un maximum de 450 caractères"
                            maxLength={450}
                        />
						<ToggleControl
								label={__("Afficher l'image", "block-development-examples")}
								checked={card.showImage}
								onChange={(value) => updateCard(index, "showImage", value)}
							/>

							{card.showImage && (
								<>
									<MediaUpload
										onSelect={(media) => {
											updateCard(index, "imageUrl", media.url);
											updateCard(index, "imageAlt", media.alt);
										}}
										allowedTypes={["image"]}
										value={card.imageUrl}
										render={({ open }) => (
											<Button
												onClick={open}
												variant="secondary"
												style={{
													display: "block",
													marginBottom: "10px",
												}}
											>
												{card.imageUrl
													? "Changer l'image"
													: "Choisir une image"}
											</Button>
										)}
									/>
									{card.imageUrl && (
										<img
											src={card.imageUrl}
											alt={card.imageAlt || `Image ${index + 1}`}
											style={{ maxWidth: "100%", height: "auto" }}
										/>
									)}
								</>
							)}
							<button
								style={{
									background: "red",
									color: "white",
									border: "none",
									padding: "5px 10px",
									cursor: "pointer",
								}}
								onClick={() => removeCard(index)}
							>
								Supprimer
							</button>
						</div>
					))}
					<button
						style={{
							background: "green",
							color: "white",
							border: "none",
							padding: "10px 15px",
							cursor: "pointer",
							marginTop: "20px",
						}}
						onClick={addCard}
					>
						<span>Ajouter une carte</span>
					</button>
                </PanelBody>
            </InspectorControls>
            <div className="pr-carte-container" {...useBlockProps()}>
                {cards.map(renderCard)}
            </div>
        </>
    );
}

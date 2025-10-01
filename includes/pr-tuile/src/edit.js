import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from "@wordpress/block-editor";
import {
	PanelBody,
	Button,
	TextControl,
	TextareaControl,
	ToggleControl,
	IconButton,
} from "@wordpress/components";
import { useState } from "@wordpress/element";

export default function Edit({ attributes, setAttributes }) {
	const { tiles = [] } = attributes;

	// Fonction pour ajouter une nouvelle tuile
	const addTile = () => {
		const newTiles = [
			...tiles,
			{
				titleField: "",
				auteurs: "",
				typeArticle: "",
				textField: "",
				linkUrl: "",
				showImage: false,
				imageUrl: "",
				imageAlt: "",
			},
		];
		setAttributes({ tiles: newTiles });
	};

	// Fonction pour supprimer une tuile
	const removeTile = (index) => {
		const newTiles = tiles.filter((_, i) => i !== index);
		setAttributes({ tiles: newTiles });
	};

	// Fonction pour mettre à jour une tuile spécifique
	const updateTile = (index, field, value) => {
		const newTiles = [...tiles];
		newTiles[index] = { ...newTiles[index], [field]: value };
		setAttributes({ tiles: newTiles });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Paramètres des tuiles", "prochaine-revue")}>
					<Button isPrimary onClick={addTile}>
						{__("Ajouter une tuile", "prochaine-revue")}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>
				<div className="pr-tuile-container">
					{tiles.length === 0 && (
						<div className="pr-tuile-placeholder">
							<p>{__("Aucune tuile ajoutée", "prochaine-revue")}</p>
							<Button isPrimary onClick={addTile}>
								{__("Ajouter votre première tuile", "prochaine-revue")}
							</Button>
						</div>
					)}

					{tiles.map((tile, index) => (
						<div key={index} className="pr-tuile-edit">
							<div className="pr-tuile-controls">
								<Button isDestructive isSmall onClick={() => removeTile(index)}>
									{__("Supprimer", "prochaine-revue")}
								</Button>
							</div>

							<div className="pr-tuile-lien">
								{/* Gestion de l'image */}
								<ToggleControl
									label={__("Afficher une image", "prochaine-revue")}
									checked={tile.showImage}
									onChange={(value) => updateTile(index, "showImage", value)}
								/>

								{tile.showImage && (
									<div className="pr-tuile-lien-image">
										<MediaUploadCheck>
											<MediaUpload
												onSelect={(media) => {
													updateTile(index, "imageUrl", media.url);
													updateTile(index, "imageAlt", media.alt);
												}}
												allowedTypes={["image"]}
												value={tile.imageUrl}
												render={({ open }) => (
													<div>
														{tile.imageUrl ? (
															<div>
																<img
																	src={tile.imageUrl}
																	alt={tile.imageAlt}
																	style={{ maxWidth: "100%", height: "auto" }}
																/>
																<Button onClick={open} isSecondary isSmall>
																	{__("Changer l'image", "prochaine-revue")}
																</Button>
															</div>
														) : (
															<Button onClick={open} isPrimary>
																{__(
																	"Sélectionner une image",
																	"prochaine-revue",
																)}
															</Button>
														)}
													</div>
												)}
											/>
										</MediaUploadCheck>
									</div>
								)}

								<div className="pr-tuile-lien-text">
									{/* Titre */}
									<TextControl
										label={__("Titre", "prochaine-revue")}
										value={tile.titleField}
										onChange={(value) => updateTile(index, "titleField", value)}
										placeholder={__("Entrez le titre...", "prochaine-revue")}
									/>

									{/* Auteurs */}
									<TextControl
										label={__("Auteurs", "prochaine-revue")}
										value={tile.auteurs}
										onChange={(value) => updateTile(index, "auteurs", value)}
										placeholder={__(
											"Nom Auteur, Prénom Auteur",
											"prochaine-revue",
										)}
									/>

									{/* Type d'article */}
									<TextControl
										label={__("Type d'article", "prochaine-revue")}
										value={tile.typeArticle}
										onChange={(value) =>
											updateTile(index, "typeArticle", value)
										}
										placeholder={__("Type d'article...", "prochaine-revue")}
									/>

									{/* Description (optionnelle) */}
									<TextareaControl
										label={__("Description (optionnel)", "prochaine-revue")}
										value={tile.textField}
										onChange={(value) => updateTile(index, "textField", value)}
										placeholder={__(
											"Description de l'article...",
											"prochaine-revue",
										)}
									/>

									{/* URL du lien */}
									<TextControl
										label={__("URL du lien", "prochaine-revue")}
										value={tile.linkUrl}
										onChange={(value) => updateTile(index, "linkUrl", value)}
										placeholder={__("https://...", "prochaine-revue")}
									/>
								</div>
							</div>
						</div>
					))}
				</div>
			</div>
		</>
	);
}

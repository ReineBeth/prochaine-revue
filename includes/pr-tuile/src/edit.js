import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	useBlockProps,
	MediaUpload,
} from "@wordpress/block-editor";
import {
	PanelBody,
	TextControl,
	ToggleControl,
	Button,
	SelectControl,
	RangeControl,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEffect } from "react";

export default function Edit({ attributes, setAttributes }) {
	const { tiles, mode, articlesCount, showAllArticles } = attributes;

	// Récupérer les articles si mode dynamique
	const articles = useSelect(
		(select) => {
			if (mode !== "dynamic") return null;

			return select("core").getEntityRecords("postType", "pr_article", {
				per_page: showAllArticles ? -1 : articlesCount,
				_embed: true,
				orderby: "date",
				order: "desc",
			});
		},
		[mode, articlesCount, showAllArticles],
	);

	console.log(articles);

	// Fonctions existantes pour les tuiles statiques
	function addTile() {
		setAttributes({
			tiles: [
				...tiles,
				{ titleField: "", textField: "", linkUrl: "", imageUrl: "" },
			],
		});
	}

	function removeTile(index) {
		const newTiles = [...tiles];
		newTiles.splice(index, 1);
		setAttributes({ tiles: newTiles });
	}

	function updateTile(index, field, value) {
		const newTiles = [...tiles];
		newTiles[index][field] = value;
		setAttributes({ tiles: newTiles });
	}

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Configuration générale", "pr-tuile")}>
					<SelectControl
						label={__("Mode d'affichage", "pr-tuile")}
						value={mode}
						options={[
							{ label: "Statique (tuiles personnalisées)", value: "static" },
							{ label: "Dynamique (articles)", value: "dynamic" },
						]}
						onChange={(value) => setAttributes({ mode: value })}
					/>

					{mode === "dynamic" && (
						<>
							<ToggleControl
								label={__("Afficher tous les articles", "pr-tuile")}
								checked={showAllArticles}
								onChange={(value) => setAttributes({ showAllArticles: value })}
							/>
							{!showAllArticles && (
								<RangeControl
									label={__("Nombre d'articles à afficher", "pr-tuile")}
									value={articlesCount}
									onChange={(value) => setAttributes({ articlesCount: value })}
									min={1}
									max={12}
								/>
							)}
						</>
					)}
				</PanelBody>

				{mode === "static" && (
					<PanelBody title={__("Configuration des tuiles", "pr-tuile")}>
						{tiles.map((tile, index) => (
							<div key={index} style={{ marginBottom: "20px" }}>
								<TextControl
									label={`Titre ${index + 1}`}
									help="Phrase d'un maximum de 25 caractères"
									value={tile.titleField}
									onChange={(value) => updateTile(index, "titleField", value)}
								/>
								<TextControl
									label={`Texte ${index + 1}`}
									help="Phrase d'un maximum de 180 caractères"
									value={tile.textField}
									onChange={(value) => updateTile(index, "textField", value)}
								/>
								<TextControl
									label={__("URL du lien", "pr-tuile")}
									value={tile.linkUrl}
									onChange={(value) => updateTile(index, "linkUrl", value)}
								/>
								<ToggleControl
									label={__("Afficher l'image", "pr-tuile")}
									checked={tile.showImage}
									onChange={(value) => updateTile(index, "showImage", value)}
								/>
								{tile.showImage && (
									<MediaUpload
										onSelect={(media) => {
											updateTile(index, "imageUrl", media.url);
											updateTile(index, "imageAlt", media.alt);
										}}
										allowedTypes={["image"]}
										value={tile.imageUrl}
										render={({ open }) => (
											<Button
												onClick={open}
												variant="secondary"
												style={{ marginBottom: "10px" }}
											>
												{tile.imageUrl
													? "Changer l'image"
													: "Choisir une image"}
											</Button>
										)}
									/>
								)}
								<Button
									isDestructive
									onClick={() => removeTile(index)}
									style={{ marginTop: "10px" }}
								>
									Supprimer
								</Button>
							</div>
						))}
						<Button isPrimary onClick={addTile} style={{ marginTop: "10px" }}>
							Ajouter une tuile
						</Button>
					</PanelBody>
				)}
			</InspectorControls>

			<div className="pr-tuile-container" {...useBlockProps()}>
				{mode === "static" ? (
					// Affichage des tuiles statiques
					tiles.map((tile, index) => (
						<a key={index} className="pr-tuile-lien" href={tile.linkUrl}>
							{tile.showImage && (
								<div className="pr-tuile-lien-image">
									<img
										src={tile.imageUrl || "https://placecats.com/520/300"}
										alt={tile.imageAlt || `Image ${index + 1}`}
									/>
								</div>
							)}
							<div className="pr-tuile-lien-text">
								<h3>{tile.titleField}</h3>
								<p>{tile.textField}</p>
							</div>
						</a>
					))
				) : // Affichage des articles dynamiques
				articles ? (
					articles.map((article) => (
						<a key={article.id} className="pr-tuile-lien" href={article?.link}>
							{article.featured_media > 0 && (
								<div className="pr-tuile-lien-image">
									<img
										src={
											article._embedded?.["wp:featuredmedia"]?.[0]
												?.source_url || "https://placecats.com/520/300"
										}
										alt={article.title.rendered}
									/>
								</div>
							)}

							<div className="pr-tuile-lien-text">
								<h3>
									{article.title.rendered || "Il n'y a pas de description :("}
								</h3>
								<p>
									{article.acf?.article_description ||
										"Il n'y a pas de description :("}
								</p>
							</div>
						</a>
					))
				) : (
					<p>Chargement des articles...</p>
				)}
			</div>
		</>
	);
}

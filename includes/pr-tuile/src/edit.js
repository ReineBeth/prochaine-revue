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

	// Récupérer les auteurs pour chaque article
	const authorsData = useSelect(
		(select) => {
			if (mode !== "dynamic" || !articles) return {};

			const authorsMap = {};
			articles.forEach((article) => {
				if (article.id) {
					const terms = select("core").getEntityRecords(
						"taxonomy",
						"pr-auteurs",
						{
							post: article.id,
						},
					);
					authorsMap[article.id] = terms;
				}
			});
			return authorsMap;
		},
		[mode, articles],
	);

	// Fonction pour convertir les types d'articles
	const getTypeLabel = (typeValue) => {
		const typeChoices = {
			recherche: "Note de recherche",
			synthese: "Compte Rendu", // ← Corrigé pour correspondre au PHP
			opinion: "Texte réflexif",
			article: "Article",
		};
		return typeChoices[typeValue] || typeValue;
	};

	// Fonctions existantes pour les tuiles statiques
	function addTile() {
		setAttributes({
			tiles: [
				...tiles,
				{
					titleField: "",
					auteurs: "",
					typeArticle: "",
					textField: "",
					linkUrl: "",
					imageUrl: "",
					showImage: false,
				},
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
							<div
								key={index}
								style={{
									marginBottom: "20px",
									borderBottom: "1px solid #ddd",
									paddingBottom: "15px",
								}}
							>
								<h4>Tuile {index + 1}</h4>

								<TextControl
									label={`Titre ${index + 1}`}
									help="Phrase d'un maximum de 25 caractères"
									value={tile.titleField}
									onChange={(value) => updateTile(index, "titleField", value)}
								/>

								<TextControl
									label={`Auteurs ${index + 1}`}
									help="Séparez les auteurs par des virgules"
									value={tile.auteurs}
									onChange={(value) => updateTile(index, "auteurs", value)}
								/>

								<TextControl
									label={`Type d'article ${index + 1}`}
									help="Ex: Note de recherche, Texte réflexif..."
									value={tile.typeArticle}
									onChange={(value) => updateTile(index, "typeArticle", value)}
								/>

								<TextControl
									label={`Description ${index + 1}`}
									help="Phrase d'un maximum de 180 caractères (optionnel)"
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
									Supprimer la tuile
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
					// Affichage des tuiles statiques avec nouveau format
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

								{/* Affichage des auteurs sur lignes séparées */}
								{tile.auteurs && (
									<div className="pr-tuile-auteurs">
										{tile.auteurs.split(",").map((auteur, idx) => (
											<div key={idx} className="pr-tuile-auteur">
												{auteur.trim()}
											</div>
										))}
									</div>
								)}

								{/* Type d'article */}
								{tile.typeArticle && (
									<div className="pr-tuile-type">
										<strong>{tile.typeArticle}</strong>
									</div>
								)}

								{/* Description (optionnelle) */}
								{tile.textField && <p>{tile.textField}</p>}
							</div>
						</a>
					))
				) : // Affichage des articles dynamiques avec nouveau format
				articles ? (
					articles.map((article) => {
						const articleAuthors = authorsData[article.id] || [];
						const articleType = article.acf?.article_type;

						return (
							<a
								key={article.id}
								className="pr-tuile-lien"
								href={`/articles/${article.slug}/`}
							>
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
									<h3>{article.title.rendered}</h3>

									{/* Affichage des auteurs */}
									{articleAuthors && articleAuthors.length > 0 && (
										<div className="pr-tuile-auteurs">
											{articleAuthors.map((auteur, idx) => (
												<div key={idx} className="pr-tuile-auteur">
													{auteur.name}
												</div>
											))}
										</div>
									)}

									{/* Type d'article */}
									{articleType && (
										<div className="pr-tuile-type">
											<strong>{getTypeLabel(articleType)}</strong>
										</div>
									)}
								</div>
							</a>
						);
					})
				) : (
					<p>Chargement des articles...</p>
				)}
			</div>
		</>
	);
}

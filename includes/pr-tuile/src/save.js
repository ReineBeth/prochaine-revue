import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	const { tiles, mode } = attributes;

	return (
		<div className="pr-tuile-container" {...useBlockProps.save()}>
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
			) : (
				// Conteneur vide pour les articles dynamiques (rempli par PHP)
				<div className="pr-tuile-dynamiques" data-mode="dynamic"></div>
			)}
		</div>
	);
}

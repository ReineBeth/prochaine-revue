import { useBlockProps } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

export default function save({ attributes }) {
	const { tiles, mode } = attributes;

	// Pour le mode dynamique, on retourne null pour laisser PHP s'en occuper
	if (mode === "dynamic") {
		return null;
	}

	// Mode statique uniquement
	return (
		<div className="pr-tuile-container" {...useBlockProps.save()}>
			{tiles.map((tile, index) => (
				<a
					key={index}
					className="pr-tuile-lien"
					href={tile.linkUrl}
					rel="noopener noreferrer"
				>
					{tile.showImage && (
						<div className="pr-tuile-lien-image">
							<img
								src={tile.imageUrl || "https://placecats.com/520/300"}
								alt={tile.imageAlt || __("")}
								loading="lazy"
							/>
						</div>
					)}
					<div className="pr-tuile-lien-text">
						<h3>{tile.titleField}</h3>
						{tile.auteurs && (
							<div className="pr-tuile-auteurs">
								{tile.auteurs.split(",").map((auteur, idx) => (
									<div key={idx} className="pr-tuile-auteur">
										{auteur.trim()}
									</div>
								))}
							</div>
						)}
						{tile.typeArticle && (
							<div className="pr-tuile-type">
								<strong>{tile.typeArticle}</strong>
							</div>
						)}
						{tile.textField && <p>{tile.textField}</p>}
					</div>
				</a>
			))}
		</div>
	);
}

import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	useInnerBlocksProps,
	InnerBlocks,
	InspectorControls,
} from "@wordpress/block-editor";
import {
	PanelBody,
	TextControl,
	SelectControl,
} from "@wordpress/components";
import { useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import "./editor.scss";
import { useEffect } from "@wordpress/element";

const AccordeonItem = ({ title, headingLevel, children, id, uniqueId }) => {
	const [isOpen, setIsOpen] = useState(false);
	const HeadingTag = `h${headingLevel}`;

	const contentId = `content-${uniqueId || id}`;
	const triggerId = `trigger-${uniqueId || id}`;

	return (
		<div className="pr-accordeon-container">
			<HeadingTag>
				<button
					type="button"
					aria-expanded={isOpen}
					className={`pr-accordeon-trigger js-trigger ${
						isOpen ? "is-open" : ""
					}`}
					aria-controls={contentId}
					id={triggerId}
					onClick={() => setIsOpen(!isOpen)}
				>
					{title}
				</button>
			</HeadingTag>
			<div
				id={contentId}
				role="region"
				aria-labelledby={triggerId}
				className={`pr-accordeon-content js-content ${isOpen ? "is-open" : ""}`}
				hidden={!isOpen}
			>
				<div className="pr-accordeon-content-inner">{children}</div>
			</div>
		</div>
	);
};

export default function Edit({ attributes, setAttributes, clientId }) {
	const {
		titleField,
		headingLevel,
		mode,
		uniqueId,
	} = attributes;

	useEffect(() => {
		if (!uniqueId) {
			setAttributes({ uniqueId: `accordion-${clientId}` });
		}
	}, [clientId, setAttributes]);

	const authors = useSelect(
		(select) => {
			if (mode !== "dynamic") return null;

			return select("core").getEntityRecords("taxonomy", "pr-auteurs", {
				per_page: -1,
				_embed: true,
				orderby: "name",
				order: "asc",
			});
		},
		[mode],
	);

	const blockProps = useBlockProps({
		className: "pr-accordeon",
	});

	const innerBlocksProps = useInnerBlocksProps(
		{ className: "pr-accordeon-content-inner" },
		{
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		},
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Configuration générale", "pr-accordeon")}>
					<SelectControl
						label={__("Mode d'affichage", "pr-accordeon")}
						value={mode}
						options={[
							{ label: "Statique (accordéon personnalisé)", value: "static" },
							{ label: "Dynamique (auteurs)", value: "dynamic" },
						]}
						onChange={(value) => setAttributes({ mode: value })}
					/>

				</PanelBody>

				{mode === "static" && (
					<PanelBody title={__("Paramètres de l'accordéon", "pr-accordeon")}>
						<TextControl
							label={__("Titre de l'accordéon", "pr-accordeon")}
							help={__("Maximum 100 caractères", "pr-accordeon")}
							value={titleField || ""}
							onChange={(value) => setAttributes({ titleField: value })}
							maxLength={100}
						/>
						<SelectControl
							label={__("Niveau de titre", "pr-accordeon")}
							value={headingLevel}
							options={[
								{ label: "Titre 2", value: "2" },
								{ label: "Titre 3", value: "3" },
								{ label: "Titre 4", value: "4" },
								{ label: "Titre 5", value: "5" },
							]}
							onChange={(value) => setAttributes({ headingLevel: value })}
							help={__(
								"Choisissez le niveau hiérarchique du titre",
								"pr-accordeon",
							)}
						/>
					</PanelBody>
				)}
			</InspectorControls>

			<div {...blockProps}>
				{mode === "static" ? (
					<AccordeonItem
						title={titleField || __("Titre de l'accordéon", "pr-accordeon")}
						headingLevel={headingLevel}
						id="static"
						uniqueId={uniqueId}
					>
						<div {...innerBlocksProps} />
					</AccordeonItem>
				) : authors ? (
					authors.map((author) => (
						<AccordeonItem
							key={author.id}
							title={author.name}
							headingLevel={headingLevel}
							id={author.id}
							uniqueId={uniqueId}
						>
							<p>Articles de {author.name}</p>
						</AccordeonItem>
					))
				) : (
					<p>Chargement des auteurs...</p>
				)}
			</div>
		</>
	);
}

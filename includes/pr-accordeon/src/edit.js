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
    ToggleControl,
    RangeControl
} from "@wordpress/components";
import { useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import "./editor.scss";

const AccordeonItem = ({ title, headingLevel, children, id }) => {
    const [isOpen, setIsOpen] = useState(false);
    const HeadingTag = `h${headingLevel}`;

    return (
        <div className="pr-accordeon-container">
            <HeadingTag>
                <button
                    type="button"
                    aria-expanded={isOpen}
                    className={`pr-accordeon-trigger js-trigger ${isOpen ? "is-open" : ""}`}
                    aria-controls={`content-${id}`}
                    id={`trigger-${id}`}
                    onClick={() => setIsOpen(!isOpen)}
                >
                    {title}
                </button>
            </HeadingTag>
            <div
                id={`content-${id}`}
                role="region"
                aria-labelledby={`trigger-${id}`}
                className={`pr-accordeon-content js-content ${isOpen ? "is-open" : ""}`}
                hidden={!isOpen}
            >
                <div className="pr-accordeon-content-inner">
                    {children}
                </div>
            </div>
        </div>
    );
};

export default function Edit({ attributes, setAttributes }) {
    const {
        titleField,
        headingLevel,
        mode,
        authorCount,
        showAllAuthors
    } = attributes;

    const authors = useSelect(
        (select) => {
            if (mode !== "dynamic") return null;

            return select("core").getEntityRecords("taxonomy", "pr-auteurs", {
                per_page: showAllAuthors ? -1 : authorCount,
                _embed: true,
                orderby: "name",
                order: "asc",
            });
        },
        [mode, authorCount, showAllAuthors]
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

                    {mode === "dynamic" && (
                        <>
                            <ToggleControl
                                label={__("Afficher tous les auteurs", "pr-accordeon")}
                                checked={showAllAuthors}
                                onChange={(value) => setAttributes({ showAllAuthors: value })}
                            />
                            {!showAllAuthors && (
                                <RangeControl
                                    label={__("Nombre d'auteurs à afficher", "pr-accordeon")}
                                    value={authorCount}
                                    onChange={(value) => setAttributes({ authorCount: value })}
                                    min={1}
                                    max={12}
                                />
                            )}
                        </>
                    )}
                </PanelBody>

                {mode === "static" && (
                    <PanelBody title={__("Paramètres de l'accordéon", "pr-accordeon")}>
                        <TextControl
                            label={__("Titre de l'accordéon", "pr-accordeon")}
                            help={__("Maximum 25 caractères", "pr-accordeon")}
                            value={titleField || ""}
                            onChange={(value) => setAttributes({ titleField: value })}
                            maxLength={25}
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
                            help={__("Choisissez le niveau hiérarchique du titre", "pr-accordeon")}
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

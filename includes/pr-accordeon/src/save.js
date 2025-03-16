import { __ } from "@wordpress/i18n";
import { useBlockProps, useInnerBlocksProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
    const {
        titleField,
        headingLevel,
        mode,
        authorCount,
        showAllAuthors
    } = attributes;

    const blockProps = useBlockProps.save({
        className: "pr-accordeon",
    });

    const innerBlocksProps = useInnerBlocksProps.save({
        className: "pr-accordeon-content-inner"
    });

    const HeadingTag = `h${headingLevel}`;

    // Pour le mode statique uniquement, car le mode dynamique sera rendu côté serveur
    if (mode === "static") {
        return (
            <div {...blockProps}>
                <div className="pr-accordeon-container">
                    <HeadingTag>
                        <button
                            type="button"
                            aria-expanded="false"
                            className="pr-accordeon-trigger js-trigger"
                            aria-controls="content-static"
                            id="trigger-static"
                        >
                            {titleField || __("Titre de l'accordéon", "pr-accordeon")}
                        </button>
                    </HeadingTag>
                    <div
                        id="content-static"
                        role="region"
                        aria-labelledby="trigger-static"
                        className="pr-accordeon-content js-content"
                        hidden
                    >
                        <div {...innerBlocksProps} />
                    </div>
                </div>
            </div>
        );
    }

    // Pour le mode dynamique, on retourne juste un conteneur vide
    // Le contenu sera généré côté serveur via le render_callback
    return (
        <div {...blockProps}>
            <div className="pr-accordeon-dynamic" data-show-all={showAllAuthors} data-count={authorCount}></div>
        </div>
    );
}

const { registerBlockType } = wp.blocks;
const { useSelect } = wp.data;
const { RichText } = wp.blockEditor;

registerBlockType('custom-field/auteurs', {
	title: 'Auteurs Article',
	icon: 'admin-users',
	category: 'custom-fields',
	attributes: {
		auteurs: {
			type: 'string',
			source: 'meta',
			meta: 'article_auteurs',
		},
	},
	edit: (props) => {
		const auteurs = useSelect(
			(select) =>
				select('core/editor').getEditedPostAttribute('meta')[
					'article_auteurs'
				]
		);

		return (
			<div>
				<p>Auteurs : {auteurs || '[Auteurs]'}</p>
			</div>
		);
	},
	save: () => null, // Rendu côté serveur
});

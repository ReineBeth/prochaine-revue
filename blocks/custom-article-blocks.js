// Fichier: blocks/custom-article-blocks.js

const { registerBlockType } = wp.blocks;
const { useSelect } = wp.data;

// Bloc pour les auteurs
registerBlockType('custom-article/auteurs', {
	title: "Auteurs de l'article",
	icon: 'admin-users',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const auteurs = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.meta?.article_auteurs
		);

		return wp.element.createElement(
			'div',
			{ className: 'article-auteurs' },
			auteurs ? `Auteurs: ${auteurs}` : 'Chargement des auteurs...'
		);
	},
	save: function () {
		return null; // Rendu côté serveur
	},
});

// Bloc pour la description
registerBlockType('custom-article/description', {
	title: "Description de l'article",
	icon: 'text',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const description = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.meta?.article_description
		);

		return wp.element.createElement(
			'div',
			{ className: 'article-description' },
			description ? description : 'Chargement de la description...'
		);
	},
	save: function () {
		return null;
	},
});

// Bloc pour le PDF
registerBlockType('custom-article/pdf', {
	title: "PDF de l'article",
	icon: 'pdf',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const pdf = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.meta?.article_pdf
		);

		return wp.element.createElement(
			'div',
			{ className: 'article-pdf' },
			pdf ? `PDF: ${pdf.title}` : 'Chargement du PDF...'
		);
	},
	save: function () {
		return null;
	},
});

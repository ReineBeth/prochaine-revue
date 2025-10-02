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
					?.article_auteurs
		);
		return wp.element.createElement(
			'div',
			{ className: 'article-auteurs' },
			auteurs ? `Auteurs: ${auteurs}` : 'Chargement des auteurs...'
		);
	},
	save: function () {
		return null;
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
					?.article_description
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

// Bloc pour le type d'article
registerBlockType('custom-article/type', {
	title: "Type d'article",
	icon: 'category',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const articleType = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.article_type
		);

		let typeLabel = "Chargement du type d'article...";
		if (articleType) {
			const typeChoices = {
				recherche: 'Note de recherche',
				synthese: 'Compte Rendu',
				opinion: 'Texte réflexif',
			};
			typeLabel = typeChoices[articleType] || articleType;
		}

		return wp.element.createElement(
			'div',
			{ className: 'article-type' },
			typeLabel
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
					?.article_pdf
		);
		return wp.element.createElement(
			'div',
			{ className: 'article-pdf' },
			pdf ? `PDF: ${pdf.title || 'Fichier PDF'}` : 'Chargement du PDF...'
		);
	},
	save: function () {
		return null;
	},
});

// Bloc pour les disciplines
registerBlockType('custom-article/disciplines', {
	title: 'Disciplines concernées',
	icon: 'book-alt',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const disciplines = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.disciplines
		);
		return wp.element.createElement(
			'div',
			{ className: 'article-disciplines' },
			disciplines
				? `Discipline(s) concernée(s): ${disciplines}`
				: 'Chargement des disciplines...'
		);
	},
	save: function () {
		return null;
	},
});

// Bloc pour les mots clés
registerBlockType('custom-article/mots-cles', {
	title: 'Mots clés',
	icon: 'tag',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const motsCles = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.mots_cles
		);
		return wp.element.createElement(
			'div',
			{ className: 'article-mots-cles' },
			motsCles ? `Mots clés: ${motsCles}` : 'Chargement des mots clés...'
		);
	},
	save: function () {
		return null;
	},
});

// Bloc pour les droits d'auteur
registerBlockType('custom-article/droits-auteur', {
	title: "Droits d'auteur",
	icon: 'admin-page',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const droits = useSelect(
			(select) =>
				select('core').getEntityRecord('postType', 'pr_article', postId)
					?.droits_auteur
		);
		return wp.element.createElement(
			'div',
			{ className: 'article-droits-auteur' },
			droits ? droits : "Chargement des droits d'auteur..."
		);
	},
	save: function () {
		return null;
	},
});

// Bloc pour les informations de publication
registerBlockType('custom-article/infos-publication', {
	title: 'Informations de publication',
	icon: 'calendar-alt',
	category: 'common',
	supports: {
		html: false,
	},
	edit: function (props) {
		const postId = useSelect((select) =>
			select('core/editor').getCurrentPostId()
		);
		const articleData = useSelect((select) =>
			select('core').getEntityRecord('postType', 'pr_article', postId)
		);

		let infosPublication = 'Chargement des informations de publication...';

		if (articleData) {
			const volume = articleData.volume;
			const numeroVolume = articleData.numero_volume;
			const mois = articleData.mois_publication;
			const annee = articleData.annee_publication;
			const pages = articleData.pages;

			const infos = [];

			if (volume && numeroVolume) {
				infos.push(`Volume ${volume}, numéro ${numeroVolume}`);
			} else if (volume) {
				infos.push(`Volume ${volume}`);
			}

			if (mois && annee) {
				infos.push(`${mois} ${annee}`);
			} else if (annee) {
				infos.push(annee);
			}

			if (pages) {
				infos.push(`p. ${pages}`);
			}

			if (infos.length > 0) {
				infosPublication = infos.join(', ') + '.';
			} else {
				infosPublication =
					'Informations de publication non disponibles';
			}
		}

		return wp.element.createElement(
			'div',
			{ className: 'article-infos-publication' },
			infosPublication
		);
	},
	save: function () {
		return null;
	},
});

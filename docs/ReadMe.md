# La Prochaine Revue — documentation du thème

Ce dossier documente le thème WordPress `prochaine-revue`, utilisé pour le site éditorial de **La Prochaine Revue**. Le thème permet de publier des articles de revue, de les associer à des auteurs et à un type d’article, de joindre leur PDF et de les présenter dans une interface publique responsive et accessible.

## Liens utiles

- [Maquette Figma](https://www.figma.com/design/NHoW21qZUymlmwkIUWo7nJ/La-Prochaine-Revue?node-id=6-2&node-type=canvas&t=S90hbwn4tcKHKdho-0)
- [Tableau Trello](https://trello.com/b/jlMqFfEz/prochaine-revue-dev)
- [Documentation Gutenberg](https://developer.wordpress.org/block-editor/getting-started/quick-start-guide/)

## Prérequis

- WordPress avec l’éditeur de blocs et les thèmes FSE activés.
- ACF, utilisé pour les champs éditoriaux des articles et des auteurs.
- PHP. La version `7.4.9` est celle observée dans l’installation WampServer locale; la version minimale officielle reste à confirmer.
- Node.js et npm pour les composants Gutenberg.
- Sass pour compiler les styles globaux.
- Apache et MySQL démarrés dans WampServer pour la vérification locale.

## Démarrage local

La racine WordPress locale documentée est :

```text
C:\wamp64\www\prochaine-revue
```

La racine de travail du thème est :

```text
C:\wamp64\www\prochaine-revue\wp-content\themes\prochaine-revue
```

Démarre Apache et MySQL dans WampServer, active le thème dans WordPress, puis ouvre l’installation locale configurée. L’URL locale exacte n’est pas imposée par le dépôt.

Toutes les commandes ci-dessous s’exécutent depuis la racine du thème, sauf indication contraire.

## Architecture

```text
functions.php                  Point d’entrée et chargement des modules
theme.json                     Réglages et styles globaux Gutenberg
templates/                     Gabarits FSE
parts/                         Parties de gabarit réutilisables
includes/setup/                Initialisation et chargement des actifs
includes/post-types/           Type de contenu pr_article
includes/taxonomies/           Auteurs et types d’article
includes/acf/                  Champs ACF éditoriaux
includes/blocks/               Blocs PHP dynamiques
includes/shortcodes/           Compatibilité et rendus historiques
includes/pr-*/                 Composants Gutenberg officiels
blocks/                        Blocs d’article historiques
assets/scss/                   Sources SCSS
assets/js/                     JavaScript du thème
docs/context/                  Contexte général et contrats stables
docs/features/                 Spécifications de fonctionnalités
tests/                         Vérifications et scripts de test existants
```

Les composants officiels sont `pr-accordeon`, `pr-carte`, `pr-tuile` et `pr-bloc-recherche`. `pr-nom-composant` est un prototype conservé dans le dépôt et ne fait pas partie des composants officiels.

## Modèle éditorial

Le type de contenu public est `pr_article`. Un article peut contenir :

- un titre, un contenu et une image mise en avant;
- une description, un type d’article et un PDF obligatoire;
- un ou plusieurs auteurs;
- des métadonnées de revue, volume, numéro, pages et année;
- des disciplines, mots-clés et droits d’auteur;
- une citation APA, un protocole de citation propre à la revue et un fichier `.ris` facultatifs.

Les champs de citation sont affichés dans la box **« Pour citer cet article »** de la page individuelle. Les lignes sans contenu restent masquées.

## Développement des blocs Gutenberg

Créer un nouveau composant avec :

```bash
npx @wordpress/create-block pr-nom-composant
```

Pour un composant existant, remplacer `pr-tuile` par le dossier ciblé :

```powershell
npm --prefix .\includes\pr-tuile ci
npm --prefix .\includes\pr-tuile run start
npm --prefix .\includes\pr-tuile run build
npm --prefix .\includes\pr-tuile run lint:js
npm --prefix .\includes\pr-tuile run lint:css
```

`pr-bloc-recherche` génère également son manifeste de blocs pendant le build.

Les sources doivent être modifiées avant les sorties compilées. Lorsque le composant possède un dossier `build/`, celui-ci doit être recompilé avant une livraison.

## Styles globaux

Compiler le SCSS depuis la racine du thème :

```powershell
sass --watch .\assets\scss\index.scss .\style.css
```

Les styles utilisent en priorité les jetons définis dans `theme.json`. Les classes propres au thème sont préfixées `pr-` lorsque c’est pertinent.

## Vérifications

Vérifier la syntaxe PHP du thème avec le binaire WampServer observé :

```powershell
Get-ChildItem . -Recurse -Filter *.php | ForEach-Object {
    & 'C:\wamp64\bin\php\php7.4.9\php.exe' -l $_.FullName
}
```

Pour une modification JavaScript ciblée :

```powershell
node --check .\blocks\custom-article-blocks.js
```

Toute modification fonctionnelle doit aussi être vérifiée dans l’administration WordPress et sur le site public, avec du contenu réel et des données absentes. Les parcours principaux doivent être contrôlés à 320 px, 768 px, 1024 px et 1440 px, au clavier et avec un zoom de 200 %.

## Déploiement

Les déploiements sont effectués manuellement par téléversement cPanel. Avant une livraison :

1. exécuter les vérifications PHP, JavaScript, CSS et les builds des composants touchés;
2. vérifier l’administration, le frontal et les parcours de lecture;
3. inclure les sources et les sorties compilées nécessaires;
4. fournir la liste exacte des fichiers à téléverser;
5. signaler séparément toute opération requise dans l’administration ou la base de données;
6. purger le cache LiteSpeed après le téléversement et vérifier en navigation privée.

Ne jamais déployer une modification de production sans demande explicite.

## Documentation du projet

- [Carte des capacités](context/CAPABILITY-MAP.md)
- [Socle du thème](context/SPEC-theme-foundation.md)
- [Modèle éditorial](context/SPEC-editorial-model.md)
- [Composants éditoriaux](context/SPEC-editorial-components.md)
- [Expérience publique](context/SPEC-site-experience.md)
- [Fonctionnalité des citations d’articles](features/article-citations/SPEC.md)
- [Fonctionnalité des titres formatés](features/formatted-article-titles/SPEC.md)

Les documents de `context/` décrivent le comportement et les contraintes générales du thème. Les documents de `features/` décrivent des fonctionnalités précises et leurs tâches associées.

## Règles de contribution

- Préserver les identifiants WordPress, les permaliens et les contrats REST existants.
- Préfixer les fonctions PHP par `pr_` et échapper toute sortie selon son contexte.
- Ne pas modifier uniquement un fichier généré lorsqu’une source correspondante existe.
- Ne pas supprimer ou renommer un bloc, un champ ou une taxonomie sans décision documentée.
- Ne pas ajouter de dépendance, modifier `theme.json` ou changer la structure globale sans validation préalable.

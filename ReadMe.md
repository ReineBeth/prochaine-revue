# Prochaine Revue

Thème WordPress de **La Prochaine Revue**, une revue scientifique numérique québécoise.

Le thème fournit :

- la publication d’articles `pr_article` ;
- la gestion des auteurs, types d’articles et métadonnées éditoriales ;
- l’ajout de fichiers PDF et RIS ;
- des blocs Gutenberg pour composer les pages ;
- des gabarits FSE pour l’accueil, les articles, les auteurs et les pages institutionnelles ;
- une interface responsive et accessible.

## Développement local

Le projet est prévu pour fonctionner avec WordPress, Apache, MySQL, PHP, ACF, Node.js, npm et Sass. Les commandes de développement s’exécutent depuis la racine du thème.

Compiler les styles SCSS en mode surveillance :

```powershell
npm run watch:scss
```

Construire ou contrôler un composant Gutenberg, par exemple `pr-tuile` :

```powershell
npm --prefix .\includes\pr-tuile ci
npm --prefix .\includes\pr-tuile run build
npm --prefix .\includes\pr-tuile run lint:js
npm --prefix .\includes\pr-tuile run lint:css
```

Vérifier la syntaxe PHP avec le binaire PHP de WampServer configuré localement :

```powershell
Get-ChildItem . -Recurse -Filter *.php | ForEach-Object {
    & 'C:\wamp64\bin\php\php7.4.9\php.exe' -l $_.FullName
}
```

## Structure principale

```text
templates/       Gabarits FSE
parts/           Parties de gabarit réutilisables
includes/        Modèle éditorial, ACF, blocs et shortcodes
blocks/          Blocs d’article historiques
assets/          SCSS, JavaScript, images et polices
docs/            Documentation de travail locale
tests/           Vérifications et scripts de test
```

Les déploiements en production sont effectués manuellement par cPanel. Toute source de composant modifiée doit être accompagnée de ses sorties compilées nécessaires avant le téléversement.

La documentation détaillée du projet est conservée localement dans `docs/` et n’est pas publiée avec le dépôt.

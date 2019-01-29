# Search
Plugin Search for Magix CMS 3

Ajouter un formulaire de recherche sur votre site internet.

Il peut être intéressant de l'ajouter à votre page d'erreur 404 (error/index.tpl)

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner search (Recherche sur le site).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
 * Copier le contenu du dossier skin/public dans le dossier de votre skin.

### Ajouter à l'endroit où e formulaire de recherche doit apparaître

```smarty
{include file="search/brick/search.tpl"}
````
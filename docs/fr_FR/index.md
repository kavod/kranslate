# Kranslate by kavod

## Présentation
Kranslate est un outil d'assistance à la traduction de plugins destiné aux développeurs de plugin Jeedom.
Il permet la génération automatique des fichiers contenus dans le répertoire ```i18n``` et vous assiste dans leur maintenance.

## Mode d'emploi
Une fois le plugin activé, sélectionnez les langues que vous souhaitez gérer.

![Sélection des langues gérées](/docs/images/selection_langues.png)

N'oubliez pas de sauvegarder !

Puis, rendez-vous sur la page du plugin (Programmation > kranslate) pour appuyer sur le bouton "Ajouter plugin".

![Ajouter plugin](/docs/images/ajout_plugin.png)

Sélectionnez le plugin à traduire dans la liste déroulante

![Sélection du plugin à traduire](/kranslate/images/selection_plugin.png)

Le plugin apparait comme un équipement. Cliquez dessus.
La page est composée d'un premier onglets avec plusieurs boutons, et d'autant d'autres onglets que de langues que vous avez précédemment sélectionné dans votre configuration principale.

![Overview](/docs/images/overview.png)

Utilisez le bouton "Scanner les traductions" pour identifier toutes les "phrases" à traduire dans le plugin.
Techniquement, kranslate va identifier toutes les occurrences de type ```{{blablabla}}``` et ```__('blablabla',__FILE__)``` des pages php et js.
Pour chaque occurrence, si le fichier i18n existe, il va identifier la traduction existante.

Après un certain temps, vous retrouverez toutes les "phrases" dans les onglets "Traductions".
Il ne vous restera donc plus qu'à passer de phrase en phrase (grâce à la touche Tab de votre clavier) pour renseigner la traduction.

N'oubliez pas de sauvegarder !

Une fois toutes les traductions gérées, rendez-vous sur le premier onglet "Plugin" et appuyer sur le bouton "Télécharger les fichiers i18n" pour récupérer un zip avec l'ensemble de vos fichier json à déposer dans le répertoire core/i18n de votre plugin.

## Evolutions prévues
Il ne s'agit qu'une toute première version, j'ai idée de l'enrichir de la façon suivante :
* Identifier les "phrases" devenues inutilisées (suite à une mise à jour)
* Ajout manuel de "phrase" (peut être nécessaire en cas de texte dynamique)
* Interface avec API Google Cloud Traduction pour génération automatique des traductions

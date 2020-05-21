## Programmation Web

# Installation 

Pour lancer notre projet il faut commancer par lancer la suite de commande suivante :

```bash
php bin/console doctrine:database:create

php bin/console make:migration

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixture:load
```

De là, symfony va créer quelques cous et deux profils utilisateurs.
Le profil d'utilisateur du professeur :
- identifiant : userp
- mot de passe : testtest

Et le profil d'un eleve :
- identifiant : usere
- mot de passe : testtest


Bien entendu libre à vous de vous créer de nouveau compte avec la rubrique 'inscription'

# Fonctionnement

Si vous êtes connecté en tant qu'enseignant, vous pouvez :

- Créer un cours en donnant, un titre, un temps estimé, mais aussi une solution (ou plusieurs en cliquant sur "ajouter solution") et des lignes à ne pas utiliser.

- Vous pouvez visualiser la liste de vos cours, en accédant à un details des notes élève, mais aussi pour chaque exercice en cliquant sur "Afficher".

Si vous êtes connecté en tant qu'élève, vous pouvez:

- Vous inscrire à des cours, et effectuer les exercices (dans l'ordre de création)- Vous ne pouvez passer à l'exercice suivant que si vous validez une bonne réponse ou que vous abandonniez !

- Vous avez aussi accès à un detail de vos cours et de votre avancement en allant sous la rubrique "mes cours" et en cliquant sur la fleche d'un exercice pour avoir son detail.# CouvertureNous avons essayé de couvrir un maximum de critère "optimaux" :

- Inscription avec identifiant et authentification par mot de passe, rôles enseignant/étudiant bien séparés (pages enseignants inaccessibles aux étudiants)La page devrait trouver une page 404 not found si un élève veut accéder à la création des cours.

- Interface complète avec création d’instructions et possibilité d’assigner plusieurs solutions à un même exercice- Validation dynamique (utilisation d'Ajax) et affichage d'une solution possible en cas d'abandon de l'exercice en cours

- Interface complète avec liens vers chaque exercice (les exercices ne sont malheureusement réalisables que dans l'ordre de création) et statut de résolution.

- Interface complète avec résultats par cours/exercice/étudiant


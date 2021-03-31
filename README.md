# reunionou-api
Lien Github : https://github.com/Benjamin-Mathieu/reunionou-api
Documentation Api webapp reunionou : https://documenter.getpostman.com/view/15183611/TzCMdoHW
Documentation Api backoffice : https://documenter.getpostman.com/view/15183611/TzCMdoD1

Le backend est divisé en 4 conteneurs docker:
- Api reunionou
- Api backoffice
- mysql
- phpMyAdmin
Les deux Api utilise le meme vhost : docketu.iutnc.univ-lorraine.fr

Deployer les conteneurs docker avec Docker-compose puis exécuter un bash dans chacun des conteneurs
des API puis exécuter un composer-install pour générer les dépendances ainsi que les autoloaders.

Identifiant user backoffice:
username : admin
password : testadmin
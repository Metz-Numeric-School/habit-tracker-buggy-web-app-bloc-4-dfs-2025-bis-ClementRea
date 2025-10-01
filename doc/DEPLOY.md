# Procédure de Déploiement

Décrivez ci-dessous votre procédure de déploiement en détaillant chacune des étapes. De la préparation du VPS à la méthodologie de déploiement continu.

## Préparation du VPS

1. Connection en ssh à la VM debian : ssh root@ip

2. Installation de aapanel sur la vm avec un script d'installation :

```
URL=https://www.aapanel.com/script/install_7.0_en.sh && if [ -f /usr/bin/curl ];then curl -ksSO "$URL" ;else wget --no-check-certificate -O install_7.0_en.sh "$URL";fi;bash install_7.0_en.sh aapanel
```

3. Une fois l'installation terminé, Je me connect à aapanel pour configurer le déploiement

- J'installe un serveur LNMP avec nginx
- Je configure mon site en mettant comme nom de dommaine cele que on m'a fournis, `rea-dfsgr2.local`
- Je créer en même temps la base de donées et je met de côté ses identifiants

4. Pour terminer la configuration de aapanel j'ai besoin de connecter le code local à la la VM

- Pour se faire, je vais dans le dossier /var
- Je créer un dossier `depot_git`
- je me rend dedans et j'init un repos git bare avec `git init --bare`

5. Je retourne vers mon code local pour ajouter un remote que je vais appeler vps

- `git remote add vps root@172.17.4.39:/var.depot_git`
- Et je push sur la VM `git push -u vps main`

6. Pour terminer le déploiement j'ai besoin de créer un tag sur le code local
   pour ensuite le push sur la vm

- `git tag 1.0.0`
- `git push vps 1.0.0`

- Sur la VM maintenant je peux récupérer tout ce qui est necessaire pour déployer `git --git-dir=/var/depot_git --work-tree=/www/wwwroot/rea-dfsgr2 _local checkout -f 1.0.0`

7. Une fois tout ça fait je créer le .env dans le portail aapanel

## Méthode de déploiement

1. Je créer dans le dossier `/var/depot_git` un fichier `deploy.sh`

2. J'y ajoute mon script de déploiement

```
`VARNAME=${1:?"missing arg 1 for tag name or branch name"}`

`git --git-dir=/var/depot_git --work-tree=/www/wwwroot/rea_dfsgr2_local checkout -f $VARNAME`
```

3. je le rend exécutable chmod +x deploy.sh

4. j'ai juste à lancer la commande ./deploy.sh {tag_number} pour lancer le déploiement

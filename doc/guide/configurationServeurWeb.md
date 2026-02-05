# Installation du serveur web Apache 2

### Note
	5 étapes en tout

## 1. Installation du serveur web  
   - Mettre à jour la liste des paquets :
     ```bash
     $ sudo apt update
     ```
   - Installer Apache 2 :
     ```bash
     $ sudo apt install apache2
     ```
   - Vérifier que le paquet est bien installé :
     ```bash
     $ apache2 -v
     ```
     Sortie typique :
     ```texte
     Server version: Apache/2.4.54 (Debian)
     Server built:   2023-06-12T16:05:12
     Server's Module Magic Number: 20120211:83
     Server loaded:  APR 1.7.0, APR-UTIL 1.6.1
     Compiled using: APR 1.7.0, APR-UTIL 1.6.1
     Architecture:   64-bit
     ```
   - Vérifier l’état du service Apache :
     ```bash
     $ systemctl status apache2
     ```
     Sortie typique :
     ```texte
     ● apache2.service - The Apache HTTP Server
          Loaded: loaded (/lib/systemd/system/apache2.service; enabled; vendor preset: enabled)
          Active: active (running) since Fri 2026-02-05 10:15:42 UTC; 2min 30s ago
            Docs: https://httpd.apache.org/docs/2.4/
         Process: 1234 ExecStart=/usr/sbin/apachectl start (code=exited, status=0/SUCCESS)
        Main PID: 1238 (apache2)
           Tasks: 6 (limit: 4915)
          Memory: 12.3M
             CPU: 200ms
          CGroup: /system.slice/apache2.service
                  ├─1238 /usr/sbin/apache2 -k start
                  ├─1239 /usr/sbin/apache2 -k start
                  └─1240 /usr/sbin/apache2 -k start
     ```
   - Après l’installation, Apache crée automatiquement l’utilisateur système `www-data` pour exécuter le serveur.
   Cette utilisateur n'a pas de mot de passe est n'est pas "accesible"

   - Répertoires créés par Apache 2 :
     - **Répertoire racine** du site web `/var/www/html`  
     C’est ici que sont les fichiers du site (`index.html`, etc.).  
     Plusieurs fichiers HTML peuvent coexister pour représenter les différentes pages d'un site.
     - **Répertoire configuration** principale `/etc/apache2/`  
     `apache2.conf` → fichier de configuration globale  
     `ports.conf` → définit les ports d’écoute (80 pour HTTP, 443 pour HTTPS)  
     `mods-available/` et `mods-enabled/` → modules Apache disponibles et activés (non utilisé dans le projet)    
     `sites-available/` et `sites-enabled/` → configuration des sites (non utilisé dans le projet)
     - **Répertoire log** `/var/log/apache2/`   
     `access.log` → journal des accès   
     `error.log` → journal des erreurs

   - Sécurité et droits
     - Apache s’exécute toujours avec l’utilisateur `www-data` pour limiter les droits.
     - L’utilisateur `www-data` **n’aura aucun autre droit sur le reste du système**, en dehors de :  
     `/var/www/html` → fichiers du site  
     `/var/log/apache2` → fichiers de logs

## 2. Activation au démarrage

Pour que le serveur démarre automatiquement au boot :
   ```bash
   $ sudo systemctl enable apache2
   ```

## 3. Redémarrage / rechargement après modification

Après modification de fichiers de configuration, il est utile de savoir recharger Apache sans couper le service :

   ```bash
   $ sudo systemctl reload apache2   # recharge la configuration sans arrêter le service
   $ sudo systemctl restart apache2  # redémarre complètement Apache
   ```

## 4. Test de fonctionnement avec localhost via navigateur

Pour vérifier que le serveur fonctionne correctement, ouvre un navigateur web et tape : [http://localhost](http://localhost)   
Si tout est correct, tu devrais voir la page par défaut d’Apache (`index.html`) s’afficher.

## 5.  Bonnes pratiques 
- Vérifier que tous les fichiers et dossiers du site appartiennent à `www-data` ou ont des permissions suffisantes pour être lus par ce compte.
- Utiliser `chown` et `chmod` pour gérer les droits des fichiers

# Configuration du serveur web

## Liste des droit d'accès du serveur web de `www-data`

- `/var/www/html` :  
  - Propriétaire : `utilisateur:groupe` → `www-data:www-data`  
  - Droits : `500` → `dr-x------`  
    Le **droit d’exécution** permet à `www-data` d’entrer dans le dossier.  
    Le **droit de lecture** permet à `www-data` de parcourir le dossier et ses sous-dossiers.

- `/var/www/html/sous-dossiers` :  
  - Propriétaire : `utilisateur:groupe` → `www-data:www-data`  
  - Droits : `500` → `dr-x------`  
    Le **droit d’exécution** permet à `www-data` d’entrer dans le dossier.  
    Le **droit de lecture** permet à `www-data` de parcourir le dossier et ses sous-dossiers.

- `/var/www/html/fichiers` :  
  - Propriétaire : `utilisateur:groupe` → `www-data:www-data`  
  - Droits : `400` → `-r--------`  
    Le **droit de lecture** permet à `www-data` de lire les fichiers.
  - Les fichier peuvent être dans les sous-dossiers avec ces meme droits.

- `/var/log/apache2/access.log` :  
  - Propriétaire : `utilisateur:groupe` → `www-data:www-data`  
  - Droits : `600` → `-rw-------`  
    Le **droit de lecture** permet à `www-data` de lire le fichier.  
    Le **droit d’écriture** permet à `www-data` d’écrire dans le fichier.

- `/var/log/apache2/error.log` :  
  - Propriétaire : `utilisateur:groupe` → `www-data:www-data`  
  - Droits : `600` → `-rw-------`  
    Le **droit de lecture** permet à `www-data` de lire le fichier.  
    Le **droit d’écriture** permet à `www-data` d’écrire dans le fichier.

## Liste des droit d'accès du serveur web de `root`

- `/etc/apache2/ -R` :  
  - Propriétaire : `utilisateur:groupe` → `root:root`  
  - Droits : `700` → `drwx------`  
    Le **droit d’exécution** permet à `root` d’entrer dans le dossier.  
    Le **droit de lecture** permet à `root` de parcourir le dossier et ses sous-dossiers.
    Le **droit d’écriture** permet à `root` d’écrire dans les fichiers.
  - Les droits sont mis en récursif pour appliquer sur tout les fichier et sous-dossiers ses même droits.

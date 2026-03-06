# Installation du serveur web Apache 2

> Note : 5 étapes principales

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
   - Vérifier l’état du service Apache 2 :
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
   Cet utilisateur n'a pas de mot de passe et n'est pas accessible

   - Répertoires créés par Apache 2 :
     - **Répertoire racine** du site web `/var/www/html`  
     C’est ici que sont les fichiers du site (`index.php`, etc.).  
     Plusieurs fichiers HTML peuvent coexister pour représenter les différentes pages d'un site.
     - **Répertoire de configuration principale** `/etc/apache2/`  
     `apache2.conf` → fichier de configuration globale  
     `ports.conf` → définit les ports d’écoute (80 pour HTTP, 443 pour HTTPS)  
     `sites-available/` et `sites-enabled/` → configuration des sites (non utilisés dans le projet)
     - **Répertoire log** `/var/log/apache2/`   
     `access.log` → journal des accès   
     `error.log` → journal des erreurs

   - Sécurité et droits
     - Apache s’exécute toujours avec l’utilisateur `www-data` pour limiter les droits.
     - L’utilisateur `www-data` **n’aura aucun autre droit sur le reste du système**, en dehors de :  
     `/var/www/html` → fichiers du site  
     `/var/log/apache2` → fichiers de logs

## 2. Installation du serveur php

   - Mettre à jour la liste des paquets :
     ```bash
     $ sudo apt update
     ```
   - Installer php et le module php de apache2 :
     ```bash
     $ sudo apt install php8.4 libapache2-mod-php8.4
     ```
   - Vérifier que le paquet est bien installé :
     ```bash
     $ php8.4 -v
     ```
     Sortie typique :
     ```texte
     PHP 8.4.xx (cli) (built: Jan  7 2026 08:40:32) (NTS)
     Copyright (c) The PHP Group
     Zend Engine v4.3.6, Copyright (c) Zend Technologies
     with Zend OPcache v8.3.6, Copyright (c), by Zend Technologies
     ```
   - Activer le module PHP dans Apache
     ```bash
     $ sudo a2enmod php8.4
     $ sudo systemctl restart apache2
     ```

## 3. Activation au démarrage

Pour que le serveur démarre automatiquement au boot :
   ```bash
   $ sudo systemctl enable apache2
   ```

## 4. Redémarrage / rechargement après modification

Après modification de fichiers de configuration, il est utile de savoir recharger Apache sans couper le service :

   ```bash
   $ sudo systemctl reload apache2   # recharge la configuration sans arrêter le service
   $ sudo systemctl restart apache2  # redémarre complètement Apache
   ```

## 5. Test de fonctionnement avec localhost via navigateur

Pour vérifier que le serveur fonctionne correctement, ouvre un navigateur web et tape : [http://localhost](http://localhost)   
Si tout est correct, tu devrais voir la page par défaut d’Apache (`index.html`) s’afficher. (ATTENTION PLUS TARD CE FICHIER SERAS `index.php`)

## 6. Bonnes pratiques
- Vérifier que tous les fichiers et dossiers du site appartiennent à `www-data` ou ont des permissions suffisantes pour être lus par ce compte.
- Utiliser `chown` et `chmod` pour gérer les droits des fichiers

# Droits d'accès du serveur web

## Pour l'utilisateur `www-data`

- **/var/www/html**  
  - Propriétaire : `www-data:www-data`  
  - Droits : `500` → `dr-x------`  
    - Le **droit d’exécution** permet à `www-data` d’entrer dans le dossier.  
    - Le **droit de lecture** permet à `www-data` de parcourir le dossier et ses sous-dossiers.

- **/var/www/html/sous-dossiers**  
  - Propriétaire : `www-data:www-data`  
  - Droits : `500` → `dr-x------`  
    - Le **droit d’exécution** permet à `www-data` d’entrer dans le dossier.  
    - Le **droit de lecture** permet à `www-data` de parcourir le dossier et ses sous-dossiers.

- **/var/www/html/fichiers**  
  - Propriétaire : `www-data:www-data`  
  - Droits : `400` → `-r--------`  
    - Le **droit de lecture** permet à `www-data` de lire les fichiers.  
    - Les fichiers dans les sous-dossiers ont les mêmes droits.

- **/var/log/apache2/access.log**  
  - Propriétaire : `www-data:www-data`  
  - Droits : `600` → `-rw-------`  
    - Le **droit de lecture** permet à `www-data` de lire le fichier.  
    - Le **droit d’écriture** permet à `www-data` d’écrire dans le fichier.

- **/var/log/apache2/error.log**  
  - Propriétaire : `www-data:www-data`  
  - Droits : `600` → `-rw-------`  
    - Le **droit de lecture** permet à `www-data` de lire le fichier.  
    - Le **droit d’écriture** permet à `www-data` d’écrire dans le fichier.

## Pour l'utilisateur `root`

- **/etc/apache2/ -R**  
  - Propriétaire : `root:root`  
  - Droits : `700` → `drwx------`  
    - Le **droit d’exécution** permet à `root` d’entrer dans le dossier.  
    - Le **droit de lecture** permet à `root` de parcourir le dossier et ses sous-dossiers.  
    - Le **droit d’écriture** permet à `root` d’écrire dans les fichiers.  
  - Les droits sont appliqués **récursivement** sur tous les fichiers et sous-dossiers.

# Configuration du serveur web

Le serveur web Apache utilise un **fichier de configuration de site** (VirtualHost) pour décrire **comment un site web est servi**.  
Dans la configuration actuelle, un seul site web est servi.

---

### Fichiers de configuration du site

- Les configurations des sites **disponibles** sont stockées dans :  
  `/etc/apache2/sites-available/`
- Les configurations des sites **actifs** sont stockées dans :  
  `/etc/apache2/sites-enabled/`

> Cette distinction permet de préparer des sites web dans `sites-available` sans qu’ils soient accessibles, et de n’activer que ceux que l’on souhaite via `sites-enabled`.  
> Les fichiers présents dans `sites-enabled` sont en réalité des **liens symboliques** vers les fichiers dans `sites-available`.

---

### Contenu d’un fichier de configuration

Un fichier de configuration définit notamment :
- le **port d’écoute** du serveur,
- le **répertoire racine du site web**,
- les **fichiers de journaux**,
- le **comportement global du site**.

Dans notre cas :
- 1 fichier de configuration **disponible**
- 1 fichier de configuration **actif**

---

### Note sur le fichier actif

Le fichier de configuration actif est un **lien symbolique** vers un fichier disponible.  
Il faut donc **modifier uniquement le fichier disponible** pour que les changements soient pris en compte.

---

### Configuration du site (`000-default.conf`)

Le fichier `000-default.conf` définit :
- le port d’écoute : **80**
- le répertoire racine du site :
  - `DocumentRoot /var/www/html`
- les fichiers de logs :
  - `/var/log/apache2/access.log`
  - `/var/log/apache2/error.log`

# Description du site web

Le site web est composé de plusieurs fichiers situés dans :

```bash
/var/www/html
```

Le fichier principal, index.php, intègre des fonctionnalités définies dans d’autres fichiers présents dans le même dossier ou dans les sous-dossiers.
Les fichiers décrivant le site sont tous du php.

# Versionnage et suivi du site web

La configuration, le site web, et sa documentation est gérés dans un projet git dans le dossier `serveur_web`.


# ATTENTION !!!

Les droits d'accés sont trés restrectif.
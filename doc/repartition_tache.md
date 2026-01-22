### Explication des taches des 3 étudiant

#### Étudiant 1
<details>
<summary>Tâches</summary>

	- S'occuper de la RTC de la rasberypi
	- S'occuper du module web de la raberypi
	- Conception de l'IHM sur la partie de saisie des paramètres de l'utilisateur
	- Conception de l'IHM sur la partie de visualisation des donnée pour l'utilisateur
	- Sauvegarde des paramètres de l'utilisateur pour une séche
	- Exportation des donnée d'une seche en format CSV depuis l'application web
</details>

<details>
<summary>Tâches détaillées</summary>

### RTC
	Choisir un module RTC compatible avec la Raspberry Pi
	Installer et câbler le module RTC sur la Rasberry Pi
	Configurer la Rasberry Pi pour utiliser la RTC comme source d’heure
	Synchroniser l’heure système avec la RTC au démarrage
	Assurer la synchronisation de l'heure au démarrage
	Assurer la persistance de l’heure après une coupure d’alimentation
	Assurer la fiabilité de l’horodatage sur la durée
	Documenter l’installation, la configuration et de câblage de la RTC
### Module Web
	Installer l’environnement serveur web sur la Raspberry Pi
	Configurer le service web
	Permettre la communication entre le serveur web et la base de données
	Permettre le routage du service web sur inthernet
	Assurer l’accessibilité du service web depuis différents navigateurs
	Assurer la stabilité et les performances du service web
	Documenter la configuration et les instructions pour déployer le module web
### IHM de saisie des paramètres de l'utilisateur
	Définir les paramètres utilisateur nécessaires au processus de séchage
	Concevoir une interface de saisie claire et intuitive
	Mettre en place des contrôles de saisie et des validations de données
	Relier l’interface de saisie à la base de données
	Assurer l'ergonomie et la clarté de l'interface utilisateur
	Assurer la cohérence de saisie des paramètres de l'utilisateur
	Documenter le fonctionnement de l’IHM pour les utilisateurs et les développeurs
### IHM de visualisation des données lors de la séche
	Définir les données à afficher pour l’utilisateur
	Concevoir une interface de visualisation lisible et structurée
	Mettre en place l’affichage dynamique des données en temps réel
	Relier l’interface de saisie à la base de données
	Assurer la lisibilité et la compréhension des informations affichées
	Assurer la clarté et la compréhension des informations affichées
	Assurer l’affichage avec des données réelles
	Documenter l’interface et ses fonctionnalités pour les utilisateurs et les développeurs
### Sauvegarde de saisie des paramètres de l'utilisateur
	Definir la structure de la base de données pour la sauvegarde des paramétre utilisateur
	Enregistrer les paramètres automatiquement dans la base de données
	Gérer l’historique des paramètres des séances de séchage
	Assurer la récupération correcte des paramètres enregistrés
	Assurer la sauvegarde sur plusieurs séances de séchage
	Documenter la procédure de sauvegarde
### Exporation des données d'une séche dans un fichier CSV
	Définir les données à inclure dans le fichier CSV
	Definir la structure de donnée d'une seche d'un fichier CSV
	Générer le fichier CSV via l’application web sous demande de téléchargement
	Permettre le téléchargement du fichier CSV par l’utilisateur
	Assurer l’ouverture et l’exploitation du fichier CSV sur différents outils
	Documenter la procédure d’exportation et les formats supportés
</details>

---
#### Étudiant 2
<details>
<summary>Tâches</summary>

	- Choisir et mettre en œuvre les capteurs
	- Choisir et mettre en œuvre la sirène
	- Choisir et mettre en œuvre les interfaces de puissance pour le séchage et l’alerte sonore
	- Developer le cas d’utilisation Mesurer
	- Developer le cas d’utilisation Alerter dépassement température
	- Developer le cas d’utilisation Chauffer
</details>

<details>
<summary>Tâches détaillées</summary>

### Capteurs
	Les capteurs de température sont à choisir.
	Il faut 6 capteurs de température
	Les capteurs doivent être étanches.
	Les capteurs doivent être peu onéreux. 
	Les capteurs doivent être anti-poussière.
	Les capteurs doivent avoir une longueur de fil environ 5m pour permettre une connexion directe au Raspberry.
### Sirène
	La sirène est à choisir.
	Interface de puissance à choisir dans le catalogue du lycée.
	Elle doit se déclencher lorsque deux mesures successives sont supérieures à 60°C.
### Developer le cas d’utilisation Mesurer
	Les capteurs de température doivent avoir une précision minimum de +/- 0,5°C dans la zone utile pour le contrôle du séchage.
### Alerter  en cas de dépassement  de la température
	L’alerte s’effectuera via un signal sonore.
### Developer le cas d’utilisation Chauffer
	Le pilotage du brûleur doit être effectué en fonction des températures configurées.
	La température moyenne doit être située entre 50 et 55°C.
	Le chauffage démarre quand la température devient inférieure à 50°C.
	Le chauffage s’arrête quand la température devient supérieure à 60°C.
	Il est nécessaire de choisir une commande compatible avec le Raspberry.
### BDD
	Les informations sur les températures, les heures, les commandes ou pas de chauffage, les alertes éventuelles sont sauvegardées dans la BDD.
</details>

---
#### Étudiant 3

---

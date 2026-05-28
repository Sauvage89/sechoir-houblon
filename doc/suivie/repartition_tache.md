### Explication des taches des 3 étudiant

#### Étudiant 1
<details>
<summary>Tâches</summary>

	- S'occuper de la RTC de la rasberypi
	- S'occuper du serveur web de la raberypi
	- S'occuper du serveur bdd de la raberypi
	- Conception de l'IHM sur la partie de saisie des paramètres de l'utilisateur
	- Conception de l'IHM sur la partie de l'exportation des donnée d'une seche en format CSV
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
### Serveur Web
	Installer l’environnement serveur web sur la Raspberry Pi
	Configurer le service web
	Permettre la communication entre le serveur web et la base de données
	Assurer la stabilité et les performances du serveur web
	Documenter la configuration et les instructions pour déployer le serveur web
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
	Documenter les données à afficher
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

	- Choisir et mettre en œuvre les capteurs de température.
	- Choisir et mettre en œuvre la sirène.
	- Choisir et mettre en œuvre les interfaces de puissance pour le séchage et l’alerte sonore.
	- Developper la partie Mesurer.
	- Developper la partie Alerter en cas de dépassement température.
	- Developper le cas d’utilisation Chauffer.
	- Sauvegarder dans la BDD.
</details>

<details>
<summary>Tâches détaillées</summary>

### Capteurs
	Choisir les capteurs de température.
	Prévoir 6 capteurs de température
	Choisir des capteurs de température étanches.
	Choisir des capteurs de température peu cher. 
	Choisir des capteurs anti-poussière.
	Définir la longueur de fil entre les capteurs de température et la Raspberry d'environ 5m.
### Sirène
	Choisir la sirène d'alerte.
	Choisir une interface de puissance dans le catalogue du lycée.
	Déclencher la sirène d'alerte lorsque deux mesures successives sont supérieures à 60°C.
### Mesurer
	Apporter une précision minimum de +/- 0,5°C au capteurs de températures dans la zone utile pour le contrôle du séchage.
	Alerter lorsque deux mesures successives sont inférieures à 50°C (hors-démarrage).
	Démarrer le chauffage quand la température devient inférieure à 50°C. (hors-démarrage)
	Alerter lorsque deux mesures successives sont supérieures à 55°C.
	Stopper Le chauffage quand la température devient supérieure à 60°C. 
### Alerter en cas de dépassement de la température
	Alerter via un signal sonore.	
### Chauffer
	Piloter le brûleur en fonction des températures configurées.
	Contrôler la température moyenne pour qu’elle reste entre 50 et 55 °C.
	Choisir une commande compatible avec le Raspberry.
### BDD
	Sauvegarder les informations sur les températures, les heures, les commandes de chauffage et les alertes éventuelles dans la BDD.
</details>

---
#### Étudiant 3

<details>
<summary>Tâches</summary>
	
	- Contrôler le séchage
	- Alerter en fin de cycle
	- Ajouter la masse de houblon
	- Mise en oeuvre du point d'accès WiFi
	- Mise en oeuvre de l'alerte visuelle
	- Mise en oeuvre et gestion de la base de données
	- Conception de l'IHM sur la partie de visualisation des donnée pour l'utilisateur
	- Développement de l'IHM web (mobile)
	- S'occuper du serveur BDD de la rasberypi
</details>

<details>
<summary>Tâches détaillées</summary>

### Infrastructure & Réseau
	1.1 Point d'accès WiFI
		- Installer le point d'accès WiFi sur le Raspberry Pi 4
		- Paramétrer le réseau (SSID, sécurité, IP)
		- Permettre la connexion directe d'un smartphone au séchoir
		- Tester la stabilité et la portée du réseau

### Alerte visuelle (fin de cycle)
	2.1 Choix du voyant
		- Etudier les solutions possibles (LED haute luminosité, gyrophare, voyant industriel)
		- Vérifier la visibilité à 10 mètres
		- Choisir une technologie adaptée (12V/24V/230V)
	2.2 Interface de puissance
		- Choisir une interface de puissance (relais, optocoupleur, module ToR)
		- Assurer l'isolation entre le Raspberry Pi et le voyant
		- Réaliser le câblage et les tests électriques
	2.3 Pilotage logiciel de l'alerte
		- Déclencher l'alerte visuelle en fin de cycle
		- Gérer l'extinction manuelle ou automatique de l'alerte
		- Tester le fonctionnement complet (simulation de fin cycle)

### Base de donnée (BDD)
	3.1 Conception de la base de donnée
		- Définir les tables: 
			• Cycles de séchage (date, heure début/fin, durée)
			• Températures enregistrées
			• Variétés de houblon
			• Masses produites par variété
			• Etats marche/arrêt
		- Définir les relations entre les tables
	3.2 Mise en oeuvre
		- Installer le SGBD (ex : SQLite / MySQL)
		- Créer les tables et schémas
		- Tester l'insertion et la lecture des données

### Cas d'utilisation : Contrôler le séchage
	4.1 Acquisititon des données
		- Récupérer les températures des 6 capteurs
		- Calculer la température moyenne
		- Lire l'état du chauffage et de la ventilation
		- Gérer l'heure de début et le temps restant du cycle
	4.2 IHM - Page "Contrôler le séchage"
		- Afficher :
			• Les 6 températures
			• La température moyenne
			• L'état chauffage / ventilation
			• L'heure actuelle
			• Le temps restant du cycle
			• Les alertes éventuelles
		- Ajouter :
			• Bouton Arrêter le chauffage
			• Bouton Démarrer le chauffage
		- Adapter l'interface à un affichage mobile
	4.3 Actions utilisateurs
		- Depuis le téléphone:
			• Démarrer le cycle de séchage
			• Arrêter le cycle de séchage
		- Sauvegarder chaque action dans la BDD (date, heure, action)

### Cas d'utilisation : Alerter en fin de cycle
	- Détecter la fin d'un cycle de séchage
	- Déclencher l'alerte visuelle
	- Afficher l'alerte sur l'interface WEB
	- Permettre l'acquittement de l'alerte
	- Enregistrer l'événement dans la BDD

### Cas d'utilisation : Ajouter la masse du houblon
	6.1 IHM - Ajout de masse
		- Créer une page web dédiée
		- Sélection de la varitété de houblon
		- Saisie de la masse produite
		- Validation et confirmation utilisateur
	6.2 Gestion des données
		- Enregistrer les masses dans la base de données
		- Associer chaque masse à une variété
		- Vérifier la cohérence des données saisies

### Visualisation des étages & variétés
	- Afficher sur le téléphone :
		• Les 4 étages du séchoir
		• La variété de houblon présente à chaque étage
	- Mettre à jour l'affichage lors du changement d'étage
	- Associer les étages aux cycles de séchage en cours

### IHM de visualisation des données lors de la séche
	Concevoir une interface de visualisation lisible et structurée
	Mettre en place l’affichage dynamique des données en temps réel
	Relier l’interface de saisie à la base de données
	Assurer la lisibilité et la compréhension des informations affichées
	Assurer la clarté et la compréhension des informations affichées
	Assurer l’affichage avec des données réelles
	Documenter l’interface et ses fonctionnalités pour les utilisateurs et les développeurs

###	Serveur BDD
	Choisir le système de base de données adapté au projet
	Installer et configurer le service de base de données sur la Raspberry Pi
	Définir les conventions de nommage et de stockage
	Assurer la communication entre la base de données et l’application web
	Assurer la connectivité et la fiabilité des échanges de données
	Assurer la fiabilité des échanges de données entre le serveur web et BDD
	Documenter la configuration de la BDD et les conventions de nommage et de stockage

---


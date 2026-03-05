# Documentation de la Base de Données

## Projet : Séchoir à Houblon

## 1. Présentation

> La base de données sert à stocker toutes les informations nécessaires au fonctionnement du système de contrôle du séchage du houblon.

Elle est utilisée par l'utilisateur **www:data**

- ### Objectifs principaux

  - #### Paraphrase

	Lors d’un cycle de séchage, les températures relevées par les différents capteurs sont enregistrées, ainsi que les événements pouvant survenir.  
	À la fin du cycle, la masse de houblon produite est enregistrée en étant associée au cycle correspondant.

  - Les données enregistrées permettent de :  
    - Visualiser les **températures des capteurs**  
    - Suivre les **cycles de séchage**  
    - Enregistrer les **événements**  
    - Stocker la **production de houblon par variété**  

- ### Technologie

  - Base de données : **MySQL**

# 2. Schéma global de la base

| Table			| Description					|
| --------------------- | --------------------------------------------- |
| `cycles_sechage`	| Enregistre les cycles de séchage		|
| `capteurs`		| Informations sur les capteurs installés	|
| `temperatures`	| Stocke les mesures des capteurs		|
| `evenements`		| Enregistre les alertes du système		|
| `masses_houblon`	| Enregistre la masse produite par variété	|

# 3. Table : temperatures

## Description

Cette table enregistre les mesures de température provenant des capteurs présents dans le séchoir.  
Chaque capteur envoie régulièrement une mesure qui est enregistrée avec sa date.

## Structure

| Champ			| Type		| Description			|
| --------------------- | ------------- | ----------------------------- |
| id_temp		| INT (PK)	| Identifiant unique		|
| temp_capteur		| INT (FK)	| Reférence a l'id d'un capteur	|
| temp_valeur		| FLOAT		| Température mesurée		|
| temp_date_mesure	| DATETIME	| Date et heure de la mesure	|

## Exemple de données

| id_temp	| temp_capteur	| temp_valeur	| temp_date_mesure	|
| ------------- | ------------- | ------------- | --------------------- |
| 1		| 4		| 52.3		| 2026-03-05 10:12:00	|
| 2		| 2		| 53.1		| 2026-03-05 10:12:00	|

# 4. Table : cycles_sechage

## Description

Cette table enregistre les cycles de séchage du houblon.  
Un cycle correspond à une période pendant laquelle le système de séchage est actif.

## Structure

| Champ			| Type		| Description			|
| --------------------- | ------------- | ----------------------------- |
| id_cyc_sech		| INT (PK)	| Identifiant du cycle		|
| cyc_sech_date_debut	| DATETIME	| Date et heure de démarrage	|
| cyc_sech_date_fin	| DATETIME	| Date et heure d'arrêt		|

## Exemple de données

| id_cyc_sech	| cyc_sech_date_debut	| cyc_sech_date_fin	|
| ------------- | --------------------- | --------------------- |
| 1		| 2026-03-05 10:12:00	| 2026-03-05 15:12:00	|
| 2		| 2026-15-17 9:20:00	| 2026-15-17 14:20:00	|

# 5. Table : alertes

## Description

Cette table enregistre les alertes générées par le système.

Les alertes peuvent être par exemple :

* fin de cycle de séchage
* température trop élevée
* problème système

## Structure

| Champ       | Type     | Description               |
| ----------- | -------- | ------------------------- |
| id          | INT (PK) | Identifiant de l'alerte   |
| type        | VARCHAR  | Type d'alerte             |
| date_alerte | DATETIME | Date et heure de l'alerte |
| etat        | VARCHAR  | Etat de l'alerte          |

## Exemple

| id | type         | date_alerte         |
| -- | ------------ | ------------------- |
| 1  | Fin de cycle | 2026-03-05 14:00:00 |

---

# 6. Table : masses_houblon

## Description

Cette table permet d’enregistrer la masse produite de chaque variété de houblon à la fin de la saison.

Les données sont saisies par l'utilisateur depuis l'interface web.

## Structure

| Champ       | Type     | Description           |
| ----------- | -------- | --------------------- |
| id          | INT (PK) | Identifiant           |
| variete     | VARCHAR  | Nom de la variété     |
| masse       | FLOAT    | Masse produite        |
| date_saisie | DATETIME | Date d'enregistrement |

## Utilisation

Cette table permet de :

* suivre la production annuelle
* conserver un **historique des récoltes**

---

# 7. Utilisation par l'application

La base de données est utilisée par les scripts PHP du projet :

| Script               | Fonction                                     |
| -------------------- | -------------------------------------------- |
| `get_status.php`     | Récupère les températures et l'état du cycle |
| `start_cycle.php`    | Démarre un cycle de séchage                  |
| `stop_cycle.php`     | Arrête un cycle de séchage                   |
| `add_production.php` | Ajoute une production de houblon             |

Les données sont renvoyées au site web sous forme de **JSON** afin d’être exploitées par le JavaScript.

---

# 8. Perspectives d'amélioration

Améliorations possibles :

* ajout d'une table **capteurs**
* ajout d'un historique détaillé des **cycles de séchage**
* ajout d'une table **utilisateurs**
* archivage des données anciennes

---

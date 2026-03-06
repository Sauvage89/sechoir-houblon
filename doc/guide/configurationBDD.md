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

# 3. Structure des tables de la base

<details>
<summary>Table : cycles_sechage</summary>

## Table : cycles_sechage

### Description

Cette table enregistre les cycles de séchage du houblon.  
Un cycle correspond à une période pendant laquelle le système de séchage est actif.

### Structure

| Champ			| Type			| Description						|
| --------------------- | --------------------- | ----------------------------------------------------- |
| id_cyc_sech		| INT (PK)		| Identifiant du cycle					|
| cyc_sech_date_debut	| DATETIME		| Date et heure de démarrage				|
| cyc_sech_date_fin	| DATETIME (NULL)	| Date et heure d'arrêt (NULL si cycle en cours)	|

### Exemple de données

| id_cyc_sech	| cyc_sech_date_debut	| cyc_sech_date_fin	|
| ------------- | --------------------- | --------------------- |
| 1		| 2026-03-05 10:12:00	| 2026-03-05 15:12:00	|
| 2		| 2026-12-17 09:20:00	| 2026-15-17 14:20:00	|

</details>

<details>
<summary>Table : capteur</summary>

## Table : capteur

### Description

Cette table enregistre les capteurs utilisés par le système.  
Elle sert uniquement de table de référence afin d'identifier les capteurs et d'éviter l'utilisation de valeurs numériques arbitraires dans les tables de mesures.

### Structure

| Champ			| Type		| Description			|
| --------------------- | ------------- | ----------------------------- |
| id_capt		| INT (PK)	| Identifiant unique du capteur	|

### Exemple de données

| id_capt	|
| ------------- |
| 1		|
| 2		|
| 3		|

</details>

<details>
<summary>Table : temperatures</summary>

## Table : temperatures

### Description

Cette table enregistre les mesures de température provenant des capteurs présents dans le séchoir.  
Chaque capteur envoie régulièrement une mesure qui est enregistrée avec sa date et son heure.

### Structure

| Champ			| Type		| Description					|
| --------------------- | ------------- | --------------------------------------------- |
| id_temp		| INT (PK)	| Identifiant unique				|
| temp_capteur		| INT (FK)	| Référence à l'identifiant d'un capteur	|
| temp_valeur		| FLOAT		| Température mesurée				|
| temp_date_mesure	| DATETIME	| Date et heure de la mesure			|

### Exemple de données

| id_temp	| temp_capteur	| temp_valeur	| temp_date_mesure	|
| ------------- | ------------- | ------------- | --------------------- |
| 1		| 4		| 52.3		| 2026-03-05 10:12:00	|
| 2		| 2		| 53.1		| 2026-03-05 10:12:00	|

</details>

<details>
<summary>Table : evenements</summary>

## Table : evenements

### Description

Cette table enregistre les événements générés par le système.  
Un événement peut être lié soit au système lui-même, soit à un composant du système.

### Structure

| Champ		| Type		| Description						|
| ------------- | ------------- | ----------------------------------------------------- |
| id_event	| INT (PK)	| Identifiant de l'evenements				|
| event_type	| VARCHAR	| Type d'evenements					|
| event_date	| DATETIME	| Date et heure de l'evenements				|
| event_src	| INT (FK)	| Identifiant du composant concerné (0 si système)	|

### Exemple

| id_event	| event_type		| event_date		| event_src	|
| ------------- | --------------------- | --------------------- | ------------- |
| 1		| "Fin de cycle"	| 2026-03-05 14:00:00	| 0		|
| 2		| "Demarage du cycle"	| 2026-15-05 14:00:00	| 0		|
| 3		| "Capteur trop chaud"	| 2026-15-05 14:00:00	| 3		|

</details>

<details>
<summary>Table : masses_houblon</summary>

## Table : masses_houblon

### Description

Cette table enregistre la masse produite pour chaque variété de houblon à la fin d'un cycle de séchage.  
Les données sont saisies par l'utilisateur depuis l'interface web.

### Structure

| Champ			| Type		| Description				|
| --------------------- | ------------- | ------------------------------------- |
| id_m_houbl		| INT (PK)	| Identifiant de l'enregistrement	|
| m_houbl_variete	| VARCHAR	| Nom de la variété de houblo		|
| m_houbl_masse		| FLOAT		| Masse produite			|
| m_houbl_date_saisie	| DATETIME	| Date et heure de l'enregistrement	|

</details>

---

# 3. Utilisation par l'application

La base de données est utilisée par les scripts PHP du projet :

| Script               | Fonction                                     |
| -------------------- | -------------------------------------------- |
| `get_status.php`     | Récupère les températures et l'état du cycle |
| `start_cycle.php`    | Démarre un cycle de séchage                  |
| `stop_cycle.php`     | Arrête un cycle de séchage                   |
| `add_production.php` | Ajoute une production de houblon             |

Les données sont renvoyées au site web sous forme de **JSON** afin d’être exploitées par le JavaScript.

# 4. Perspectives d'amélioration

Améliorations possibles :

* ajout d'une table **capteurs**
* ajout d'un historique détaillé des **cycles de séchage**
* ajout d'une table **utilisateurs**
* archivage des données anciennes

---

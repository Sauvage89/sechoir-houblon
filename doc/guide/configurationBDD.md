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

| Table				| Description						|
| ----------------------------- | ----------------------------------------------------- |
| `composants`			| Référence les composants du système			|
| `temperatures`		| Stocke les mesures des capteurs			|
| `evenements`			| Enregistre les evenements du système			|
| `houblon variété`		| Enregistre les variétés d'houblons			|
| `houblon sechage`		| Enregistre les houblons d'une session de séchage	|
| `masses houblon finale`	| Enregistre une masse produite	de houblon		|

# 3. Structure des tables de la base

<details>
<summary>Table : composant</summary>

## Table : compo

### Description

Cette table enregistre les composants du système 
(capteurs, système, etc.).

### Structure

| Champ		| Type		| Description						|
| ------------- | ------------- | ----------------------------------------------------- |
| id_compo	| INT (PK)	| Identifiant unique					|
| compo_type	| VARCHAR	| Nom du type de composante				|

</details>

<details>
<summary>Table : temperatures</summary>

## Table : temp

### Description

Cette table enregistre les mesures de température moyennes des capteurs dans le séchoir.  
Toutes les minutes une messure des capteurs est prise et on enregistre la valeur en moyennant les messures des capteurs.  

### Structure

| Champ			| Type		| Description						|
| --------------------- | ------------- | ----------------------------------------------------- |
| id_temp		| INT (PK)	| Identifiant unique					|
| temp_houb_sech	| INT (FK)	| Référence à l'identifiant d'un houblon de séchage	|
| temp_valeur		| DECIMAL(3,1)	| Température moyenne mesurée				|
| temp_date_mesure	| DATETIME	| Date et heure de la mesure				|

</details>

<details>
<summary>Table : evenements</summary>

## Table : event

### Description

Cette table enregistre les événements générés par le système.  
Un événement peut être lié soit au système lui-même, soit à un composant du système.

### Structure

| Champ			| Type		| Description							|
| --------------------- | ------------- | ------------------------------------------------------------- |
| id_event		| INT (PK)	| Identifiant de l'evenements					|
| event_houb_sech	| INT (FK)	| Référence à l'identifiant d'un houblon de séchage		|
| event_compo		| INT (FK)	| Référence à l'identifiant du composant concerné		|
| event_type		| VARCHAR	| Type d'evenements						|
| event_date		| DATETIME	| Date et heure de l'evenements					|

</details>

<details>
<summary>Table : houblon variété</summary>

## Table : houb_var

### Description

Cette table enregistre les variété de houblon.  

### Structure

| Champ			| Type		| Description			|
| --------------------- | ------------- | ----------------------------- |
| id_houb_var		| INT (PK)	| Identifiant unique du houblon	|
| houb_var_type		| VARCHAR	| Nom de la variété		|
| houb_var_activ	| BOOL		| 1=active, 0=inactif		|

</details>

<details>
<summary>Table : houblon de séchage</summary>

## Table : houb_sech

### Description

Cette table enregistre une variété de houblon d'un cycle de séchage.  
Les données sont saisies par l'utilisateur depuis l'interface web.  
Un houblon de séchage est un "lot de houblon".
Toute l'importance de la BDD ce situe sur cette table la et sur la table `m_houb_final` pour voire la "performance d'une production d'houblon.  

### Structure

| Champ				| Type		| Description						|
| ----------------------------- | ------------- | ----------------------------------------------------- |
| id_houb_sech			| INT (PK)	| Identifiant de l'enregistrement			|
| houb_sech_m_houb_final	| INT (FK/NULL)	| Identifiant sur la masse houblon finale		|
| houb_sech_var			| INT (FK)	| Identifiant de la variete de houblon			|
| houb_sech_etage4		| INT (NULL)	| Temps passé a l'étage 4				|
| houb_sech_etage3		| INT (NULL)	| Temps passé a l'étage 3				|
| houb_sech_etage2		| INT (NULL)	| Temps passé a l'étage 2				|
| houb_sech_etage1		| INT (NULL)	| Temps passé a l'étage 1				|
| houb_sech_date_in		| DATETIME	| Date et heure de l'enregistrement			|
| houb_sech_date_out		| DATETIME	| Date et heure de fin de séchage de l'houblon		|


</details>

<details>
<summary>Table : masses houblon finale</summary>

## Table : m_houb

### Description

Cette table enregistre la masse produite pour une variété de houblon à la fin d'un cycle de séchage.  
Les données sont saisies par l'utilisateur depuis l'interface web.

### Structure

| Champ			| Type		| Description						|
| --------------------- | ------------- | ----------------------------------------------------- |
| id_m_houb		| INT (PK)	| Identifiant de l'enregistrement			|
| m_houb_masse		| DECIMAL(3,2)	| Masse produite					|
| m_houb_date_saisie	| DATETIME	| Date et heure de l'enregistrement			|

</details>

---

# 3. Utilisation par l'application

La base de données est accédée par des scripts PHP.  
Chaque script réalise une fonction spécifique et renvoie les données au site web au format JSON, afin qu’elles puissent être exploitées par le JavaScript.

## Script :

- `get_temperature.php`
  - Récupère les dernières mesures de température de tous les capteurs venant d'un meme cycle de sèchage.  
  → Arguments : aucun.  
  → On cherche jusqu'au 50 dernière prise de température pour ne pas chercher a l'infini. Le retour est donc un JSON NULL.  
  → Retour : liste des dernières températures par capteur, au format JSON.  
  → Exemple retour :  
  ```JSON
  [  
  {"id_capt": 1, "temp_valeur": 52.3, "temp_date_mesure": "2026-03-05 10:12:00"},  
  {"id_capt": 2, "temp_valeur": 53.1, "temp_date_mesure": "2026-03-05 10:12:00"},  
  {"id_capt": 3, "temp_valeur": 51.8, "temp_date_mesure": "2026-03-05 10:12:00"}  
  ]  
  ```

- `start_cycle.php`
  - Démarre un nouveau cycle de séchage.  
  → Arguments : aucun.  
  → Retour : confirmation du démarrage du cycle et identifiant du cycle créé, au format JSON.  
  → Exemple retour :  
  ```JSON
  {
  "id_cyc_sech": 4,
  "status": "Cycle démarré",
  "cyc_sech_date_debut": "2026-03-06 08:00:00"
  }
  ```

- `stop_cycle.php`
  - Arrête le cycle en cours.  
  → Arguments : aucun.  
  → Retour : confirmation de l’arrêt et mise à jour de la date de fin, au format JSON.  
  → Exemple retour :  
  ```JSON
  {
  "id_cyc_sech": 4,
  "status": "Cycle arrêté",
  "cyc_sech_date_debut": "2026-03-06 14:00:00"
  }
  ```

- `add_production.php`
  - Enregistre la masse produite de houblon d'un cycle de sèchage.  
  → Arguments : Arguments : `m_houbl_variete`, `m_houbl_masse`, `id_cyc_sech`.  
  → Retour : confirmation de l’ajout, au format JSON.  
  → Exemple retour :  
  ```JSON
  {
  "id_m_houbl": 7,
  "status": "Production ajoutée",
  "m_houbl_variete": "Cascade",
  "m_houbl_masse": 12.5,
  "m_houbl_date": "2026-03-06 14:00:00"
  }
  ```
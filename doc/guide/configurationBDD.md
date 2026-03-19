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

| Table	naturel				| Description						|
| ------------------------------------- | ----------------------------------------------------- |
| `composant`				| Référence les composants du système			|
| `evenement`				| Enregistre les evenements du système			|
| `temperature`				| Stocke les mesures des capteurs			|
| `houblon etage`			| Enregistre l'état d'un houblon à l'étage donné	|
| `lien evenement houblon etage`	| Table reliant un evenement à un étage			|
| `lien temperature houblon etage`	| Table reliant une température à un étage		|
| `houblon variete`			| Enregistre les variétés de houblon			|
| `houblon lot`				| Enregistre les houblons d'une session de séchage	|
| `houblon final`			| Enregistre une masse produite de houblon		|

# 3. Structure des tables de la base




<details>
<summary>Table naturel : composant</summary>

## Table technique : compo

### Description

Cette table enregistre les composants du système (capteurs, modules système, etc.).  

### Structure

| Champ		| Type		| Description							|
| ------------- | ------------- | ------------------------------------------------------------- |
| id_compo	| INT (PK)	| Identifiant unique du composant				|
| compo_type	| VARCHAR(32)	| Nom du type de composante					|
| compo_actif	| BOOL		| Indique si le composant est active (1) ou inactive (0)	|

</details>




<details>
<summary>Table naturel : evenement</summary>

## Table technique : even

### Description

Cette table enregistre les événements générés par le système.  
Un événement est lié soit à un composant du système via la (FK) `event_compo`.  
Il est également rattaché aux étages actifs du système au moment de l’événement via la table `lienEvenHoubEtag`.  

### Structure

| Champ			| Type		| Description							|
| --------------------- | ------------- | ------------------------------------------------------------- |
| id_even		| INT (PK)	| Identifiant unique de l'événement				|
| even_compo		| INT (FK)	| Référence au composant concerné				|
| even_type		| VARCHAR(128)	| Type d'evenements						|
| even_date		| DATETIME	| Date et heure de l'evenements					|

</details>




<details>
<summary>Table naturel : temperature</summary>

## Table technique : temp

### Description

Cette table enregistre les mesures de température moyennes des 6 capteurs dans le séchoir.  
Toutes les trentres minutes une messure des 6 capteurs est prise et on enregistre la valeur moyenné.  
Les températures sont rattaché aux étages actifs du système au moment de l'enregistrement via la table `lienTempHoubEtag`.  

### Structure

| Champ			| Type		| Description						|
| --------------------- | ------------- | ----------------------------------------------------- |
| id_temp		| INT (PK)	| Identifiant unique					|
| temp_valeur		| DECIMAL(3,1)	| Température moyenne mesurée				|
| temp_date		| DATETIME	| Date et heure de la mesure				|

</details>




<details>
<summary>Table naturel : houblon etage</summary>

## Table technique : houbEtag

### Description

Cette table représente la position d’un lot de houblon dans le séchoir et sert de contexte pour associer les événements et les mesures à un étage précis du houblon lot.  
Un étage houblon est lié à son houblon lot via la (FK) `houbEtag_houbLot`.  
Le lien `houblon etage` <-> `evenements`/`temperatures` sont faite par les tables `lienEvenHoubEtag`/`lienTempHoubEtag`.  

### Structure

| Champ			| Type		| Description							|
| --------------------- | ------------- | ------------------------------------------------------------- |
| id_houbEtag		| INT (PK)	| Identifiant unique de l’enregistrement 			|
| houbEtag_houbLot	| INT (FK)	| Identifiant du lot de houblon					|
| houbEtag_etage	| BYTE		| Numéro de l’étage dans le séchoir (1 à 4) 			|
| houbEtag_duree	| INT (NULL) 	| Durée passée à cet étage					|
| houbEtag_actif	| BOOL		| Indique si le lot est actuellement présent à cet étage	|

</details>




<details>
<summary>Table naturel : lien evenement houblon etage</summary>

## Table technique : lienEvenHoubEtag

### Description

Table d’association entre un **étage de houblon** et un **événement**.  
Elle permet de rattacher un événement à l’étage précis où se trouvait le houblon au moment de cet événement.  
Les (FK) sont explicite.  

### Structure

| Champ				| Type		| Description				|
| ----------------------------- | ------------- | ------------------------------------- |
| lienEvenHoubEtag_houbEtag	| INT (FK)	| Identifiant de l’étage de houblon 	|
| lienEvenHoubEtag_even		| INT (FK)	| Identifiant de l’événement associé	|

</details>




<details>
<summary>Table naturel : lien temperature houblon etage</summary>

## Table technique : lienTempHoubEtag

### Description

Table d’association entre un **étage de houblon** et une **mesure de température**.  
Elle permet de rattacher une mesure de température à l’étage précis où se trouvait le houblon au moment de la mesure.  
Les (FK) sont explicite.  

### Structure

| Champ				| Type		| Description						|
| ----------------------------- | ------------- | ----------------------------------------------------- |
| lienTempHoubEtag_houbEtag	| INT (FK)	| Identifiant de l’étage de houblon 			|
| lienTempHoubEtag_temp		| INT (FK)	| Identifiant de la mesure de température associé	|

</details>




<details>
<summary>Table naturel : houblon lot</summary>

## Table technique : houbLot

### Description

Cette table représente un lot de houblon.  
Les données sont saisies par l’utilisateur via l’interface web.  
Le lot houblon constitue une parie de la masse finale d'une production de houblon en étant relié via la (FK) `houbLot_houbFinal`, cette (FK) est nulle tant qu'on a pas de masse d'houblon finale mesuré.  
La variété de l'houblon est donné par la (FK) `houbLot_houbVar`.  

Elle constitue la table centrale du système, car elle permet de suivre un cycle complet de production et d’évaluer la performance des lots participant aux différentes productions finales de houblon.  

### Structure

| Champ				| Type			| Description						|
| ----------------------------- | --------------------- | ----------------------------------------------------- |
| id_houbLot			| INT (PK)		| Identifiant unique du lot				|
| houbLot_houbFinal		| INT (FK/NULL)		| Référence à la production finale			|
| houbLot_houbVar		| INT (FK)		| Référence à la variété de houblon			|
| houbLot_dateDebut		| DATETIME		| Date et heure de début du lot				|
| houbLot_dateFin		| DATETIME (NULL)	| Date et heure de fin du lot				|

</details>




<details>
<summary>Table naturel : houblon variete</summary>

## Table technique : houbVar

### Description

Cette table enregistre les variétés de houblon disponibles et non-disponibles dans le système.  

### Structure

| Champ			| Type		| Description							|
| --------------------- | ------------- | ------------------------------------------------------------- |
| id_houbVar		| INT (PK)	| Identifiant unique de la variété				|
| houbVar_type		| VARCHAR(32)	| Nom de la variété de houblon					|
| houbVar_activ		| BOOL		| Indique si la variété est active (1) ou inactive (0)		|

</details>




<details>
<summary>Table naturel : houblon final</summary>

## Table technique : houbFinal

### Description

Cette table enregistre la masse de houblon produite à partir de plusieurs lots de houblon.  
Les données sont saisies par l’utilisateur via l’interface web.  

### Structure

| Champ				| Type		| Description						|
| ----------------------------- | ------------- | ----------------------------------------------------- |
| id_houbFinal			| INT (PK)	| Identifiant unique de la masse			|
| houbFinal_masse		| DECIMAL(3,2)	| Masse produite					|
| houbFinal_date		| DATETIME	| Date et heure de saisie de la mesure			|

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

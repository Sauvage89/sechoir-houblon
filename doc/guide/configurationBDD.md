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
| `session de sechage`		| Enregistre les session de séchage			|
| `capteur`			| Informations sur les capteurs installés		|
| `temperatures`		| Stocke les mesures des capteurs			|
| `evenements`			| Enregistre les evenements du système			|
| `houblon variété`		| Enregistre les variétés d'houblons			|
| `houblon sechage`		| Enregistre les houblons d'une session de séchage	|
| `masses houblon finale`	| Enregistre une masse produite	de houblon		|

# 3. Structure des tables de la base

<details>
<summary>Table : session_sechage</summary>

## Table : ses_sech

### Description

Cette table enregistre les sessions de séchage du houblon.  
Une sesions correspond à une période pendant laquelle le système de séchage est actif.

### Structure

| Champ			| Type			| Description						|
| --------------------- | --------------------- | ----------------------------------------------------- |
| id_ses_sech		| INT (PK)		| Identifiant du cycle					|
| ses_sech_date_debut	| DATETIME		| Date et heure de démarrage				|
| ses_sech_date_fin	| DATETIME (NULL)	| Date et heure d'arrêt (NULL si cycle en cours)	|

### Exemple de données

| id_ses_sech	| ses_sech_date_debut	| ses_sech_date_fin	|
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

| Champ			| Type		| Description						|
| --------------------- | ------------- | ----------------------------------------------------- |
| id_temp		| INT (PK)	| Identifiant unique					|
| temp_ses_sech		| INT (FK)	| Référence à l'identifiant d'une session de sèche	|
| temp_capteur		| INT (FK)	| Référence à l'identifiant d'un capteur		|
| temp_valeur		| FLOAT		| Température mesurée					|
| temp_date_mesure	| DATETIME	| Date et heure de la mesure				|

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

| Champ			| Type		| Description							|
| --------------------- | ------------- | ------------------------------------------------------------- |
| id_event		| INT (PK)	| Identifiant de l'evenements					|
| event_ses_sech	| INT (FK)	| Référence à l'identifiant d'une session de sèche		|
| event_src		| INT (FK)	| Identifiant du composant concerné (0 si système)		|
| event_type		| VARCHAR	| Type d'evenements						|
| event_date		| DATETIME	| Date et heure de l'evenements					|

### Exemple

| id_event	| event_type		| event_date		| event_src	|
| ------------- | --------------------- | --------------------- | ------------- |
| 1		| "Fin de cycle"	| 2026-03-05 14:00:00	| 0		|
| 2		| "Demarage du cycle"	| 2026-15-05 14:00:00	| 0		|
| 3		| "Capteur trop chaud"	| 2026-15-05 14:00:00	| 3		|

</details>

<details>
<summary>Table : houblon variété</summary>

## Table : houb_var

### Description

Cette table enregistre les capteurs utilisés par le système.  
Elle sert uniquement de table de référence afin d'identifier les capteurs et d'éviter l'utilisation de valeurs numériques arbitraires dans les tables de mesures.

### Structure

| Champ			| Type		| Description			|
| --------------------- | ------------- | ----------------------------- |
| id_houb_var		| INT (PK)	| Identifiant unique du capteur	|
| houb_var_type		| VARCHAR	| Nom de la variété		|

</details>

<details>
<summary>Table : houblon de séchage</summary>

## Table : houb_sech

### Description

Cette table enregistre une variété de houblon d'un cycle de séchage.  
Les données sont saisies par l'utilisateur depuis l'interface web.

### Structure

| Champ				| Type		| Description						|
| ----------------------------- | ------------- | ----------------------------------------------------- |
| id_houb_sech			| INT (PK)	| Identifiant de l'enregistrement			|
| houb_sech_m_houbl_final	| INT (FK/NULL)	| Identifiant sur la masse houblon finale		|
| houb_sech_ses_sech		| INT (FK)	| Identifiant d'une session sèche			|
| houb_sech_variete		| INT (FK)	| Identifiant de la variete de houblon			|
| houb_sech_etage		| INT (NULL)	| Quelle étage est cette variete			|
| houb_sech_date_in		| DATETIME	| Date et heure de l'enregistrement			|
| houb_sech_date_out		| DATETIME	| Date et heure de retirement				|

</details>


<details>
<summary>Table : masses houblon finale</summary>

## Table : m_houb_final

### Description

Cette table enregistre la masse produite pour une variété de houblon à la fin d'un cycle de séchage.  
Les données sont saisies par l'utilisateur depuis l'interface web.

### Structure

| Champ			| Type		| Description						|
| --------------------- | ------------- | ----------------------------------------------------- |
| id_m_houbl_final	| INT (PK)	| Identifiant de l'enregistrement			|
| m_houbl_masse		| FLOAT		| Masse produite					|
| m_houbl_date_saisie	| DATETIME	| Date et heure de l'enregistrement			|

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
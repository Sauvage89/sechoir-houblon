# Documentation de la Base de Données

## Projet : Séchoir à Houblon

---

## 1. Présentation

> La base de données sert à stocker toutes les informations nécessaires au fonctionnement du système de contrôle du séchage du houblon.

- ### Objectifs principaux

  - #### Paraphrase

    Lors d'un cycle de séchage, les températures relevées par les différents capteurs sont enregistrées, ainsi que les événements pouvant survenir.  
    À la fin du cycle, la masse de houblon produite est enregistrée en étant associée au lot correspondant.

  - Les données enregistrées permettent de :
    - Visualiser les **températures des capteurs**
    - Suivre les **lots de houblon par étage**
    - Enregistrer les **événements système**
    - Stocker la **production de houblon par variété**
    - Surveiller l'**état du séchoir**

- ### Technologie

  - Base de données : **MySQL**

---

# 2. Schéma global de la base

| Table naturelle   | Table technique | Description                                              |
| ----------------- | --------------- | -------------------------------------------------------- |
| `variete`         | `variete`       | Enregistre les variétés de houblon                       |
| `masse`           | `masse`         | Enregistre les masses produites de houblon               |
| `etage`           | `etage`         | Référence les étages physiques du séchoir                |
| `etatSechoir`     | `etatSechoir`   | Enregistre l'état courant du séchoir                     |
| `compo`           | `compo`         | Référence les composants du système                      |
| `evenement`       | `evenement`     | Enregistre les événements du système                     |
| `lot`             | `lot`           | Enregistre les lots de houblon d'une session de séchage  |
| `temperature`     | `temperature`   | Stocke les mesures de température des capteurs           |
| `lotEtage`        | `lotEtage`      | Table de liaison entre un lot et un étage                |

---

# 3. Structure des tables de la base




<details>
<summary>Table : variete</summary>

## Table technique : variete

### Description

Cette table enregistre les variétés de houblon disponibles et non-disponibles dans le système.

### Structure

| Champ                  | Type         | Description                                             |
| ---------------------- | ------------ | ------------------------------------------------------- |
| id_variete             | INT (PK)     | Identifiant unique de la variété                        |
| variete_nom            | VARCHAR(32)  | Nom de la variété de houblon                            |
| variete_dateCreation   | DATETIME     | Date et heure de création de la variété                 |
| variete_actif          | BOOLEAN      | Indique si la variété est active (1) ou inactive (0)    |

### Stratégie d'enregistrement

	On enregistre les diverses variétés de houblon actives pour l'année.  
	On garde toujours les autres variétés pour un souci de correspondance avec les autres tables (notamment `lot`).  
	Si une variété n'est plus utilisée, on la désactive (`variete_actif = 0`) sans la supprimer.

</details>




<details>
<summary>Table : masse</summary>

## Table technique : masse

### Description

Cette table enregistre les masses de houblon produites à l'issue d'un ou plusieurs lots de séchage.  
Les données sont saisies par l'utilisateur via l'interface web.

### Structure

| Champ            | Type          | Description                              |
| ---------------- | ------------- | ---------------------------------------- |
| id_masse         | INT (PK)      | Identifiant unique de la masse           |
| masse_masse      | DECIMAL(3,2)  | Masse produite (en kg)                   |
| masse_dateHeure  | DATETIME      | Date et heure de saisie de la mesure     |

### Stratégie d'enregistrement

	Quand l'agriculteur le souhaite, il peut enregistrer une masse de houblon finale.  
	Un enregistrement `masse` est référencé par un ou plusieurs `lot` via la FK `id_masse`.

</details>




<details>
<summary>Table : etage</summary>

## Table technique : etage

### Description

Cette table référence les étages physiques du séchoir.  
Chaque étage est une position fixe dans le séchoir où un lot de houblon peut être placé.

### Structure

| Champ       | Type     | Description                            |
| ----------- | -------- | -------------------------------------- |
| id_etage    | INT (PK) | Identifiant unique de l'étage          |
| etage_num   | TINYINT  | Numéro de l'étage dans le séchoir      |

### Stratégie d'enregistrement

	Les étages sont des données statiques représentant la configuration physique du séchoir.  
	Ils sont créés une seule fois lors de l'initialisation du système et ne sont pas modifiés en fonctionnement normal.

</details>




<details>
<summary>Table : etatSechoir</summary>

## Table technique : etatSechoir

### Description

Cette table enregistre l'état courant du séchoir.  
Elle permet de savoir si le séchoir est en fonctionnement, en pause, ou à l'arrêt, et de gérer les ajustements de durée liés aux pauses.

### Structure

| Champ                    | Type         | Description                                                       |
| ------------------------ | ------------ | ----------------------------------------------------------------- |
| id_etatSechoir           | INT (PK)     | Identifiant unique de l'enregistrement d'état                     |
| etatSechoir_status       | VARCHAR(32)  | Statut actuel du séchoir (ex : `en cours`, `pause`, `arrêté`)     |
| etatSechoir_dataMaj      | DATETIME     | Date et heure de la dernière mise à jour du statut                |
| etatSechoir_pauseDebut   | DATETIME     | Date et heure du début de la pause en cours (NULL si pas en pause)|
| etatSechoir_ajoutMinute  | SMALLINT     | Nombre de minutes cumulées à ajouter suite aux pauses             |

### Stratégie d'enregistrement

	Un seul enregistrement actif est attendu à la fois dans cette table, représentant l'état courant du séchoir.  
	Lors d'une mise en pause, `etatSechoir_pauseDebut` est renseigné avec le moment du début de la pause.  
	À la reprise, la durée de la pause est calculée et ajoutée à `etatSechoir_ajoutMinute` pour compenser le temps perdu dans la durée totale de séchage.

</details>




<details>
<summary>Table : compo</summary>

## Table technique : compo

### Description

Cette table enregistre les composants du système (capteurs, modules système, etc.).

### Structure

| Champ       | Type         | Description                                                   |
| ----------- | ------------ | ------------------------------------------------------------- |
| id_compo    | INT (PK)     | Identifiant unique du composant                               |
| compo_type  | VARCHAR(32)  | Nom du type de composant                                      |
| compo_actif | BOOLEAN      | Indique si le composant est actif (1) ou inactif (0)          |

### Stratégie d'enregistrement

	On enregistre tous les composants impactants du système dans cette table.  
	Si le composant est actif dans le système, `compo_actif` doit être à (1).  
	Si le composant n'est plus actif, il faut le conserver en base (pour assurer la cohérence des logs passés) et mettre `compo_actif` à (0) pour qu'il ne soit plus pris en compte comme élément actif par le système.

</details>




<details>
<summary>Table : evenement</summary>

## Table technique : evenement

### Description

Cette table enregistre les événements générés par le système.  
Un événement est obligatoirement lié à un composant du système via la (FK) `id_compo`.

### Structure

| Champ                    | Type          | Description                                   |
| ------------------------ | ------------- | --------------------------------------------- |
| id_evenement             | INT (PK)      | Identifiant unique de l'événement             |
| evenement_type           | VARCHAR(64)   | Type d'événement                              |
| evenement_description    | VARCHAR(255)  | Description détaillée de l'événement (optionnel) |
| evenement_dateHeure      | DATETIME      | Date et heure de l'événement                  |
| id_compo                 | INT (FK)      | Référence au composant ayant généré l'événement |

### Stratégie d'enregistrement

	On enregistre les événements qui surviennent dans le système.  
	Un événement provient forcément d'un composant, il faut donc renseigner quel composant a généré cet événement via `id_compo`.  
	Il est recommandé de définir une base de valeurs `evenement_type` conventionnées pour assurer la cohérence des données.  
	Le champ `evenement_description` est optionnel et permet d'ajouter des détails supplémentaires si nécessaire.

</details>




<details>
<summary>Table : lot</summary>

## Table technique : lot

### Description

Cette table représente un lot de houblon introduit dans le séchoir pour une session de séchage.  
Les données sont saisies par l'utilisateur via l'interface web.  
Un lot est lié à une variété de houblon via la (FK) `id_variete`.  
Il est également lié à une masse produite finale via la (FK) `id_masse`, renseignée à la fin du cycle.  

Elle constitue la table centrale du système, car elle permet de suivre un cycle complet de production et d'évaluer la performance des lots.

### Structure

| Champ            | Type           | Description                                        |
| ---------------- | -------------- | -------------------------------------------------- |
| id_lot           | INT (PK)       | Identifiant unique du lot                          |
| lot_remplissage  | DECIMAL(3,2)   | Taux de remplissage du lot (ex : quantité initiale)|
| lot_dateDebut    | DATETIME       | Date et heure de début du lot                      |
| lot_dateFin      | DATETIME (NULL)| Date et heure de fin du lot (NULL si en cours)     |
| lot_actif        | BOOLEAN        | Indique si le lot est actuellement en cours (1)    |
| id_masse         | INT (FK)       | Référence à la masse produite finale               |
| id_variete       | INT (FK)       | Référence à la variété de houblon                  |

### Stratégie d'enregistrement

	Un lot de houblon est créé au moment où l'agriculteur introduit une variété dans un étage du séchoir.  
	À la création, `lot_actif` est mis à (1) et `lot_dateFin` reste NULL.  
	À la fin du séchage, `lot_dateFin` est renseigné et `lot_actif` passe à (0).  
	La FK `id_masse` est renseignée une fois la masse finale pesée et enregistrée dans la table `masse`.

</details>




<details>
<summary>Table : temperature</summary>

## Table technique : temperature

### Description

Cette table enregistre les mesures de température effectuées par les capteurs du séchoir.  
Un capteur est référencé via la (FK) `id_compo`.

### Structure

| Champ                  | Type          | Description                                        |
| ---------------------- | ------------- | -------------------------------------------------- |
| id_temperature         | INT (PK)      | Identifiant unique de la mesure                    |
| temperature_valeur     | DECIMAL(5,2)  | Valeur de la température mesurée (en °C)           |
| temperature_dateHeure  | DATETIME      | Date et heure de la mesure                         |
| id_compo               | INT (FK)      | Référence au capteur ayant effectué la mesure      |

### Stratégie d'enregistrement

	Les températures sont sauvegardées à intervalles réguliers par un programme automatique.  
	Chaque mesure est associée au capteur qui l'a relevée via `id_compo`.  
	Le champ `temperature_valeur` utilise un format `DECIMAL(5,2)` pour permettre des valeurs précises (ex : 52.30°C).

</details>




<details>
<summary>Table : lotEtage</summary>

## Table technique : lotEtage

### Description

Table d'association entre un **lot de houblon** et un **étage du séchoir**.  
Elle permet de tracer le passage d'un lot à travers les différents étages du séchoir au fil du temps.  
La clé primaire est composée de (`id_lot`, `id_etage`), ce qui signifie qu'un lot ne peut être enregistré qu'une seule fois par étage.

### Structure

| Champ               | Type           | Description                                                 |
| ------------------- | -------------- | ----------------------------------------------------------- |
| id_lot              | INT (FK/PK)    | Identifiant du lot de houblon                               |
| id_etage            | INT (FK/PK)    | Identifiant de l'étage du séchoir                           |
| lotEtage_dateDebut  | DATETIME       | Date et heure d'arrivée du lot à cet étage                  |
| lotEtage_dateFin    | DATETIME (NULL)| Date et heure de départ du lot de cet étage (NULL si actif) |

### Stratégie d'enregistrement

	Lors de la création d'un lot, un enregistrement `lotEtage` est créé simultanément pour indiquer l'étage initial du lot, avec `lotEtage_dateFin` à NULL.  
	Lors d'un changement d'étage :  
		`lotEtage_dateFin` de l'enregistrement courant est renseigné avec la date du changement.  
		Un nouvel enregistrement `lotEtage` est créé pour le nouvel étage, avec `lotEtage_dateFin` à NULL.  
	Lorsqu'un lot quitte définitivement le séchoir, `lotEtage_dateFin` du dernier enregistrement est renseigné.  
	La PK composite (`id_lot`, `id_etage`) empêche qu'un même lot soit enregistré deux fois pour un même étage.

</details>




---

# 4. Utilisation par l'application

La base de données est accédée par des scripts PHP.  
Chaque script réalise une fonction spécifique et renvoie les données au site web au format JSON, afin qu'elles puissent être exploitées par le JavaScript.

## Scripts :

- `get_temperature.php`
  - Récupère les dernières mesures de température de tous les capteurs actifs.  
  → Arguments : aucun.  
  → Retour : liste des dernières températures par capteur, au format JSON.  
  → Exemple retour :
  ```json
  [
    {"id_compo": 1, "temperature_valeur": 52.30, "temperature_dateHeure": "2026-03-05 10:12:00"},
    {"id_compo": 2, "temperature_valeur": 53.10, "temperature_dateHeure": "2026-03-05 10:12:00"},
    {"id_compo": 3, "temperature_valeur": 51.80, "temperature_dateHeure": "2026-03-05 10:12:00"}
  ]
  ```

- `get_etat_sechoir.php`
  - Récupère l'état actuel du séchoir.  
  → Arguments : aucun.  
  → Retour : statut du séchoir et informations associées, au format JSON.  
  → Exemple retour :
  ```json
  {
    "id_etatSechoir": 1,
    "etatSechoir_status": "en cours",
    "etatSechoir_dataMaj": "2026-03-06 08:00:00",
    "etatSechoir_pauseDebut": null,
    "etatSechoir_ajoutMinute": 0
  }
  ```

- `start_lot.php`
  - Crée un nouveau lot de houblon et l'associe à un étage de départ.  
  → Arguments : `id_variete`, `lot_remplissage`, `id_etage`.  
  → Retour : confirmation de la création du lot et identifiant du lot créé, au format JSON.  
  → Exemple retour :
  ```json
  {
    "id_lot": 5,
    "status": "Lot démarré",
    "lot_dateDebut": "2026-03-06 08:00:00"
  }
  ```

- `stop_lot.php`
  - Arrête un lot en cours et renseigne sa date de fin.  
  → Arguments : `id_lot`.  
  → Retour : confirmation de l'arrêt du lot, au format JSON.  
  → Exemple retour :
  ```json
  {
    "id_lot": 5,
    "status": "Lot arrêté",
    "lot_dateFin": "2026-03-06 14:00:00"
  }
  ```

- `add_masse.php`
  - Enregistre une masse de houblon produite et la lie aux lots concernés.  
  → Arguments : `masse_masse`, `ids_lot[]`.  
  → Retour : confirmation de l'ajout de la masse, au format JSON.  
  → Exemple retour :
  ```json
  {
    "id_masse": 3,
    "status": "Masse ajoutée",
    "masse_masse": 12.50,
    "masse_dateHeure": "2026-03-06 14:30:00"
  }
  ```

- `change_etage.php`
  - Enregistre le passage d'un lot vers un nouvel étage du séchoir.  
  → Arguments : `id_lot`, `id_etage_nouveau`.  
  → Retour : confirmation du changement d'étage, au format JSON.  
  → Exemple retour :
  ```json
  {
    "id_lot": 5,
    "id_etage": 2,
    "status": "Étage mis à jour",
    "lotEtage_dateDebut": "2026-03-06 10:00:00"
  }
  ```
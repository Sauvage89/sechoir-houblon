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
    - Enregistrer les **pauses du séchoir**
    - Stocker la **production de houblon par variété**
    - Surveiller l'**état du séchoir**

- ### Technologie

  - Base de données : **MySQL**

---

# 2. Schéma global de la base

| Table naturelle   | Table technique | Description                                             |
| ----------------- | --------------- | ------------------------------------------------------- |
| `pause`           | `pause`         | Enregistre les pauses du séchoir                        |
| `variete`         | `variete`       | Enregistre les variétés de houblon                      |
| `masse finale`    | `masse`         | Enregistre les masses produites de houblon              |
| `etage`           | `etage`         | Référence les étages physiques du séchoir               |
| `etat sechoir`    | `etatSechoir`   | Enregistre l'état courant du séchoir                    |
| `capteur`         | `capteur`       | Référence les capteurs de température du système        |
| `lot`             | `lot`           | Enregistre les lots de houblon d'une session de séchage |
| `temperature`     | `temperature`   | Stocke les mesures de température des capteurs          |
| `occuper`         | `lotEtage`      | Table de liaison entre un lot et un étage               |

---

# 3. Structure des tables de la base




<details>
<summary>Table : pause</summary>

## Table technique : pause

### Description

Cette table enregistre les pauses survenues durant le fonctionnement du séchoir.  
Chaque pause est typée et horodatée, avec un début et une fin optionnelle.

### Structure

| Champ                  | Type         | Description                                              |
| ---------------------- | ------------ | -------------------------------------------------------- |
| id_pause               | INT (PK)     | Identifiant unique de la pause                           |
| pause_type             | VARCHAR(64)  | Type de pause (ex : `manuelle`, `alarme`, etc.)          |
| pause_dateHeureDebut   | DATETIME     | Date et heure du début de la pause                       |
| pause_dateHeureFin     | DATETIME     | Date et heure de fin de la pause (NULL si en cours)      |

### Stratégie d'enregistrement

	Lors d'une mise en pause du séchoir, un enregistrement `pause` est créé avec `pause_dateHeureDebut` renseigné et `pause_dateHeureFin` à NULL.  
	À la reprise du séchoir, `pause_dateHeureFin` est renseigné avec le moment de la reprise.  
	Le champ `pause_type` permet de catégoriser la nature de la pause pour une meilleure traçabilité.

</details>




<details>
<summary>Table : variete</summary>

## Table technique : variete

### Description

Cette table enregistre les variétés de houblon disponibles et non-disponibles dans le système.

### Structure

| Champ                    | Type         | Description                                             |
| ------------------------ | ------------ | ------------------------------------------------------- |
| id_variete               | INT (PK)     | Identifiant unique de la variété                        |
| variete_nom              | VARCHAR(32)  | Nom de la variété de houblon                            |
| variete_dateHeureCreation| DATETIME     | Date et heure de création de la variété                 |
| variete_actif            | BOOLEAN      | Indique si la variété est active (1) ou inactive (0)    |

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
| masse_masse      | DECIMAL(4,1)  | Masse produite (en kg)                   |
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

### Stratégie d'enregistrement

	Les étages sont des données statiques représentant la configuration physique du séchoir.  
	Ils sont créés une seule fois lors de l'initialisation du système et ne sont pas modifiés en fonctionnement normal.

</details>




<details>
<summary>Table : etat sechoir</summary>

## Table technique : etatSechoir

### Description

Cette table enregistre l'état courant du séchoir.  
Elle permet de savoir si le séchoir est en fonctionnement, en pause, ou à l'arrêt, et de gérer les ajustements de durée liés aux pauses.

### Structure

| Champ                    | Type         | Description                                                        |
| ------------------------ | ------------ | ------------------------------------------------------------------ |
| id_etatSechoir           | INT (PK)     | Identifiant unique de l'enregistrement d'état                      |
| etatSechoir_status       | VARCHAR(32)  | Statut actuel du séchoir (ex : `en cours`, `pause`, `arrêté`)      |
| etatSechoir_dataMaj      | DATETIME     | Date et heure de la dernière mise à jour du statut                 |
| etatSechoir_pauseDebut   | DATETIME     | Date et heure du début de la pause en cours (NULL si pas en pause) |
| etatSechoir_ajoutMinute  | SMALLINT     | Nombre de minutes cumulées à ajouter suite aux pauses              |

### Stratégie d'enregistrement

	Un seul enregistrement actif est attendu à la fois dans cette table, représentant l'état courant du séchoir.  
	Lors d'une mise en pause, `etatSechoir_pauseDebut` est renseigné avec le moment du début de la pause.  
	À la reprise, la durée de la pause est calculée et ajoutée à `etatSechoir_ajoutMinute` pour compenser le temps perdu dans la durée totale de séchage.

</details>




<details>
<summary>Table : capteur</summary>

## Table technique : capteur

### Description

Cette table enregistre les capteurs de température du système.  
Chaque capteur est identifié par son adresse physique unique (ex : adresse 1-Wire pour les sondes DS18B20).

### Structure

| Champ            | Type         | Description                                              |
| ---------------- | ------------ | -------------------------------------------------------- |
| addresse_capteur | VARCHAR(32)  | Adresse physique unique du capteur (clé primaire)        |
| capteur_nom      | VARCHAR(32)  | Nom lisible du capteur                                   |
| capteur_gpio     | VARCHAR(8)   | Broche GPIO associée au capteur                          |
| capteur_actif    | BOOLEAN      | Indique si le capteur est actif (1) ou inactif (0)       |

### Stratégie d'enregistrement

	On enregistre tous les capteurs de température du système dans cette table.  
	Si le capteur est actif dans le système, `capteur_actif` doit être à (1).  
	Si le capteur n'est plus actif, il faut le conserver en base (pour assurer la cohérence des relevés passés) et mettre `capteur_actif` à (0) pour qu'il ne soit plus pris en compte comme capteur actif par le système.

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

| Champ                | Type           | Description                                         |
| -------------------- | -------------- | --------------------------------------------------- |
| id_lot               | INT (PK)       | Identifiant unique du lot                           |
| lot_remplissage      | TINYINT        | Taux de remplissage du lot (en %)                   |
| lot_dateHeureEntree  | DATETIME       | Date et heure de début du lot                       |
| lot_dateHeureSortie  | DATETIME (NULL)| Date et heure de fin du lot (NULL si en cours)      |
| lot_dureeTheorique   | INT            | Durée théorique de séchage prévue (en minutes)      |
| lot_actif            | BOOLEAN        | Indique si le lot est actuellement en cours (1)     |
| id_masse             | INT (FK)       | Référence à la masse produite finale                |
| id_variete           | INT (FK)       | Référence à la variété de houblon                   |

### Stratégie d'enregistrement

	Un lot de houblon est créé au moment où l'agriculteur introduit une variété dans un étage du séchoir.  
	À la création, `lot_actif` est mis à (1) et `lot_dateHeureSortie` reste NULL.  
	À la fin du séchage, `lot_dateHeureSortie` est renseigné et `lot_actif` passe à (0).  
	La FK `id_masse` est renseignée une fois la masse finale pesée et enregistrée dans la table `masse`.  
	Le champ `lot_dureeTheorique` permet de comparer la durée réelle au plan de séchage initial.

</details>




<details>
<summary>Table : temperature</summary>

## Table technique : temperature

### Description

Cette table enregistre les mesures de température effectuées par les capteurs du séchoir.  
Un capteur est référencé via la (FK) `addresse_capteur`.

### Structure

| Champ                  | Type          | Description                                        |
| ---------------------- | ------------- | -------------------------------------------------- |
| id_temperature         | INT (PK)      | Identifiant unique de la mesure                    |
| temperature_valeur     | DECIMAL(3,1)  | Valeur de la température mesurée (en °C)           |
| temperature_dateHeure  | DATETIME      | Date et heure de la mesure                         |
| addresse_capteur       | VARCHAR(32)   | Référence au capteur ayant effectué la mesure (FK) |

### Stratégie d'enregistrement

	Les températures sont sauvegardées à intervalles réguliers par un programme automatique.  
	Chaque mesure est associée au capteur qui l'a relevée via `addresse_capteur`.  
	Le champ `temperature_valeur` utilise un format `DECIMAL(2,1)` pour des valeurs avec une décimale (ex : 52.3°C).

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
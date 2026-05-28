# Documentation de la Base de Données

## Projet : Séchoir à Houblon

---

# 1. Présentation

> La base de données sert à stocker toutes les informations nécessaires au fonctionnement du système automatisé de séchage du houblon.

- ### Objectifs principaux

  - #### Paraphrase

    Lors d'un cycle de séchage, les températures relevées par les capteurs sont enregistrées automatiquement, ainsi que les événements et états du séchoir.  
    À la fin du cycle, les masses produites sont associées aux lots correspondants afin de conserver un historique complet de production.

  - Les données enregistrées permettent de :

    - Visualiser les **températures des capteurs**
    - Gérer les **lots de houblon**
    - Suivre les **étages utilisés**
    - Enregistrer les **pauses du séchoir**
    - Historiser les **événements du système**
    - Suivre l'**état du séchoir**
    - Conserver les **masses produites**
    - Assurer une **traçabilité complète des cycles**

- ### Technologie

  - SGBD : **MariaDB**
  - Moteur : **InnoDB**
  - Encodage : **utf8mb4**

---

# 2. Schéma global de la base

| Table naturelle | Table technique | Description |
|---|---|---|
| pause | `pause` | Enregistre les pauses du séchoir |
| variété | `variete` | Enregistre les variétés de houblon |
| masse | `masse` | Enregistre les masses finales produites |
| étage | `etage` | Référence les étages physiques du séchoir |
| état séchoir | `etatSechoir` | Stocke l'état courant du séchoir |
| capteur | `capteur` | Référence les capteurs de température |
| événement | `evenement` | Enregistre les événements du système |
| lot | `lot` | Représente un cycle de séchage |
| température | `temperature` | Stocke les relevés de température |
| lot étage | `lotEtage` | Association entre un lot et un étage |

---

# 3. Structure des tables de la base

<details>
<summary>Table : pause</summary>

# Table technique : pause

## Description

Cette table enregistre les pauses effectuées durant le fonctionnement du séchoir.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_pause | INT (PK) | Identifiant unique |
| pause_type | VARCHAR(64) | Type de pause |
| pause_dateHeureDebut | DATETIME | Début de pause |
| pause_dateHeureFin | DATETIME | Fin de pause |

## Stratégie d'enregistrement

Lorsqu'une pause est déclenchée, un enregistrement est créé avec la date de début.  
Lorsque le séchoir reprend, la date de fin est renseignée.

</details>

---

<details>
<summary>Table : variete</summary>

# Table technique : variete

## Description

Cette table contient les variétés de houblon disponibles dans le système.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_variete | INT (PK) | Identifiant unique |
| variete_nom | VARCHAR(32) | Nom de la variété |
| variete_dateHeureCreation | DATETIME | Date de création |
| variete_actif | BOOLEAN | Variété active ou inactive |

## Stratégie d'enregistrement

Les variétés ne sont jamais supprimées afin de préserver l'intégrité des anciens lots.  
Une variété inutilisée est simplement désactivée.

</details>

---

<details>
<summary>Table : masse</summary>

# Table technique : masse

## Description

Cette table stocke les masses finales produites.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_masse | INT (PK) | Identifiant unique |
| masse_masse | DECIMAL(4,1) | Masse produite |
| masse_dateHeure | DATETIME | Date de saisie |

## Stratégie d'enregistrement

Une masse peut être associée à un ou plusieurs lots via la FK `id_masse`.

</details>

---

<details>
<summary>Table : etage</summary>

# Table technique : etage

## Description

Cette table référence les étages physiques du séchoir.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_etage | INT (PK) | Identifiant unique |

## Stratégie d'enregistrement

Les étages sont créés une seule fois à l'installation du système.

</details>

---

<details>
<summary>Table : etatSechoir</summary>

# Table technique : etatSechoir

## Description

Cette table stocke l'état courant du séchoir.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_etatSechoir | INT (PK) | Identifiant unique |
| etatSechoir_status | VARCHAR(32) | État actuel |
| etatSechoir_dataMaj | DATETIME | Dernière mise à jour |
| etatSechoir_pauseDebut | DATETIME | Début de pause |
| etatSechoir_ajoutMinute | SMALLINT | Minutes ajoutées |
| etatSechoir_seuilMin | DECIMAL(4,1) | Température minimale |
| etatSechoir_seuilMax | DECIMAL(4,1) | Température maximale |

## Stratégie d'enregistrement

Un seul état actif est attendu simultanément.  
Les seuils min/max sont enregistrés directement dans cette table.

</details>

---

<details>
<summary>Table : capteur</summary>

# Table technique : capteur

## Description

Cette table référence les capteurs de température.

## Structure

| Champ | Type | Description |
|---|---|---|
| addresse_capteur | VARCHAR(32) (PK) | Adresse physique |
| capteur_nom | VARCHAR(32) | Nom du capteur |
| capteur_gpio | VARCHAR(8) | GPIO utilisé |
| capteur_actif | BOOLEAN | État du capteur |

## Stratégie d'enregistrement

Les capteurs ne sont jamais supprimés afin de conserver l'historique des mesures.

</details>

---

<details>
<summary>Table : evenement</summary>

# Table technique : evenement

## Description

Cette table enregistre tous les événements du système.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_event | INT (PK) | Identifiant unique |
| event_type | VARCHAR(64) | Type d'événement |
| event_description | VARCHAR(2048) | Description |
| event_dateHeureDebut | DATETIME | Début |
| event_dateHeureFin | DATETIME | Fin |

## Stratégie d'enregistrement

Chaque événement important du système doit être enregistré afin de conserver un historique complet.

</details>

---

<details>
<summary>Table : lot</summary>

# Table technique : lot

## Description

Cette table représente un cycle de séchage de houblon.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_lot | INT (PK) | Identifiant unique |
| lot_remplissage | TINYINT | Taux de remplissage |
| lot_dateHeureEntree | DATETIME | Début du lot |
| lot_dateHeureSortie | DATETIME | Fin du lot |
| lot_dureeTheorique | INT | Durée théorique |
| lot_actif | BOOLEAN | Lot actif ou terminé |
| id_masse | INT (FK) | Référence masse |
| id_variete | INT (FK) | Référence variété |

## Contraintes

- FK `id_masse`
  - `ON DELETE SET NULL`
  - `ON UPDATE CASCADE`

- FK `id_variete`
  - `ON DELETE RESTRICT`
  - `ON UPDATE CASCADE`

## Stratégie d'enregistrement

Lorsqu'un lot démarre :

- `lot_actif = 1`
- `lot_dateHeureSortie = NULL`

Lorsqu'il se termine :

- `lot_actif = 0`
- `lot_dateHeureSortie` est renseigné

</details>

---

<details>
<summary>Table : temperature</summary>

# Table technique : temperature

## Description

Cette table stocke les relevés de température.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_temperature | INT (PK) | Identifiant unique |
| temperature_valeur | DECIMAL(4,1) | Température mesurée |
| temperature_dateHeure | DATETIME | Date de mesure |
| addresse_capteur | VARCHAR(32) (FK) | Capteur associé |

## Contraintes

- FK `addresse_capteur`
  - `ON DELETE CASCADE`
  - `ON UPDATE CASCADE`

## Stratégie d'enregistrement

Les températures sont enregistrées périodiquement automatiquement.

</details>

---

<details>
<summary>Table : lotEtage</summary>

# Table technique : lotEtage

## Description

Table d'association entre les lots et les étages.

## Structure

| Champ | Type | Description |
|---|---|---|
| id_lot | INT (FK/PK) | Référence lot |
| id_etage | INT (FK/PK) | Référence étage |
| lotEtage_dateDebut | DATETIME | Début présence |
| lotEtage_dateFin | DATETIME | Fin présence |

## Contraintes

- FK `id_lot`
  - `ON DELETE CASCADE`
  - `ON UPDATE CASCADE`

- FK `id_etage`
  - `ON DELETE CASCADE`
  - `ON UPDATE CASCADE`

## Stratégie d'enregistrement

Lorsqu'un lot est placé dans un étage :

- création d'un enregistrement
- `lotEtage_dateFin = NULL`

Lors d'un changement :

- fermeture de l'ancien enregistrement
- création d'un nouveau

</details>

---

# 4. Index SQL

## Description

Des index ont été ajoutés afin d'améliorer les performances des recherches SQL.

## Index présents

| Index | Table | Champ |
|---|---|---|
| idx_temperature_date | temperature | temperature_dateHeure |
| idx_evenement_date | evenement | event_dateHeureDebut |
| idx_lot_actif | lot | lot_actif |
| idx_capteur_actif | capteur | capteur_actif |

## Objectif

Les index permettent :

- d'accélérer les recherches
- d'améliorer les tris
- d'optimiser les filtres `WHERE`

Exemple :

```sql
SELECT *
FROM temperature
WHERE temperature_dateHeure >= NOW() - INTERVAL 1 DAY;
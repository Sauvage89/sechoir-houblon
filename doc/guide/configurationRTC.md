# Question

<details>
<summary>Pourquoi utiliser une RTC externe ?</summary>  

---

- La Raspberry Pi est déployée dans une zone sans accès réseau (zone blanche).  
En l'absence de connexion Internet, elle ne peut pas synchroniser son horloge via un serveur NTP.

- Sans RTC matérielle :
  - L'heure est perdue à chaque coupure d'alimentation
  - Les logs deviennent incohérents
  - Les tâches planifiées (cron, services temporisés) peuvent dysfonctionner
  - Les certificats ou mécanismes de sécurité basés sur le temps peuvent échouer

- L'ajout d'une RTC externe permet :
  - De conserver l'heure même hors tension
  - D'assurer une horodatation fiable au démarrage
  - De garantir le bon fonctionnement des services dépendants du temps

</details>

<details>
<summary>Qu'est-ce qu'une RTC externe ?</summary>

---

- Une RTC (Real-Time Clock) externe est un module matériel autonome chargé de maintenir l'heure et la date, même lorsque le système principal est hors tension.

- Elle intègre généralement :
  - Un oscillateur précis
  - Une pile pour l'alimentation de secours
  - Une interface de communication

- Dans le cas d'une Raspberry Pi, une RTC externe permet :
  - De conserver l'heure sans connexion Internet
  - D’assurer une horodatation fiable pour les logs

- Exemples de circuits RTC courants :
  - DS3231
  - DS1307
  - PCF8523

</details>

<details>
<summary>Comment choisir une RTC externe ?</summary>

---

- Le choix d’une RTC externe dépend du contexte d’utilisation, des contraintes environnementales et des exigences de précision.

- Critères techniques principaux :

  - Précision et dérive :
    - Exprimée en ppm (parts per million)
    - ±2 ppm ≈ ±1 minute par an

  - Interface de communication :
    - I2C (le plus courant et compatible Raspberry Pi)
    - SPI (plus rare pour les RTC classiques)

  - Tension de fonctionnement :
    - Compatibilité de tensions de fonctionnement.

  - Alimentation de secours :
    - Pile bouton
    - Supercondensateur
    - Durée de rétention attendue

  - Plage de température

  - Consommation :
    - Courant en mode sauvegarde
    - Impact sur la durée de vie de la pile

- Comparaison de modèles courants :

  - DS1307 :
    - I2c
    - 5V
    - Précision moyenne
    - La retention dépend de la pile.
    - Faible coût

  - DS3231 :
    - I2c
    - 3.3V
    - Haute précision
    - Rétention ~ 6 ans
    - Moyen coût

  - PCF8523 :
    - I2c
    - 3.3V
    - Précision moyenne
    - Rétention ~ 6 an
    - Faible coût

- Recommandation générale :

  - Usage critique / zone blanche / besoin de fiabilité :
    → DS3231

  - Incompatibilité de tensions :
    → DS1307

  - Compromis consommation / précision :
    → PCF8523

</details>

<details>
<summary>Quel est le modèle de la RTC externe utilisé ?</summary>

---

Aucun module RTC externe vas être utilisé car au cours du devellopement du projet la Raspberry Pi 5 a était fourni.

Cette Raspberry Pi 5 intégre une RTC. Il faut brancher une pile au connectique de la RTC interne de la carte électronique pour faire fonctionner
la RTC interne.

</details>

<details>
<summary>Exemple de trame I2C d'une RTC externe</summary>

---

## Connexion matérielle

| Module DS1307 | Raspberry Pi 3 B+ (GPIO) |
|---------------|--------------------------|
| VCC           | 3.3V (Pin 1)             |
| GND           | GND (Pin 6)              |
| SDA           | GPIO2 (Pin 3)            |
| SCL           | GPIO3 (Pin 5)            |

### Commande utilisée
```bash
$ sudo hwclock -r	# demande la date et l'heure a la RTC
```

### Trame I²C observée
```bash
TRAM : 0x68 0x00 RESTART 0x68 0x03
```

- Interprétation :
  - 0x68 → Adresse I²C du périphérique  
  - 0x00 → Registre des secondes  
  - RESTART → Repeated START I²C  
  - 0x68 → Adresse I²C (lecture)  
  - 0x03 → Valeur retournée par la RTC (secondes)

---

## Décomposition de la communication

### TRAME 1 — Adresse + écriture
  - 7 bit pour l'addr `1101000` : master  
  - 1 bit R/W `0` (Write) : master  
  - 1 bit ACK `0` : slave  

### TRAME 2 — Sélection du registre
  - 8 bits registre : `00000000` : master
  - 1 bit ACK : `0` : slave  

### TRAME 3 — Repeated START
  - SDA passe à `1`
  - SCL passe à `1`
  - SDA passe à `0`
  - SCL passe à `0`

### TRAME 4 — Adresse + lecture
  - 7 bits adresse : `1101000` : slave  
  - 1 bit R/W : `1` (Read) : slave  
  - 1 bit ACK : `0` : master

### TRAME 5 — Donnée retournée par la RTC
  - 8 bits data : `00000011` : slave  
  - 1 bit ACK : `0` : master  
  → le master souhaite continuer la communication

`SUITE NON DECODER`  

Les 5 tram ce situe dans le dossier `doc/img/`  

</details>

---

# Configuration d'une RTC

### 1️⃣ Connexion matérielle

> Raspberry Pi 3 B+

Interface I2C sur la Raspberry Pi 3 B+ :

| Module DS1307 | Raspberry Pi 3 B+ (GPIO) |
|---------------|--------------------------|
| VCC           | 3.3V (Pin 1)             |
| GND           | GND (Pin 6)              |
| SDA           | GPIO2 (Pin 3)            |
| SCL           | GPIO3 (Pin 5)            |

> Raspberry Pi 5

Brancher une batterie RTC sur le connecteur RTC (BAT) de la Raspberry Pi 5.  
Cette batterie alimente l’horloge temps réel lorsque la carte est hors tension.

---

### 2️⃣ Activer l’I2C sur le système

> Raspberry Pi 3 B+

#### 1. Lancer la configuration :  
```bash
sudo raspi-config
```
#### 2. Aller à Interface Options → I2C → Enable  

#### 3. Redémarrer la Pi :  
```bash
sudo reboot
```

#### 4. Installer les outils I2C : 
```bash
sudo apt update  
sudo apt install i2c-tools
```

#### 5. Vérifier la détection du module :  
```bash
sudo i2cdetect -y 1
```

L’adresse I2C 0x68 doit apparaître dans le tableau.
Elle peut aussi apparaître comme 'UU'.

> Raspberry Pi 5

Riens n'est a faire pour la Raspberry Pi 5

---

### 3️⃣ Désactiver le faux RTC logiciel

> Raspberry Pi 3 B+ et Raspberry Pi 5

```bash
sudo apt purge fake-hwclock  
sudo systemctl disable fake-hwclock
```

---

### 4️⃣ Ajouter la RTC dans le système

> Raspberry Pi 3 B+

Éditer le fichier de configuration :  
```bash
sudo nano /boot/framework/config.txt
```

Ajouter à la fin :  
**dtoverlay=i2c-rtc,ds1307**

Puis redémarrer.

> Raspberry Pi 5

Riens n'est a faire pour la Raspberry Pi 5

---

### 5️⃣ Synchroniser l’heure système ↔ RTC

> Raspberry Pi 3 B+ et Raspberry Pi 5

#### Régler manuellement :  
```bash
sudo timedatectl set-timezone Europe/Paris
sudo date -s "2026-02-25 14:30:00"  
sudo hwclock -w   # Ecris l'heure system dans la RTC externe
```

Puis reboot.

#### Si hwclock n'est pas installer :
- Il faut installer le paquet `util-linux-extra`
```bash
sudo apt install util-linux-extra
```

---

### 6️⃣ Vérifier le fonctionnement de la RTC

> Raspberry Pi 3 B+ et Raspberry Pi 3 B+

Après reboot :  
```bash
sudo hwclock -r
```

- Si l’heure s’affiche correctement → la RTC fonctionne.  
- Sinon, vérifier le câblage et l’activation I2C.

---

### 7️⃣ Vérification finale

> Raspberry Pi 3 B+ et Raspberry Pi 3 B+

- Débrancher la Pi ou couper l’alimentation  
- Redémarrer  
- Vérifier l’heure
- Si l’heure est correcte → configuration validée
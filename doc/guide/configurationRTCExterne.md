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

- Le module RTC utilisé est basé sur le circuit **DS1307**.

- Le DS1307 est une horloge temps réel (RTC) intégrant :
  - Un oscillateur à quartz
  - Une mémoire pour conserver la date et l'heure
  - Une alimentation de secours via pile
  - Une interface de communication I2C

- Caractéristiques principales :
  - Interface : I2C
  - Adresse I2C par défaut : 0x68
  - Tension de fonctionnement : 5V (nécessite adaptation niveau logique pour 3.3V sur Raspberry Pi)
  - Précision typique : ±2 à ±5 minutes par mois (moins précise que le DS3231)

- Limitations / points à noter :
  - Sensible aux variations de température
  - Nécessite un niveau logique compatible 3.3V ou un convertisseur de niveau
  - Dérive plus importante que les modèles avec TCXO (DS3231)

- Avantages :
  - Faible coût
  - Facile à trouver et à interfacer via I2C
  - Compatible avec la plupart des systèmes embarqués simples

</details>

---

# Configuration d'une RTC externe

### 1️⃣ Connexion matérielle

Pour une interface I2C sur la Raspberry Pi 3 B+ :

| Module DS1307 | Raspberry Pi 3 B+ (GPIO) |
|---------------|--------------------------|
| VCC           | 3.3V (Pin 1)             |
| GND           | GND (Pin 6)              |
| SDA           | GPIO2 (Pin 3)            |
| SCL           | GPIO3 (Pin 5)            |

---

### 2️⃣ Activer l’I2C sur le système

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

Tu devrais voir 'UU' à l’adresse I2C du DS1307 (qui est 68).

---

### 3️⃣ Désactiver le faux RTC logiciel
```bash
sudo apt purge fake-hwclock  
sudo systemctl disable fake-hwclock
```

---

### 4️⃣ Ajouter la RTC dans le système

Éditer le fichier de configuration :  
```bash
sudo nano /boot/framework/config.txt
```

Ajouter à la fin :  
**dtoverlay=i2c-rtc,ds1307**

Puis redémarrer.

---

### 5️⃣ Synchroniser l’heure système ↔ RTC

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

Après reboot :  
```bash
sudo hwclock -r
```

- Si l’heure s’affiche correctement → la RTC fonctionne.  
- Sinon, vérifier le câblage et l’activation I2C.

---

### 7️⃣ Vérification finale

- Débrancher la Pi ou couper l’alimentation  
- Redémarrer  
- Vérifier l’heure
- Si l’heure est correcte → configuration validée
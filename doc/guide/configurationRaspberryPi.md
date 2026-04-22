# Installation de l’OS Raspberry Pi sur une carte SD

### Note
	Pour l’installation de l’OS Raspberry Pi, il faut connecter la carte SD à un PC à l’aide du lecteur de carte.  
	La carte SD doit être vierge.  
	Il faut lancer le logiciel Raspberry Pi Imager et suivre les étapes d’installation.

### Logiciel nécessaire :
- Raspberry Pi Imager

### Matériel nécessaire :
- Carte SD vierge > 16 Go  
- Lecteur de carte SD

### Étapes d’installation de l’imager
1. **Appareil** : choisir l’appareil correspondant sur lequel la carte SD sera utilisée (Raspberry Pi 3)
2. **OS** : choisir une version de l’OS (64-bit)
3. **Stockage** : sélectionner la carte SD comme périphérique de stockage
4. **Personnalisation – Nom d’hôte** : donner un nom d’hôte (rasbpiSechoir)
5. **Personnalisation – Localisation** : définir la ville, le fuseau horaire et le type de clavier (Paris (France), Europe/Paris, fr)
6. **Personnalisation – Utilisateur** : définir un nom d’utilisateur et un mot de passe (user_sechoir, password)
7. **Personnalisation – Wi-Fi** : sélectionner un "réseau sécurisé" et renseigner le SSID et le mot de passe du réseau Wi-Fi (sechoir, password)
8. **Personnalisation – Accès à distance** : ne pas activer le SSH
9. **Personnalisation – Raspberry Pi Connect** : ne pas activer Raspberry Pi Connect
10. **Écriture** : validation des éléments renseignés et formatage de la carte SD
11. **Terminé** : la carte SD contient un OS Raspberry Pi

# Premier lancement de la Raspberry Pi

### Note
	J'appelle "module" la Raspberry Pi

### Mettre à jour l’heure
Il faut mettre l’heure à jour :
- soit on connecte le module à Internet et on récupère l’heure en ligne
- soit on configure l’heure manuellement


### Mettre à jour le système
- `sudo apt update`  
  Met à jour la liste des paquets disponibles depuis les dépôts configurés.  
  Cette commande ne modifie aucun paquet installé.

- `sudo apt upgrade`  
  Met à jour tous les paquets installés vers leur dernière version disponible.  
  Les paquets obsolètes sont remplacés
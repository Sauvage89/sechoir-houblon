### Explication des taches des 3 étudiant

#### Étudiant 1
<details>
<summary>Tâches</summary>

	- Instalation de la RTC pour la rasberypi
	- Configuration de la RTC pour la rasberypi
	- Instalation du service web pour la rasberypi
	- Configuration du service web pour la rabserypi
	- Définir l'IHM de saisie des paramètres de l'utilisateur
	- Définir l'IHM de visualisation des données pour l'utilisateur
</details>

<details>
<summary>Tâches simplifier</summary>

	- S'occuper de la RTC de la rasberypi
	- S'occuper du module web de la raberypi
	- Conception de l'IHM sur la partie de saisie des paramètres de l'utilisateur
	- Conception de l'IHM sur la partie de visualisation des donnée pour l'utilisateur
</details>

---
#### Étudiant 2
<details>
<summary>Tâches</summary>

	- Choisir et mettre en œuvre les capteurs
	- Choisir et mettre en œuvre la sirène
	- Choisir et mettre en œuvre les interfaces de puissance pour le séchage et l’alerte sonore
	- Developer le cas d’utilisation Mesurer
	- Developer le cas d’utilisation Alerter dépassement température
	- Developer le cas d’utilisation Chauffer
</details>

<details>
<summary>Tâches détaillées</summary>

### Capteurs
	Les capteurs de température sont à choisir.
	Il faut 6 capteurs de température
	Les capteurs doivent être étanches.
	Les capteurs doivent être peu onéreux. 
	Les capteurs doivent être anti-poussière.
	Les capteurs doivent avoir une longueur de fil environ 5m pour permettre une connexion directe au Raspberry.
### Sirène
	La sirène est à choisir.
	Interface de puissance à choisir dans le catalogue du lycée.
	Elle doit se déclencher lorsque deux mesures successives sont supérieures à 60°C.
### Developer le cas d’utilisation Mesurer
	Les capteurs de température doivent avoir une précision minimum de +/- 0,5°C dans la zone utile pour le contrôle du séchage.
### Alerter  en cas de dépassement  de la température
	L’alerte s’effectuera via un signal sonore.
### Developer le cas d’utilisation Chauffer
	Le pilotage du brûleur doit être effectué en fonction des températures configurées.
	La température moyenne doit être située entre 50 et 55°C.
	Le chauffage démarre quand la température devient inférieure à 50°C.
	Le chauffage s’arrête quand la température devient supérieure à 60°C.
	Il est nécessaire de choisir une commande compatible avec le Raspberry.
### BDD
	Les informations sur les températures, les heures, les commandes ou pas de chauffage, les alertes éventuelles sont sauvegardées dans la BDD.
</details>

---
#### Étudiant 3

---
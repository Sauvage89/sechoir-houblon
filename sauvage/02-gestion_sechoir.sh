#!/bin/bash

# Le code est lancer toute les minute est permet de gérer la temperature du sechoir

# Je prend une temperature d'un capteur, si elle est abérante ou error je réessaye de la prendre
# Je prend 6 fois cette meme messure
# je trie en ordre croisant mes 6 messure d'un capteur
# j'enleve les tempererature extreme (0 et 5)
# Je moyenne la temperature du capteur
# Je garde bien en mémoire la temperature de mon capteur
# Je fait ceci pour mes 6 capteur

CAPTEURS=("CAP1" "CAP2" "CAP3" "CAP4" "CAP5" "CAP6")
TEMPS=()

get_valid_temp() {
	local capteur_id=$1
	local value

	value=$(./get_temperature "$capteur_id")

	# filtre erreurs (-500, -501, etc.)
	if ! [[ "$value" =~ ^-?[0-9]+([.][0-9]+)?$ ]]; then
		echo ""
		return
	fi

	echo "$value"
}

for i in {0..5}
do
	values=()
	capteur="${CAPTEURS[$i]}"
	attempt=0
	max_attempts=50

	# 6 mesures valides
	while [ "${#values[@]}" -lt 6 ] && [ "$attempt" -lt "$max_attempts" ]
	do
		temp=$(get_valid_temp "$i")

		if [ -n "$temp" ]; then
			values+=("$temp")
		else
			echo "Capteur $capteur : lecture échouée"
		fi

		attempt=$((attempt + 1))
	done

	# sécurité
	if [ "${#values[@]}" -lt 6 ]; then
		echo "Capteur $capteur : données insuffisantes"
		TEMPS[$i]="ERROR"
		continue
	fi

	# tri
	sorted=($(printf '%s\n' "${values[@]}" | sort -n))

	# moyenne sans extrêmes (1..4)
	sum=0

	for j in 1 2 3 4
	do
		sum=$(echo "$sum + ${sorted[$j]}" | bc -l)
	done

	average=$(echo "scale=2; $sum / 4" | bc -l)

	echo "Capteur $capteur : $average"

	TEMPS[$i]=$average

done

# On lis la temp 1, si ce n'est ni erreur, et si elle fait partie d'un seuil défini alors on ne fait riens.
# sinon si cette temp est une erreur il faut le detecter, montre moi la detection j'écrirais la suite du code
# sinno si la temp est en dessous du seuil min alors il faut vérifier si le sechoir vient d'être mis en route récement (fait un teste simple je l'ajusterais), si oui
# alors on ne fait riens sinon tu me montre le fait qu'on a détecter sa.
# sinon si la temp est au dessus du seuil max alors tu me montre le fait qu'on a détecter sa.

# =========================
# SEUILS (à ajuster)
# =========================
SEUIL_MIN=15
SEUIL_MAX=25


for i in {0..5}
do
	capteur="${CAPTEURS[$i]}"
	capteur_value="${TEMPS[$i]}"

	# =========================
	# 1) Erreur capteur
	# =========================
	if [[ "$capteur_value" == "ERROR" ]]; then
		# IL FAUT VENIR ENREGISTRER UN EVENT DE CAPTEUR XXX EN ETAT D'ERROR
		echo "ALERTE : $capteur en erreur (capteur HS ou données invalides)"
		continue
	fi

	# =========================
	# 2) Hors seuil bas
	# =========================
	if (( $(echo "$capteur_value < $SEUIL_MIN" | bc -l) )); then

		# récupération start séchoir
		start_time=$(mysql -u dbsechoir -p'password' -N -s base_sechoir \
		-e "SELECT event_dateHeureDebut
			FROM evenement
			WHERE event_type = 'START_SECHOIR'
			ORDER BY event_dateHeureDebut DESC
			LIMIT 1;")

		if [ -z "$start_time" ]; then
			start_sec=0
		else
			start_sec=$(date -d "$start_time" +%s)
		fi
		now_sec=$(date +%s)

		diff=$(( now_sec - start_sec ))

		# 30 minutes = 1800 sec
		if [ "$diff" -lt 1800 ]; then
			echo "$capteur SOUS SEUIL mais séchoir en phase de démarrage -> ignoré"
		else
			# IL FAUT VENIR ALLUMER LE BRULEUR
			# IL FAUT VENIR ENREGISTRER UN EVENT D'ESSAI D'ALLUMAGE DU BRULEUR AVEC UNE DESCRIPTION 
			# DANS LA DESCRIPTION ON DIT SI LE BRULEUR ETAIT DEJA ACTIF OU NON
			# IL FAUT VENIR ENREGISTRER UN EVENT DE TEMPERATURE TROP BASSE
			echo "ALERTE : $capteur température trop basse"
		fi

		continue
	fi

	# =========================
	# 3) Hors seuil haut
	# =========================
	if (( $(echo "$capteur_value > $SEUIL_MAX" | bc -l) )); then
		# IL FAUT VENIR ETEINDRE LE BRULEUR
		# IL FAUT VENIR ENREGISTRER UN EVENT D'ESSAI D'ETEINDRE LE BRULEUR AVEC UNE DESCRIPTION 
		# DANS LA DESCRIPTION ON DIT SI LE BRULEUR ETAIT DEJA ACTIF OU NON
		# IL FAUT VENIR ENREGISTRER UN EVENT DE TEMPERATURE TROP HAUTE
		echo "ALERTE : $capteur température trop élevée"
	fi

done
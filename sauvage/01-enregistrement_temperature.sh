#!/bin/bash

# Le code est lancer toute les 15 minute est fait un enregistrement des temperature

CAPTEURS=("CAP1" "CAP2" "CAP3" "CAP4" "CAP5" "CAP6")

for i in {0..5}
do
	capteur="${CAPTEURS[$i]}"
	values=()

	# Récupération 6 fois
	for j in {1..6}
	do
		value=$(./get_temperature "$i")
		if [[ "$value" == -5* ]]; then
			echo "Capteur $capteur : erreur ($value), valeur ignorée"
			continue
		fi
		values+=("$value")
	done

	if [ "${#values[@]}" -lt 4 ]; then
		echo "Capteur $capteur : pas assez de données valides enregistrement non pris en charge"
		continue
	fi

	# Tri croissant
	sorted=($(printf '%s\n' "${values[@]}" | sort -n))

	# Somme sans les 2 extrêmes
	sum=0
	for j in 1 2 3 4
	do
		sum=$(awk "BEGIN {print $sum + ${sorted[$j]}}")
	done

	# Moyenne
	average=$(awk "BEGIN {print $sum / 4}")

	echo "Capteur $capteur : $average"

	mysql -u dbsechoir -p'password' base_sechoir \
	-e "INSERT INTO temperature (temperature_valeur, temperature_dateHeure, addresse_capteur)
	VALUES ($average, NOW(), '$capteur');"

done
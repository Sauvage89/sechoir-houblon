#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdbool.h>
#include <mariadb/mysql.h>

#define INTERVALLE_MESURE_SEC 60                 // Pause entre deux relevés

// --- CONFIGURATION ---
char PATH_SENSOR1[] = "/sys/bus/w1/devices/28-00000689cb7e/w1_slave";           // Chemin du capteur 1
char PATH_SENSOR2[] = "/sys/bus/w1/devices/28-00000a5464f1/w1_slave";           // Chemin du capteur 2
char PATH_SENSOR3[] = "/sys/bus/w1/devices/28-3c01d0750ec7/w1_slave";           // Chemin du capteur 3
char PATH_SENSOR4[] = "/sys/bus/w1/devices/28-3c01d075adb7/w1_slave";           // Chemin du capteur 4


MYSQL *connexion_bdd()
{
    MYSQL *conn = mysql_init(NULL);
    
    if (mysql_real_connect(conn, "localhost", "root", "password", "base_sechoir", 0, NULL, 0))
        return (conn);
    else
    {
        fprintf(stderr, "Erreur de connexion : %s\n", mysql_error(conn));
        mysql_close(conn);
        return (NULL);
    }
}


int query_get_seuil(MYSQL *conn, int *seuilMin, int *seuilMax)
{
    if (mysql_query(conn, "SELECT lot_seuilMin, lot_seuilMax FROM lot"))
    {
        fprintf(stderr, "Erreur de la lecture des seuils : %s\n", mysql_error(conn));
        return (-1);
    }

    MYSQL_RES *result =mysql_store_result(conn);
    MYSQL_ROW row = mysql_fetch_row(result);
    
    if (row != NULL)
    {
        *seuilMin = atof(row[0]);
        *seuilMax = atof(row[1]);
        
        printf("Seuil Max : %.2f | Seuil Min : %.2f\n", seuilMax, seuilMin);
    }
    
    mysql_free_result(result);
}

// --- FONCTION DE LECTURE (Identique) ---
float read_temp_fiable(char *sensor_path) {
    float mesures[5];
    int nb_valides = 0;
    float somme = 0;
    

    for (int i = 0; i < 5; i++) {
        // Pointeur vers le fichier du capteur
        FILE* file;

        // Buffer qui stocke les données lues
        char buffer[256];

        // Ouverture du fichier en lecture
        file = fopen(sensor_path, "r");

        // Si le fichier ne s'ouvre pas, on passe à la lecture suivante
        if (file == NULL)
            continue;

        // Remise à zéro du buffer pour éviter les anciennes données
        if (!memset(buffer, 0, sizeof(buffer)))
            // Lecture du contenu du fichier dans le buffer
            if (!fread(buffer, sizeof(char), 255, file))
                // Fermeture du fichier
                fclose(file);

        // Recherche du mot "YES" qui indique une mesure valide
        if (strstr(buffer, "YES") != NULL) {
            // Recherche de "t=" dans les données du capteur
            char* t_ptr = strstr(buffer, "t=");

            // Vérifie que la température existe
            if (t_ptr != NULL) {
                // Conversion de la valeur texte en float puis division par 1000 pour obtenir des °C
                float temp_instante = atof(t_ptr + 2) / 1000.0;

                // Stockage de la température dans le tableau des mesures
                mesures[nb_valides] = temp_instante;

                // Incrémente le nombre de mesures valides
                nb_valides++;
            }
        }
        // Si "YES" n'est pas trouvé, la mesure est invalide 
        else {
            printf("Y a pas de YES\n");
        }
        // Pause de 50 ms entre deux lectures
        usleep(50000);
    }

    if (nb_valides > 2) 
    {
        // TRI DU TABLEAU (ordre croissant)
        for (int i = 0; i < nb_valides - 1; i++) 
        {
            for (int j = 0; j < nb_valides - i - 1; j++) 
            {
                // Si deux valeurs sont dans le mauvais ordre
                if (mesures[j] > mesures[j + 1]) 
                {
                    // Échange des deux valeurs pour les remettre dans l'ordre
                    float tmp = mesures[j];
                    mesures[j] = mesures[j + 1];
                    mesures[j + 1] = tmp;
                }
            }
        }
        // CALCUL DE LA SOMME SANS MIN ET MAX
        float somme = 0;

        // On commence à 1 pour ignorer la plus petite valeur, on s'arrête à nb_valides - 1 pour ignorer la plus grande
        for (int i = 1; i < nb_valides - 1; i++) 
        {
            // On additionne uniquement les valeurs "fiables"
            somme += mesures[i];
        }
        // CALCUL DE LA MOYENNE FINALES
        return somme / (nb_valides - 2);
    }
    // Si pas assez de valeurs, impossible de faire une moyenne fiable
    return -1;
}


int main () 
{
    printf("=== SURVEILLANCE DOUBLE BUS (6 SONDES) ===\n");
    printf("--------------------------------------------\n");

    float seuilMax = 28;
    float seuilMin = 23;
    float temperature;
    char sensors[4][1024];
    strcpy(sensors[0], PATH_SENSOR1);
    strcpy(sensors[1], PATH_SENSOR2);
    strcpy(sensors[2], PATH_SENSOR3);
    strcpy(sensors[3], PATH_SENSOR4);

	
	for (int i = 0; i < 4; i++)
	{
		temperature = read_temp_fiable(sensors[i]);
		printf("temperature capteur : %i : %f\n", i+1, temperature);
		if (temperature > seuilMax)
			printf("ATTENTION TROP CHAUD\n");
		else if (temperature < seuilMin)
			printf("ATTENTION TROP FROID\n");
		else
			printf("Bonne température !\n");
		usleep(50000);
	}

    return (0);
}

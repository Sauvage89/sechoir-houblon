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
char PATH_SENSOR5[] = "/sys/bus/w1/devices/28-00000a54129a/w1_slave";           // Chemin du capteur 5
char PATH_SENSOR6[] = "/sys/bus/w1/devices/28-00000a54230c/w1_slave";           // Chemin du capteur 6


MYSQL *connexion_bdd()
{
    MYSQL *conn = mysql_init(NULL);
    
    if (mysql_real_connect(conn, "localhost", "root", "password", "base_sechoir", 0, NULL, 0))
        return (conn);
    else
    {
        fprintf(stderr, "Erreur de connexion : %s\n", mysql_error(conn));98*
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
        FILE* file;
        char buffer[256];

        file = fopen(sensor_path, "r");
        if (file == NULL)
            continue;
        
        if (!memset(buffer, 0, sizeof(buffer)))
            printf("PROBLEM FREAD\n");
        if (!fread(buffer, sizeof(char), 255, file))
            printf("PROBLEM FREAD\n");
        fclose(file);
        

        if (strstr(buffer, "YES") != NULL) {
            char* t_ptr = strstr(buffer, "t=");
            if (t_ptr != NULL) {
                float temp_instante = atof(t_ptr + 2) / 1000.0;
                mesures[nb_valides] = temp_instante;
                nb_valides++;
            }
        }
        else {
            printf("Y a pas de YES\n");
        }
        
        usleep(50000);
    }

    if (nb_valides > 0) {
        for (int j = 0; j < nb_valides; j++) {
            somme += mesures[j];
        }
        return somme / nb_valides;
    }
    return (-1);
}


int main () 
{
    printf("=== SURVEILLANCE DOUBLE BUS (6 SONDES) ===\n");
    printf("--------------------------------------------\n");

    float seuilMax = 28;
    float seuilMin = 23;
    float temperature;
    char sensors[6][1024];
    strcpy(sensors[0], PATH_SENSOR1);
    strcpy(sensors[1], PATH_SENSOR2);
    strcpy(sensors[2], PATH_SENSOR3);
    strcpy(sensors[3], PATH_SENSOR4);
    strcpy(sensors[4], PATH_SENSOR4);
    strcpy(sensors[5], PATH_SENSOR4);
    
	while(true)
	{
		for (int i = 0; i < 6; i++)
		{
			temperature = read_temp_fiable(sensors[i]);
			printf("temperature capteur : %i : %f\n", i+1, temperature);
			if (temperature > seuilMax)
				printf("ATTENTION TROP CHAUD\n");
			else if (temperature < seuilMin)
				printf("ATTENTION TROP FROID\n");
			else
				printf("Bonne température !\n");
			usleep(5000);
		}
	}
    return (0);
}

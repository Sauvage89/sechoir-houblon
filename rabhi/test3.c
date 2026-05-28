#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdbool.h>

// --- CONFIGURATION ---
#define SENSOR_PATH "/sys/bus/w1/devices/28-00000689cb7e/w1_slave"
#define INTERVALLE_MESURE_SEC 60

// SEUILS D'ALERTE : Définit la plage de température "normale"
#define TEMP_MIN 23.0
#define TEMP_MAX 28.0

typedef struct {
    const char* nom;
    float derniere_temp;
} Sonde;

// --- FONCTION DE LECTURE (Inchangée) ---
float read_temp_fiable() {
    float mesures[5];
    int nb_valides = 0;
    float somme = 0;

    for (int i = 0; i < 5; i++) {
        FILE* file;
        char buffer[256];
        float temp_instante = -999.0;

        file = fopen(SENSOR_PATH, "r");
        if (file == NULL) {
            perror("Erreur d'ouverture");
            continue;
        }

        memset(buffer, 0, sizeof(buffer));
        fread(buffer, sizeof(char), 255, file);
        fclose(file);

        if (strstr(buffer, "YES") != NULL) {
            char* t_ptr = strstr(buffer, "t=");
            if (t_ptr != NULL) {
                temp_instante = atof(t_ptr + 2) / 1000.0;
                mesures[nb_valides] = temp_instante;
                nb_valides++;
            }
        }
        usleep(50000);
    }

    if (nb_valides > 0) {
        for (int j = 0; j < nb_valides; j++) {
            somme += mesures[j];
        }
        return somme / nb_valides;
    }
    return -1;
}

// --- PROGRAMME PRINCIPAL AVEC ALERTES ---

int main() {
    Sonde ma_sonde = { "Sonde Test", 0.0 };

    printf("=== SYSTEME DE SURVEILLANCE AVEC ALERTES ===\n");
    printf("Seuils configurés : %.1f°C - %.1f°C\n", TEMP_MIN, TEMP_MAX);
    printf("--------------------------------------------\n");

    while (true) {
        float resultat = read_temp_fiable();

        if (resultat != -1) {
            ma_sonde.derniere_temp = resultat;

            // --- LOGIQUE D'ALERTE ---
            if (ma_sonde.derniere_temp < TEMP_MIN) {
                printf("[%s] ALERT : Température trop BASSE ! %.2f°C\n", ma_sonde.nom, ma_sonde.derniere_temp);
            }
            else if (ma_sonde.derniere_temp > TEMP_MAX) {
                printf("[%s] ALERT : Température trop HAUTE ! %.2f°C\n", ma_sonde.nom, ma_sonde.derniere_temp);
            }
            else {
                printf("[%s] OK : Température normale %.2f°C\n", ma_sonde.nom, ma_sonde.derniere_temp);
            }
        }
        else {
            printf("[%s] ERREUR : Capteur inaccessible.\n", ma_sonde.nom);
        }

        fflush(stdout);
        sleep(INTERVALLE_MESURE_SEC);
    }

    return 0;
}

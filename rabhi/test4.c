 #include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdbool.h>

// --- CONFIGURATION GÉNÉRALE ---
#define INTERVALLE_MESURE_SEC 60

// --- STRUCTURE DE DONNÉES ÉVOLUÉE ---
typedef struct {
    int id;                 // Numéro du capteur (1, 2, etc.)
    const char* nom;        // Nom convivial
    const char* chemin;     // Chemin système vers le capteur
    float temp_min;         // Seuil bas personnalisé
    float temp_max;         // Seuil haut personnalisé
    float derniere_temp;    // Stockage de la mesure
} Sonde;

// --- FONCTION DE LECTURE (Identique) ---
float read_temp_fiable(const char* sensor_path) {
    float mesures[5];
    int nb_valides = 0;
    float somme = 0;

    for (int i = 0; i < 5; i++) {
        FILE* file;
        char buffer[256];

        file = fopen(sensor_path, "r");
        if (file == NULL) continue;

        memset(buffer, 0, sizeof(buffer));
        fread(buffer, sizeof(char), 255, file);
        fclose(file);

        if (strstr(buffer, "YES") != NULL) {
            char* t_ptr = strstr(buffer, "t=");
            if (t_ptr != NULL) {
                float temp_instante = atof(t_ptr + 2) / 1000.0;
                mesures[nb_valides] = temp_instante;
                nb_valides++;
            }
        }
    }

    if (nb_valides > 0) {
        for (int j = 0; j < nb_valides; j++) {
            somme += mesures[j];
        }
        return somme / nb_valides;
    }
    return -1;
}

int main() {
    // --- DÉCLARATION DES SONDES AVEC LEURS PROPRES SEUILS ---
    // Sonde 1 : Par exemple pour une chambre (Seuils serrés)
    // Sonde 2 : Par exemple pour un frigo ou l'extérieur (Seuils larges)
    Sonde mes_sondes[] = {
        {1, "", "/sys/bus/w1/devices/28-00000689cb7e/w1_slave", 25.0, 32.0, 0.0},
        {2, "", "/sys/bus/w1/devices/28-00000a5464f1/w1_slave", 20.0, 26.0, 0.0}
    };

    int nb_sondes = sizeof(mes_sondes) / sizeof(Sonde);

    printf("=== SURVEILLANCE DIFFÉRENCIÉE ACTIVÉE ===\n");
    printf("--------------------------------------------\n");

    while (true) {
        for (int i = 0; i < nb_sondes; i++) {
            float resultat = read_temp_fiable(mes_sondes[i].chemin);

            if (resultat != -1) {
                mes_sondes[i].derniere_temp = resultat;

                // Comparaison avec les seuils propres à CETTE sonde
                if (mes_sondes[i].derniere_temp < mes_sondes[i].temp_min) {
                    printf("[Capteur %d %s] !! ALERTE FROID - ACTIVATION DU BRÛLEUR !! : %.2f°C (Seuil min: %.1f)\n",
                        mes_sondes[i].id, mes_sondes[i].nom, mes_sondes[i].derniere_temp, mes_sondes[i].temp_min);
                }
                else if (mes_sondes[i].derniere_temp > mes_sondes[i].temp_max) {
                    printf("[Capteur %d %s] !! ALERTE CHAUD - MISE EN PAUSE DU BRÛLEUR !! : %.2f°C (Seuil max: %.1f)\n",
                        mes_sondes[i].id, mes_sondes[i].nom, mes_sondes[i].derniere_temp, mes_sondes[i].temp_max);
                }
                else {
                    printf("[Capteur %d %s] Température : %.2f°C\n",
                        mes_sondes[i].id, mes_sondes[i].nom, mes_sondes[i].derniere_temp);
                }
            }
            else {
                printf("[Capteur %d] ERREUR : Connexion perdue.\n", mes_sondes[i].id);
            }
        }

        printf("--------------------------------------------\n");
        fflush(stdout);
        sleep(INTERVALLE_MESURE_SEC);
    }

    return 0;
}

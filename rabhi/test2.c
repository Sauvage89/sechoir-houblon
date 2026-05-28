#include <stdio.h>    // Pour les entrées/sorties (printf, fopen)
#include <stdlib.h>   // Pour les conversions (atof)
#include <string.h>   // Pour manipuler le texte (strstr, memset)
#include <unistd.h>   // Pour les pauses (sleep, usleep)
#include <stdbool.h>  // Pour utiliser le type booléen (true/false)

// --- CONFIGURATION ---

// Chemin système direct vers ton capteur spécifique
#define SENSOR_PATH "/sys/bus/w1/devices/28-00000689cb7e/w1_slave"
// Temps d'attente entre chaque cycle de surveillance (en secondes)
#define INTERVALLE_MESURE_SEC 60

// --- STRUCTURE DE DONNÉES ---

typedef struct {
    const char* nom;        // Nom de la pièce ou du test
    float derniere_temp;    // Stockage de la température finale calculée
} Sonde; // Alias 'Sonde' pour simplifier les déclarations

// --- FONCTION DE LECTURE FIABILISÉE ---

/**
 * Lit la température en effectuant 5 tentatives.
 * Retourne la moyenne des lectures réussies ou -1 en cas d'échec total.
 */
float read_temp_fiable() {
    float mesures[5];    // Tableau pour stocker les 5 échantillons
    int nb_valides = 0;  // Compteur de lectures réussies
    float somme = 0;     // Somme pour le calcul de la moyenne

    for (int i = 0; i < 5; i++) {
        FILE* file;
        char buffer[256];
        float temp_instante = -999.0;

        // 1. Ouverture du fichier virtuel du capteur
        file = fopen(SENSOR_PATH, "r");
        if (file == NULL) {
            perror("Erreur d'ouverture");
            continue; // Passe à la tentative suivante si le fichier est occupé
        }

        // 2. Lecture du contenu (on vide le buffer avant par sécurité)
        memset(buffer, 0, sizeof(buffer));
        fread(buffer, sizeof(char), 255, file);
        fclose(file);

        // 3. Analyse du texte reçu
        // On cherche "YES" (donnée valide) et "t=" (début de la température)
        if (strstr(buffer, "YES") != NULL) {
            char* t_ptr = strstr(buffer, "t=");
            if (t_ptr != NULL) {
                // Convertit le texte après "t=" en nombre et divise par 1000
                temp_instante = atof(t_ptr + 2) / 1000.0;

                // Enregistre la mesure dans le tableau
                mesures[nb_valides] = temp_instante;
                nb_valides++;
            }
        }

        // Petite pause de 50ms entre chaque lecture pour la stabilité du bus 1-Wire
        usleep(50000);
    }

    // 4. Calcul de la moyenne des mesures valides
    if (nb_valides > 0) {
        for (int j = 0; j < nb_valides; j++) {
            somme += mesures[j];
        }
        return somme / nb_valides; // Retourne la température moyenne
    }

    return -1; // Retourne -1 si aucune mesure n'a pu être prise
}

// --- PROGRAMME PRINCIPAL ---

int main() {
    // Initialisation de notre objet sonde avec un nom convivial
    Sonde ma_sonde = { "Sonde Test Labo", 0.0 };

    printf("=== DEMARRAGE DU SYSTEME DE SURVEILLANCE ===\n");
    printf("Cible : %s\n", SENSOR_PATH);
    printf("--------------------------------------------\n");

    // Boucle infinie de surveillance
    while (true) {
        // Exécution de la lecture fiabilisée
        float resultat = read_temp_fiable();

        if (resultat != -1) {
            ma_sonde.derniere_temp = resultat;
            printf("[%s] Température moyenne : %.2f°C\n", ma_sonde.nom, ma_sonde.derniere_temp);
        } else {
            printf("[%s] ERREUR : Impossible de lire le capteur.\n", ma_sonde.nom);
        }

        // On vide le tampon de sortie pour voir l'affichage en temps réel
        fflush(stdout);

        // Pause avant le prochain cycle de 5 mesures
        sleep(INTERVALLE_MESURE_SEC);
    }

    return (0); // Le programme ne s'arrête jamais
}

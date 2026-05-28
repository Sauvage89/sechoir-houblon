#include <stdio.h>    // Inclusion de la bibliothèque standard d'entrées/sorties (printf, fopen)
#include <stdlib.h>   // Inclusion pour les fonctions utilitaires (atof)
#include <string.h>   // Inclusion pour la manipulation de chaînes de caractères (strstr)
#include <unistd.h>   // Inclusion pour les fonctions système POSIX (sleep)

// Définit une constante contenant le chemin système vers le fichier du capteur 1-Wire
#define SENSOR_PATH "/sys/bus/w1/devices/28-00000a5464f1/w1_slave"

// Déclaration de la fonction qui retourne un nombre à virgule (flottant) pour la température
float read_temp() {
    FILE *file;       // Déclare un pointeur vers un fichier
    char buffer[256]; // Crée un espace mémoire (tampon) de 256 caractères pour stocker le texte lu
    float temp_c;     // Variable pour stocker le résultat final de la température

    // Tente d'ouvrir le fichier du capteur en mode lecture seule ("r")
    file = fopen(SENSOR_PATH, "r");

    // Vérifie si l'ouverture a échoué (par exemple si le capteur est débranché)
    if (file == NULL) {
        perror("Erreur d'ouverture du fichier"); // Affiche l'erreur système correspondante
        return (-1); // Quitte la fonction en retournant -1 pour signaler une erreur
    }

    // Lit jusqu'à 256 caractères du fichier et les place dans le buffer
    fread(buffer, sizeof(char), 256, file);

    // Ferme le fichier car la lecture en mémoire est terminée
    fclose(file);

    // Cherche la sous-chaîne "YES" dans le buffer (indique que le checksum du capteur est bon)
    if (strstr(buffer, "YES") != NULL) {
	// Cherche "t=" dans le texte, puis décale le pointeur de 2 caractères pour pointer sur le chiffre
        char* temp_str = strstr(buffer, "t=") + 2;

        // Convertit la chaîne de caractères (ex: "25500") en nombre et divise par 1000
        temp_c = atof(temp_str) / 1000.0;

        return (temp_c); // Retourne la température finale en degrés Celsius
    }

    // Si "YES" n'a pas été trouvé dans le fichier
    printf("Erreur de lecture du capteur.\n");
    return (-1); // Retourne -1 pour signaler un problème de donnée
}

int main() {
    // Boucle infinie pour une lecture continue
    while (1) {
        // Appelle la fonction de lecture et stocke le résultat dans 'temp'
        float temp = read_temp();

        // Si la lecture n'a pas renvoyé d'erreur (-1)
        if (temp != -1) {
            // Affiche la température avec 2 chiffres après la virgule
            printf("Température: %.2f °C\n", temp);
        }

        // Suspend l'exécution du programme pendant 2 secondes
        sleep(2);
    }

    return (0); // Fin théorique du programme (jamais atteint ici à cause du while(1))
}

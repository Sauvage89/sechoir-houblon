#include "lmath_secure.h"

// Retourne 0 si le char en parametre est un nombre sinon un 1 si sa ne l'est pas
int	is_numb(char c)
{
	if (c >= '0' && c <= '9')
		return (0);
	return (1);
}
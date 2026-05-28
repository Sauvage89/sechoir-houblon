#include "lmath_secure.h"

// La fonction fait la puissance d'un nombre
// Elle retourne si le pow est réussi ou non
int	ft_pow_secure(int nb, int pow, int *result)
{
	*result = 1;
	while (pow > 0)
	{
		if (ft_mul_secure(*result, nb, result) == 1)
			return (1);
		pow--;
	}
	return (0);
}

// Fonction d'addition securiser par rapport au overflow en int max/min
// Elle retourne si l'addition est réussi ou non
int	ft_add_secure(int a, int b, int *result)
{
	if ((b > 0 && a > INT_MAX - b) || (b < 0 && a < INT_MIN - b))
		return (1);
	*result = a + b;
	return (0);
}

// Fonction de multiplication sécurisée par rapport au overflow en int max/min
// Elle retourne si la multiplication a réussi ou non
int	ft_mul_secure(int a, int b, int *result)
{
	if (a > 0)
	{
		if (b > 0 && a > INT_MAX / b)
			return (1);
		if (b < 0 && b < INT_MIN / a)
			return (1);
	}
	else if (a < 0)
	{
		if (b > 0 && a < INT_MIN / b)
			return (1);
		if (b < 0 && a != 0 && b < INT_MAX / a)
			return (1);
	}

	*result = a * b;
	return (0);
}
#include "lmath_secure.h"

// Retourne la longeur du nombre dans le string
int	get_len_atoi_secure(char *str)
{
	int	i;

	i = 0;
	while (is_numb(str[i]) == 0)
		i++;
	i--;
	return (i);
}

// atoi accepte les espace au début a la fin mais il est strict sur le nombre.
// "   5  "	-> valide
// "4   "	-> valide
// "3"		-> valide
// "  17  "	-> valide
// " 1  7 "	-> invalide
// "a1  "	-> invalide
int	ft_atoi_secure(char *str, int *result)
{
	int	i;
	int	mul_10;
	int	result_step;

	while (*str == ' ')
		str++;
	i = get_len_atoi_secure(str);
	*result = 0;
	while (i > 0)
	{
		if (ft_pow_secure(10, i, &mul_10) == 1)
			return (1);
		result_step = 1;
		if (ft_mul_secure(*str - '0', mul_10, &result_step) == 1)
			return (1);
		if (ft_add_secure(result_step, *result, result) == 1)
			return (1);
		i--;
		str++;
	}
	if (ft_add_secure(*str++ - '0', *result, result) == 1)
		return (1);
	while (*str == ' ')
		str++;
	if (*str == '\0')
		return (0);
	return (1);
}
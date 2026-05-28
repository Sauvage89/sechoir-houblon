#ifndef LMATH_SECURE_H
# define LMATH_SECURE_H

#include <limits.h>

// basic_operator.c
int	ft_add_secure(int a, int b, int *result);
int	ft_mul_secure(int a, int b, int *result);
int	ft_pow_secure(int nb, int pow, int *result);
int	ft_atoi_secure(char *str, int *result);
int	is_numb(char c);

#endif // LMATH_SECURE_H
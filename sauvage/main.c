#include <unistd.h>
#include <stdio.h>
#include <fcntl.h>
#include <stdlib.h>
#include <limits.h>
#include <string.h>
#include "lmath_secure/lmath_secure.h"

const char	PATH_SENSOR1[] = "sensor1";
const char	PATH_SENSOR2[] = "sensor2";
const char	PATH_SENSOR3[] = "sensor3";
const char	PATH_SENSOR4[] = "sensor4";
const char	PATH_SENSOR5[] = "sensor5";
const char	PATH_SENSOR6[] = "sensor6";
const char	*PATH_SENSORS[] =
{
	PATH_SENSOR1,
	PATH_SENSOR2,
	PATH_SENSOR3,
	PATH_SENSOR4,
	PATH_SENSOR5,
	PATH_SENSOR6
};

// Code erreur -500
double	get_temperature(int id_capteur)
{
	int	fd;
	int	i;
	double	temperature;
	char	*buffer;

	buffer = malloc(512);
	if (buffer == NULL)
		return (-500);
	fd = open(PATH_SENSORS[id_capteur], O_RDONLY);
	if (fd == -1)
		return (-501);
	i = read(fd, buffer, 100);
	if (i == -1)
		return (-502);
	buffer[i] = '\0';
	close(fd);
	buffer = strstr(buffer, "YES");
	if (buffer == NULL)
		return (-503);
	buffer = strstr(buffer, "t=");
	if (buffer == NULL)
		return (-504);
	buffer += 2;
	i = 0;
	while (is_numb(buffer[i]) == 0)
		i++;
	buffer[i] = '\0';
	temperature = atof(buffer);
	temperature /= 1000;
	return (temperature);
}

int	main(int argc, char **argv)
{
	int	nb;

	if (argc != 2)
	{
		printf("Le programme doit être lancer avec 1 parametre.\n");
		return (1);
	}
	if (ft_atoi_secure(argv[1], &nb) == 1)
	{
		printf("Le parametre rentrer est invalide.\n");
		return (1);
	}
	printf("%lf", get_temperature(nb));
	return (0);
}
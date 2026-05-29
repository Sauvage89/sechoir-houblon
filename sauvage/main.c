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

const char	*PATH_SENSORS[] = {
	PATH_SENSOR1,
	PATH_SENSOR2,
	PATH_SENSOR3,
	PATH_SENSOR4,
	PATH_SENSOR5,
	PATH_SENSOR6
};

int	ft_strstr(char **str, char *find)
{
	*str = strstr(*str, find);
	if (*str == NULL)
		return (1);
	return (0);
}

int	get_temperature_open_file(char **buffer, int id_capteur)
{
	int	fd;

	fd = open(PATH_SENSORS[id_capteur], O_RDONLY);
	if (fd == -1)
	{
		close(fd);
		free(buffer);
		return (-501);
	}
	return (fd);
}
int	get_temperature_read_file(char **buffer, int *fd)
{
	int	i;

	i = read(*fd, *buffer, 99);
	close(*fd);
	if (i == -1)
	{
		free(*buffer);
		return (-502);
	}
	(*buffer)[i] = '\0';
	return (0);
}

int	get_temperature_seek(char **buffer)
{
	if (ft_strstr(buffer, "YES") != 0)
		return (-503);
	if (ft_strstr(buffer, "t=") != 0)
		return (-504);
	return (0);
}

int	get_temperature_get_number(char **str)
{
	int	i;

	while (is_numb(**str) == 1)
		(*str)++;
	i = 0;
	while (is_numb((*str)[i]) == 0)
		i++;
	(*str)[i] = '\0';
	return (i);
}

// Code erreur :
// -500 ->
// 
// 
// 
//
double	get_temperature(int id_capteur)
{
	double	temperature;
	char	*buffer;
	int	fd;
	int	i;

	buffer = malloc(100);
	if (buffer == NULL)
		return (-500);
	if ((fd = get_temperature_open_file(&buffer, id_capteur)) < 0)
		return (fd);
	if ((i = get_temperature_read_file(&buffer, &fd)) != 0)
		return (i);
	if ((i = get_temperature_seek(&buffer)) != 0)
		return (i);
	i = get_temperature_get_number(&buffer);
	temperature = atof(buffer) / 1000;
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
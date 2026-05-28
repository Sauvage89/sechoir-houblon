NAME = lmath_secure.a

CC = gcc
CFLAGS = -Wall -Wextra -Werror

SRCS = atoi.c basic_operator.c is_xxx.c
OBJS = $(SRCS:.c=.o)

INCLUDES = -Iinclude

all: $(NAME)

# création de la lib statique
$(NAME): $(OBJS)
	ar rcs $(NAME) $(OBJS)

# compilation des .c en .o
%.o: %.c
	$(CC) $(CFLAGS) $(INCLUDES) -c $< -o $@

clean:
	rm -f $(OBJS)

fclean: clean
	rm -f $(NAME)

re: fclean all
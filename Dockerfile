# Usa un'immagine base di PHP con Apache
FROM php:8.2-apache

# Copia i file dell'app nella directory del server
COPY . /var/www/html

# Espone la porta 80 per il traffico HTTP
EXPOSE 80

# Avvia Apache
CMD ["apache2-foreground"]


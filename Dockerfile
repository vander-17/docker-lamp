FROM php:8.1-apache

# Instalar e habilitar a extensão mysqli
RUN docker-php-ext-install mysqli

# Ativar mod_rewrite para o Apache (caso precise de .htaccess)
RUN a2enmod rewrite

#!/bin/sh

# Crée le dossier s'il n'existe pas
mkdir -p /var/www/html/data

# Donne les bons droits au dossier
chown -R www-data:www-data /var/www/html/data
chmod 700 /var/www/html/data

# Si la base existe déjà, on ajuste ses permissions
if [ -f /var/www/html/data/users.sqlite ]; then
    chown www-data:www-data /var/www/html/data/users.sqlite
    chmod 600 /var/www/html/data/users.sqlite
fi

# Lancer le service PHP-FPM (ou autre CMD défini)
exec "$@"

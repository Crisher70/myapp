# APP

# Requisitos
```
Laravel 8
NPM
Composer
Base de datos MySQL
PHP 8
Apache
Para los ultimos tres requisitos, estos pueden ser cubiertos instalando XAMPP
```

# Instalación

Ejecutar los siguientes comandos en la carpeta del proyecto:
```
npm install
composer install
```
## Variables de entorno

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= Nombre de la base de datos
DB_USERNAME= Usuario de la base de datos
DB_PASSWORD= Contraseña de la base de datos
```

## Servidor Web

Ejecutar los siguientes comandos en la carpeta del proyecto:

```
php artisan key:generate
php artisan migrate --seed
npm run watch o npm run dev
```

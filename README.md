<div align="center">
  <img src="https://github.com/pabloccf/autoElite/blob/master/public/images/autoElite_logo.png" alt="AutoElite" width="300px" height="300px">
</div>

# AutoElite

## Introducción
AutoElite, mi Proyecto de Fin de Grado, se trata de una aplicación web que tiene como objetivo principal facilitar la gestión de un concesionario multimarca.

## Instalación
1. Descargar XAMPP en el caso de Linux o su equivalente en Windows y MacOS con las siguientes versiones:
     - PHP 8.2.4
     - Apache 2.4.56
     - MariaDB 10.4.28
     - Perl 5.34.1
     - phpMyAdmin 5.2.1
3. Instalar php8.2-cli, php8.2-xml, php8.2-mysql, php8.2-intl
4. Instalar Symfony 6.4 y su cli
5. Instalar composer
6. Instalar las dependencias del proyecto con el comando:
   ```bash
   composer install
   ```
8. Crear la base de datos con el comando:
   ```bash
   php bin/console doctrine:database:create
   ```
9. Actualizar la base de datos con los siguientes comandos:
    ```bash
     php bin/console make:migration
     php bin/console doctrine:migrations:migrate
    ```

## Despliegue
Una vez completados los pasos anteriores, ya podremos desplegar nuestro proyecto de Symfony ejecutando el siguiente comando:
```bash
symfony server:start
```

## Author
- **Pablo López Gosálvez** - [Github](https://github.com/pabloccf)

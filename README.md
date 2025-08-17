# app-erp

Sistema ERP desarrollado en Symfony 7.2, utilizando PostgreSQL como base de datos y Docker para la gestión de servicios.

## Requisitos

- PHP >= 8.2
- Composer
- Docker y Docker Compose
- PostgreSQL

## Instalación

1. **Clona el repositorio:**
   ```sh
   git clone <URL_DEL_REPOSITORIO>
   cd app-erp
   ```

2. **Configura las variables de entorno:**
   Edita el archivo `.env` según tus necesidades. Ejemplo de configuración:
   ```
   POSTGRES_USER=root
   POSTGRES_PASS=12345678
   POSTGRES_DB=app-erp
   ```

3. **Instala las dependencias de PHP:**
   ```sh
   composer install
   ```

4. **Levanta los servicios con Docker:**
   ```sh
   docker-compose up -d
   ```

5. **Ejecuta las migraciones:**
   ```sh
   php bin/console doctrine:migrations:migrate
   ```

## Estructura del Proyecto

- `src/`: Código fuente principal (Controladores, Entidades, Servicios, Repositorios, etc.)
- `config/`: Archivos de configuración de Symfony y bundles.
- `migrations/`: Migraciones de base de datos.
- `public/`: Punto de entrada de la aplicación.
- `postgres-data/`: Datos persistentes de PostgreSQL.
- `docker-compose.yml`: Configuración de servicios Docker.

## Principales Dependencias

- Symfony Framework 7.2
- Doctrine ORM y Migrations
- Nelmio CORS Bundle
- Firebase PHP-JWT

## Variables de Entorno

- `APP_ENV`: Entorno de la aplicación (dev, prod, etc.)
- `APP_SECRET`: Secreto de la aplicación Symfony.
- `POSTGRES_USER`, `POSTGRES_PASS`, `POSTGRES_DB`: Credenciales de la base de datos.
- `JWT_SECRET`: Secreto para la generación de tokens JWT.

## Comandos Útiles

- Limpiar caché:
  ```sh
  php bin/console cache:clear
  ```
- Ejecutar servidor de desarrollo:
  ```sh
  symfony server:start
  ```

## Licencia

Este proyecto es de uso propietario.
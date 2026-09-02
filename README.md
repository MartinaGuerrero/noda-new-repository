# NODA

Sistema de reservas y gestión de usuarios para centros educativos.

## Requisitos

- PHP 7.4+ o compatible
- MySQL / MariaDB
- Apache o servidor web compatible con PHP
- WAMP, XAMPP o similar para desarrollo local

## Configuración rápida

1. Clona el proyecto.
2. Crea un archivo `.env` basado en `.env.example`.
3. Ajusta tus credenciales de base de datos.
4. Importa la base de datos o ejecuta la migración necesaria.
5. Levanta el proyecto desde la raíz del repositorio con tu servidor local.

## Variables de entorno

Copia `.env.example` a `.env` y modifica los valores:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=novosis_noda
APP_ENV=development
```

## Base de datos

1. Crear la base de datos en MySQL.
2. Importar el esquema base si corresponde.
3. Ejecutar la migración de usuarios por centros:

```sql
ALTER TABLE usuario ADD PRIMARYA TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE usuario ADD SECUNDARIA TINYINT(1) NOT NULL DEFAULT 0;
```

o usar el archivo en `migrations/001_add_primaria_secundaria.sql`.

## Ejecutar localmente

Con WAMP/XAMPP, ubica la carpeta del proyecto dentro de la raíz web y accede desde el navegador:

```text
http://localhost/NODA/
```

## Estructura principal

- `Controlador/` : controladores y lógica de acceso a datos
- `modelo/` : modelos y consultas
- `vista/` : vistas, CSS y JS
- `migrations/` : migraciones de base de datos

## Nota

Este repositorio está preparado para usar variables de entorno y no hardcodear credenciales en código. Nunca subas un `.env` real al repositorio.
